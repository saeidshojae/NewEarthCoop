<?php
namespace App\Modules\Stock\Controllers;

use App\Modules\Stock\Models\Stock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    // نمایش اطلاعات پایه سهام
    public function index()
    {
        $stock = Stock::first();
        return view('Stock.Views.stock_info', compact('stock'));
    }

    // فرم تنظیم اطلاعات پایه
    public function create()
    {
        return view('Stock.Views.stock_create');
    }

    // ذخیره اطلاعات پایه سهام
    public function store(Request $request)
    {
        $data = $request->validate([
            'startup_valuation' => 'required|numeric',
            'total_shares' => 'required|integer',
            'base_share_price' => 'required|numeric',
            'info' => 'nullable|string',
        ]);
        $stock = Stock::create($data);
        return redirect()->route('stock.index')->with('success', 'اطلاعات سهام ثبت شد');
    }

    // نمایش اطلاعات پایه سهام برای پنل مدیریت
    public function adminIndex()
    {
        $stock = Stock::first();
        return view('stock.admin_stock_info', compact('stock'));
    }

    // فرم ویرایش اطلاعات پایه سهام برای پنل مدیریت
    public function adminCreate()
    {
        $stock = Stock::first();
        return view('stock.admin_stock_create', compact('stock'));
    }

    // ذخیره اطلاعات پایه سهام از پنل مدیریت
    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'startup_valuation' => 'required|numeric',
            'total_shares' => 'required|integer',
            'base_share_price' => 'required|numeric',
            'info' => 'nullable|string',
        ]);
        $stock = Stock::first();
        if ($stock) {
            $stock->update($data);
        } else {
            Stock::create($data);
        }
        return redirect()->route('admin.stock.index')->with('success', 'اطلاعات سهام ذخیره شد');
    }
}
