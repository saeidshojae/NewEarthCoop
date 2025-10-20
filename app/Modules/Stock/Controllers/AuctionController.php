<?php
namespace App\Modules\Stock\Controllers;

use App\Modules\Stock\Models\Auction;
use App\Modules\Stock\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuctionController extends Controller
{
    // لیست حراج‌ها
    public function index()
    {
        $auctions = Auction::orderByDesc('id')->get();
        return view('Stock::auction_list', compact('auctions'));
    }

    // فرم ایجاد حراج جدید
    public function create()
    {
        $stock = Stock::first();
        return view('Stock::auction_create', compact('stock'));
    }

    // ذخیره حراج جدید
    public function store(Request $request)
    {
        $data = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'shares_count' => 'required|integer',
            'base_price' => 'required|numeric',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'info' => 'nullable|string',
        ]);
        Auction::create($data);
        return redirect()->route('auction.index')->with('success', 'حراج جدید ثبت شد');
    }
    
    // نمایش جزئیات حراج
    public function show(Auction $auction)
    {
        $auction->load(['stock', 'bids.user']);
        $userBids = $auction->bids()->where('user_id', auth()->id())->get();
        
        return view('Stock::auction_show', compact('auction', 'userBids'));
    }
    
    // ثبت پیشنهاد
    public function placeBid(Request $request, Auction $auction)
    {
        if (!auth()->check()) {
            return back()->with('error', 'لطفاً ابتدا وارد شوید');
        }
        
        $data = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);
        
        try {
            $auctionService = app(\App\Modules\Stock\Services\AuctionService::class);
            $bid = $auctionService->validateAndPlaceBid(
                auth()->id(),
                $auction,
                $data['price'],
                $data['quantity']
            );
            
            return back()->with('success', 'پیشنهاد شما با موفقیت ثبت شد');
            
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // لیست حراج‌ها برای پنل مدیریت
    public function adminIndex()
    {
        $auctions = \App\Modules\Stock\Models\Auction::orderByDesc('id')->get();
        return view('Stock::admin_auction_list', compact('auctions'));
    }

    // فرم ایجاد حراج جدید برای پنل مدیریت
    public function adminCreate()
    {
        $stock = \App\Modules\Stock\Models\Stock::first();
        return view('Stock::admin_auction_create', compact('stock'));
    }

    // ذخیره حراج جدید از پنل مدیریت
    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'stock_id' => 'required|exists:stocks,id',
            'shares_count' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'ends_at' => 'required|date|after:start_time',
            'type' => 'required|in:single_winner,uniform_price,pay_as_bid',
            'min_bid' => 'nullable|numeric|min:0',
            'max_bid' => 'nullable|numeric|min:0|gte:min_bid',
            'lot_size' => 'required|integer|min:1',
            'channel_id' => 'nullable|exists:groups,id',
            'info' => 'nullable|string',
        ]);
        
        $data['status'] = 'scheduled';
        Auction::create($data);
        
        return redirect()->route('admin.auction.index')->with('success', 'حراج جدید ذخیره شد');
    }
    
    // شروع حراج
    public function startAuction(Auction $auction)
    {
        if ($auction->status !== 'scheduled') {
            return back()->with('error', 'فقط حراج‌های برنامه‌ریزی شده قابل شروع هستند');
        }
        
        $auction->update(['status' => 'running']);
        
        return back()->with('success', 'حراج شروع شد');
    }
    
    // بستن دستی حراج
    public function closeAuction(Auction $auction)
    {
        if ($auction->status !== 'running') {
            return back()->with('error', 'فقط حراج‌های فعال قابل بستن هستند');
        }
        
        $auctionService = app(\App\Modules\Stock\Services\AuctionService::class);
        $auctionService->closeAuction($auction);
        
        return back()->with('success', 'حراج بسته و تسویه شد');
    }
}
