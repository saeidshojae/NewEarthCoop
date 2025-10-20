<?php
namespace App\Modules\Stock\Controllers;

use App\Modules\Stock\Services\HoldingService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HoldingController extends Controller
{
    protected $holdingService;
    
    public function __construct(HoldingService $holdingService)
    {
        $this->holdingService = $holdingService;
    }
    
    // نمایش کیف‌سهام کاربر
    public function index()
    {
        $holdings = $this->holdingService->getUserHoldings(auth()->id());
        
        return view('Stock::holding_index', compact('holdings'));
    }
    
    // نمایش جزئیات یک سهم
    public function show($stockId)
    {
        $holding = $this->holdingService->getOrCreateHolding(auth()->id(), $stockId);
        $transactions = $holding->transactions()->latest()->paginate(20);
        
        return view('Stock::holding_show', compact('holding', 'transactions'));
    }
}
