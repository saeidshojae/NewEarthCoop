<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\Transaction;
use App\Modules\NajmBahar\Models\SubAccount;
use App\Modules\NajmBahar\Models\Fee;
use App\Modules\NajmBahar\Models\ScheduledTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NajmBaharDashboardController extends Controller
{
    /**
     * نمایش Dashboard ادمین
     */
    public function index()
    {
        // آمار کلی
        $stats = [
            'total_accounts' => Account::where('type', 'user')->count(),
            'total_transactions' => Transaction::where('status', 'completed')->count(),
            'total_balance' => Account::where('type', 'user')->sum('balance'),
            'total_sub_accounts' => SubAccount::where('status', 1)->count(),
            'active_fees' => Fee::where('is_active', true)->count(),
            'pending_scheduled' => ScheduledTransaction::where('status', 'scheduled')->count(),
        ];

        // تراکنش‌های امروز
        $todayTransactions = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();
        
        $todayVolume = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('amount');

        // تراکنش‌های هفته گذشته
        $weekTransactions = Transaction::where('created_at', '>=', Carbon::now()->subWeek())
            ->where('status', 'completed')
            ->count();
        
        $weekVolume = Transaction::where('created_at', '>=', Carbon::now()->subWeek())
            ->where('status', 'completed')
            ->sum('amount');

        // نمودار تراکنش‌های 30 روز گذشته
        $dailyTransactions = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as volume')
            )
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // تراکنش‌های اخیر
        $recentTransactions = Transaction::with(['fromAccount', 'toAccount'])
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // توزیع نوع تراکنش‌ها
        $transactionTypes = Transaction::select(
                'type',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as volume')
            )
            ->where('status', 'completed')
            ->groupBy('type')
            ->get();

        // حساب‌های با بیشترین موجودی
        $topAccounts = Account::where('type', 'user')
            ->orderBy('balance', 'desc')
            ->limit(10)
            ->get();

        return view('admin.najm-bahar.dashboard', compact(
            'stats',
            'todayTransactions',
            'todayVolume',
            'weekTransactions',
            'weekVolume',
            'dailyTransactions',
            'recentTransactions',
            'transactionTypes',
            'topAccounts'
        ));
    }
}

