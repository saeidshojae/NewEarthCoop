<?php
namespace App\Modules\Stock\Services;

use App\Modules\Stock\Models\Auction;
use App\Modules\Stock\Models\Bid;
use App\Modules\Stock\Models\Wallet;
use App\Modules\Stock\Models\Holding;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuctionService
{
    protected $walletService;
    protected $holdingService;
    
    public function __construct(WalletService $walletService, HoldingService $holdingService)
    {
        $this->walletService = $walletService;
        $this->holdingService = $holdingService;
    }
    
    public function validateAndPlaceBid(int $userId, Auction $auction, float $price, int $quantity): Bid
    {
        return DB::transaction(function () use ($userId, $auction, $price, $quantity) {
            // Validate auction is active
            if (!$auction->isActive()) {
                throw new \Exception('Auction is not active');
            }
            
            // Validate price range
            if ($auction->min_bid && $price < $auction->min_bid) {
                throw new \Exception('Bid price below minimum');
            }
            
            if ($auction->max_bid && $price > $auction->max_bid) {
                throw new \Exception('Bid price above maximum');
            }
            
            // Validate quantity
            if ($quantity > $auction->lot_size) {
                throw new \Exception('Quantity exceeds lot size');
            }
            
            // Check wallet balance
            $wallet = $this->walletService->getOrCreateWallet($userId);
            $totalAmount = $price * $quantity;
            
            if (!$wallet->canAfford($totalAmount)) {
                throw new \Exception('Insufficient wallet balance');
            }
            
            // Hold the amount
            $this->walletService->hold($wallet, $totalAmount, "Bid for auction #{$auction->id}", $auction);
            
            // Create bid
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $userId,
                'price' => $price,
                'quantity' => $quantity,
                'status' => 'active',
            ]);
            
            return $bid;
        });
    }
    
    public function closeAuction(Auction $auction): array
    {
        return DB::transaction(function () use ($auction) {
            $auction->update(['status' => 'settling']);
            
            $results = [];
            
            switch ($auction->type) {
                case 'single_winner':
                    $results = $this->settleSingleWinner($auction);
                    break;
                case 'uniform_price':
                    $results = $this->settleUniformPrice($auction);
                    break;
                case 'pay_as_bid':
                    $results = $this->settlePayAsBid($auction);
                    break;
            }
            
            $auction->update(['status' => 'settled']);
            
            return $results;
        });
    }
    
    protected function settleSingleWinner(Auction $auction): array
    {
        $winningBid = $auction->activeBids()
            ->orderBy('price', 'desc')
            ->orderBy('created_at', 'asc')
            ->first();
        
        if (!$winningBid) {
            return ['winners' => [], 'total_settled' => 0];
        }
        
        $winningBid->update(['status' => 'won']);
        
        // Release other bids
        $auction->activeBids()
            ->where('id', '!=', $winningBid->id)
            ->update(['status' => 'lost']);
        
        $this->releaseOtherBids($auction, $winningBid->id);
        
        // Settle winning bid
        $this->settleWinningBid($winningBid);
        
        return [
            'winners' => [$winningBid],
            'total_settled' => $winningBid->quantity,
        ];
    }
    
    protected function settleUniformPrice(Auction $auction): array
    {
        $bids = $auction->activeBids()
            ->orderBy('price', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $winners = [];
        $remainingShares = $auction->shares_count;
        $clearingPrice = 0;
        
        foreach ($bids as $bid) {
            if ($remainingShares <= 0) {
                $bid->update(['status' => 'lost']);
                $this->walletService->release(
                    $this->walletService->getOrCreateWallet($bid->user_id),
                    $bid->total_value,
                    "Bid not filled - auction #{$auction->id}",
                    $bid
                );
                continue;
            }
            
            $allocatedShares = min($bid->quantity, $remainingShares);
            $remainingShares -= $allocatedShares;
            
            if ($allocatedShares > 0) {
                $bid->update(['status' => 'won']);
                $winners[] = $bid;
                $clearingPrice = $bid->price;
                
                // Settle partial allocation
                $this->settlePartialBid($bid, $allocatedShares, $clearingPrice);
            }
        }
        
        return [
            'winners' => $winners,
            'total_settled' => $auction->shares_count - $remainingShares,
            'clearing_price' => $clearingPrice,
        ];
    }
    
    protected function settlePayAsBid(Auction $auction): array
    {
        $bids = $auction->activeBids()
            ->orderBy('price', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        
        $winners = [];
        $remainingShares = $auction->shares_count;
        
        foreach ($bids as $bid) {
            if ($remainingShares <= 0) {
                $bid->update(['status' => 'lost']);
                $this->walletService->release(
                    $this->walletService->getOrCreateWallet($bid->user_id),
                    $bid->total_value,
                    "Bid not filled - auction #{$auction->id}",
                    $bid
                );
                continue;
            }
            
            $allocatedShares = min($bid->quantity, $remainingShares);
            $remainingShares -= $allocatedShares;
            
            if ($allocatedShares > 0) {
                $bid->update(['status' => 'won']);
                $winners[] = $bid;
                
                // Settle at bid price
                $this->settlePartialBid($bid, $allocatedShares, $bid->price);
            }
        }
        
        return [
            'winners' => $winners,
            'total_settled' => $auction->shares_count - $remainingShares,
        ];
    }
    
    protected function settleWinningBid(Bid $bid): void
    {
        $wallet = $this->walletService->getOrCreateWallet($bid->user_id);
        $holding = $this->holdingService->getOrCreateHolding($bid->user_id, $bid->auction->stock_id);
        
        // Settle payment
        $this->walletService->settle($wallet, $bid->total_value, "Auction settlement", $bid);
        
        // Transfer shares
        $this->holdingService->settlement($holding, $bid->quantity, "Auction win", $bid);
    }
    
    protected function settlePartialBid(Bid $bid, int $allocatedShares, float $price): void
    {
        $wallet = $this->walletService->getOrCreateWallet($bid->user_id);
        $holding = $this->holdingService->getOrCreateHolding($bid->user_id, $bid->auction->stock_id);
        
        $totalAmount = $price * $allocatedShares;
        $remainingAmount = $bid->total_value - $totalAmount;
        
        // Settle payment for allocated shares
        $this->walletService->settle($wallet, $totalAmount, "Auction settlement", $bid);
        
        // Release remaining amount
        if ($remainingAmount > 0) {
            $this->walletService->release($wallet, $remainingAmount, "Unallocated bid amount", $bid);
        }
        
        // Transfer allocated shares
        $this->holdingService->settlement($holding, $allocatedShares, "Auction win", $bid);
    }
    
    protected function releaseOtherBids(Auction $auction, int $excludeBidId): void
    {
        $auction->activeBids()
            ->where('id', '!=', $excludeBidId)
            ->get()
            ->each(function ($bid) use ($auction) {
                $wallet = $this->walletService->getOrCreateWallet($bid->user_id);
                $this->walletService->release(
                    $wallet,
                    $bid->total_value,
                    "Bid not filled - auction #{$auction->id}",
                    $bid
                );
            });
    }
}
