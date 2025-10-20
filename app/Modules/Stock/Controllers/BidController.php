<?php
namespace App\Modules\Stock\Controllers;

use App\Modules\Stock\Models\Bid;
use App\Modules\Stock\Models\Auction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    // ثبت پیشنهاد کاربر در حراج
    public function store(Request $request, $auctionId)
    {
        $data = $request->validate([
            'shares_count' => 'required|integer|min:1',
            'bid_price' => 'required|numeric|min:0',
        ]);
        $data['auction_id'] = $auctionId;
        $data['user_id'] = Auth::id();
        Bid::create($data);
        return redirect()->back()->with('success', 'پیشنهاد شما ثبت شد');
    }
}
