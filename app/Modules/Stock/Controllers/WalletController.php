<?php
namespace App\Modules\Stock\Controllers;

use App\Modules\Stock\Services\WalletService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    protected $walletService;
    
    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }
    
    // نمایش کیف‌پول کاربر
    public function index()
    {
        $wallet = $this->walletService->getOrCreateWallet(auth()->id());
        $transactions = $wallet->transactions()->latest()->paginate(20);
        
        return view('Stock::wallet_index', compact('wallet', 'transactions'));
    }
    
    // شارژ دستی کیف‌پول (ادمین)
    public function adminCredit(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);
        
        $wallet = $this->walletService->getOrCreateWallet($data['user_id']);
        $this->walletService->credit($wallet, $data['amount'], $data['description']);
        
        return back()->with('success', 'کیف‌پول با موفقیت شارژ شد');
    }
}
