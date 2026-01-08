<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\Transaction;
use App\Modules\NajmBahar\Models\LedgerEntry;
use App\Modules\NajmBahar\Services\AccountNumberService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NajmBaharController extends Controller
{
    /**
     * نمایش Dashboard کاربری
     */
    public function dashboard()
    {
        $user = auth()->user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        
        // دریافت حساب NajmBahar
        $account = Account::where('account_number', $accountNumber)->first();
        
        // اگر حساب NajmBahar وجود ندارد، از Legacy Spring استفاده کن
        if (!$account) {
            $spring = \App\Models\Spring::where('user_id', $user->id)->first();
            if (!$spring || $spring->status == 0) {
                return redirect()->route('spring-accounts');
            }
        }

        // آمار کلی
        $stats = $this->getUserStats($account, $accountNumber);
        
        // تراکنش‌های اخیر
        $recentTransactions = $this->getRecentTransactions($account, $accountNumber);
        
        // داده‌های نمودار (30 روز گذشته)
        $chartData = $this->getChartData($account, $accountNumber);

        return view('najm-bahar.dashboard', compact('account', 'stats', 'recentTransactions', 'chartData'));
    }

    /**
     * دریافت آمار کاربر
     */
    private function getUserStats($account, $accountNumber)
    {
        $userId = auth()->id();
        
        // اگر حساب NajmBahar وجود دارد
        if ($account) {
            $balance = $account->balance;
            
            // تراکنش‌های 30 روز گذشته
            $transactions30Days = Transaction::where(function($query) use ($account) {
                $query->where('from_account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->where('status', 'completed')
            ->get();
            
            // محاسبه ورودی و خروجی
            $totalIn = 0;
            $totalOut = 0;
            
            foreach ($transactions30Days as $tx) {
                if ($tx->to_account_id == $account->id) {
                    $totalIn += $tx->amount;
                }
                if ($tx->from_account_id == $account->id) {
                    $totalOut += $tx->amount;
                }
            }
            
            // تعداد تراکنش‌ها
            $transactionCount = Transaction::where(function($query) use ($account) {
                $query->where('from_account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->where('status', 'completed')
            ->count();
            
            // تراکنش‌های در انتظار
            $pendingCount = Transaction::where(function($query) use ($account) {
                $query->where('from_account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->where('status', 'pending')
            ->count();
        } else {
            // استفاده از Legacy Spring
            $spring = \App\Models\Spring::where('user_id', $userId)->first();
            $balance = $spring ? $spring->amount : 0;
            
            $legacyTransactions = \App\Models\Transaction::where(function($query) use ($spring) {
                if ($spring) {
                    $query->where('from_account_id', $spring->id)
                          ->orWhere('to_account_id', $spring->id);
                }
            })
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get();
            
            $totalIn = 0;
            $totalOut = 0;
            
            foreach ($legacyTransactions as $tx) {
                if ($spring && $tx->to_account_id == $spring->id) {
                    $totalIn += $tx->amount;
                }
                if ($spring && $tx->from_account_id == $spring->id) {
                    $totalOut += $tx->amount;
                }
            }
            
            $transactionCount = $spring ? \App\Models\Transaction::where(function($query) use ($spring) {
                $query->where('from_account_id', $spring->id)
                      ->orWhere('to_account_id', $spring->id);
            })->count() : 0;
            
            $pendingCount = 0;
        }

        return [
            'balance' => $balance,
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'transactionCount' => $transactionCount,
            'pendingCount' => $pendingCount,
            'netAmount' => $totalIn - $totalOut,
        ];
    }

    /**
     * دریافت تراکنش‌های اخیر
     */
    private function getRecentTransactions($account, $accountNumber)
    {
        $userId = auth()->id();
        
        if ($account) {
            return Transaction::where(function($query) use ($account) {
                $query->where('from_account_id', $account->id)
                      ->orWhere('to_account_id', $account->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($tx) use ($account) {
                return [
                    'id' => $tx->id,
                    'type' => $tx->from_account_id == $account->id ? 'out' : 'in',
                    'amount' => $tx->amount,
                    'description' => $tx->description,
                    'status' => $tx->status,
                    'created_at' => $tx->created_at,
                ];
            });
        } else {
            // Legacy Spring
            $spring = \App\Models\Spring::where('user_id', $userId)->first();
            if (!$spring) {
                return collect();
            }
            
            return \App\Models\Transaction::where(function($query) use ($spring) {
                $query->where('from_account_id', $spring->id)
                      ->orWhere('to_account_id', $spring->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($tx) use ($spring) {
                return [
                    'id' => $tx->id,
                    'type' => $tx->from_account_id == $spring->id ? 'out' : 'in',
                    'amount' => $tx->amount,
                    'description' => $tx->description ?? 'تراکنش',
                    'status' => 'completed',
                    'created_at' => $tx->created_at,
                ];
            });
        }
    }

    /**
     * دریافت داده‌های نمودار (30 روز گذشته)
     */
    private function getChartData($account, $accountNumber)
    {
        $userId = auth()->id();
        $days = 30;
        $labels = [];
        $inData = [];
        $outData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('Y-m-d');
            
            if ($account) {
                $in = Transaction::where('to_account_id', $account->id)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->where('status', 'completed')
                    ->sum('amount');
                    
                $out = Transaction::where('from_account_id', $account->id)
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->where('status', 'completed')
                    ->sum('amount');
                    
                $inData[] = $in;
                $outData[] = $out;
            } else {
                // Legacy Spring
                $spring = \App\Models\Spring::where('user_id', $userId)->first();
                if ($spring) {
                    $in = \App\Models\Transaction::where('to_account_id', $spring->id)
                        ->whereDate('created_at', $date->format('Y-m-d'))
                        ->sum('amount');
                        
                    $out = \App\Models\Transaction::where('from_account_id', $spring->id)
                        ->whereDate('created_at', $date->format('Y-m-d'))
                        ->sum('amount');
                        
                    $inData[] = $in;
                    $outData[] = $out;
                } else {
                    $inData[] = 0;
                    $outData[] = 0;
                }
            }
        }
        
        return [
            'labels' => $labels,
            'in' => $inData,
            'out' => $outData,
        ];
    }
}

