<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\Transaction;
use App\Modules\NajmBahar\Services\AccountNumberService;
use App\Exports\NajmBaharTransactionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class NajmBaharReportController extends Controller
{
    /**
     * نمایش صفحه گزارش‌های مالی
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        // اگر حساب NajmBahar وجود ندارد، از Legacy Spring استفاده کن
        if (!$account) {
            $spring = \App\Models\Spring::where('user_id', $user->id)->first();
            if (!$spring || $spring->status == 0) {
                return redirect()->route('spring-accounts');
            }
        }

        // فیلترها
        $dateFrom = $request->input('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $type = $request->input('type', 'all'); // all, in, out
        $search = $request->input('search');

        // دریافت تراکنش‌ها
        $transactions = $this->getTransactions($account, $accountNumber, $dateFrom, $dateTo, $type, $search);

        // آمار خلاصه
        $summary = $this->getSummary($account, $accountNumber, $dateFrom, $dateTo);

        return view('najm-bahar.reports.index', compact('transactions', 'summary', 'dateFrom', 'dateTo', 'type', 'search'));
    }

    /**
     * دریافت تراکنش‌ها با فیلتر
     */
    private function getTransactions($account, $accountNumber, $dateFrom, $dateTo, $type, $search)
    {
        if ($account) {
            $query = Transaction::where(function($q) use ($account) {
                $q->where('from_account_id', $account->id)
                  ->orWhere('to_account_id', $account->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->where('status', 'completed');

            // فیلتر نوع
            if ($type === 'in') {
                $query->where('to_account_id', $account->id);
            } elseif ($type === 'out') {
                $query->where('from_account_id', $account->id);
            }

            // جستجو در توضیحات
            if ($search) {
                $query->where('description', 'like', "%{$search}%");
            }

            return $query->orderBy('created_at', 'desc')->paginate(25);
        } else {
            // Legacy Spring
            $spring = \App\Models\Spring::where('user_id', auth()->id())->first();
            if (!$spring) {
                return collect()->paginate(25);
            }

            $query = \App\Models\Transaction::where(function($q) use ($spring) {
                $q->where('from_account_id', $spring->id)
                  ->orWhere('to_account_id', $spring->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);

            if ($type === 'in') {
                $query->where('to_account_id', $spring->id);
            } elseif ($type === 'out') {
                $query->where('from_account_id', $spring->id);
            }

            if ($search) {
                $query->where('description', 'like', "%{$search}%");
            }

            return $query->orderBy('created_at', 'desc')->paginate(25);
        }
    }

    /**
     * دریافت آمار خلاصه
     */
    private function getSummary($account, $accountNumber, $dateFrom, $dateTo)
    {
        if ($account) {
            $transactions = Transaction::where(function($q) use ($account) {
                $q->where('from_account_id', $account->id)
                  ->orWhere('to_account_id', $account->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->where('status', 'completed')
            ->get();

            $totalIn = $transactions->where('to_account_id', $account->id)->sum('amount');
            $totalOut = $transactions->where('from_account_id', $account->id)->sum('amount');
            $count = $transactions->count();
        } else {
            // Legacy Spring
            $spring = \App\Models\Spring::where('user_id', auth()->id())->first();
            if (!$spring) {
                return [
                    'totalIn' => 0,
                    'totalOut' => 0,
                    'net' => 0,
                    'count' => 0,
                ];
            }

            $transactions = \App\Models\Transaction::where(function($q) use ($spring) {
                $q->where('from_account_id', $spring->id)
                  ->orWhere('to_account_id', $spring->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->get();

            $totalIn = $transactions->where('to_account_id', $spring->id)->sum('amount');
            $totalOut = $transactions->where('from_account_id', $spring->id)->sum('amount');
            $count = $transactions->count();
        }

        return [
            'totalIn' => $totalIn,
            'totalOut' => $totalOut,
            'net' => $totalIn - $totalOut,
            'count' => $count,
        ];
    }

    /**
     * Export به Excel
     */
    public function exportExcel(Request $request)
    {
        $user = auth()->user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            $spring = \App\Models\Spring::where('user_id', $user->id)->first();
            if (!$spring || $spring->status == 0) {
                return redirect()->route('spring-accounts');
            }
        }

        // دریافت فیلترها
        $dateFrom = $request->input('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $type = $request->input('type', 'all');
        $search = $request->input('search');

        // دریافت تمام تراکنش‌ها (بدون pagination)
        $transactions = $this->getTransactionsForExport($account, $accountNumber, $dateFrom, $dateTo, $type, $search);

        $fileName = 'najm-bahar-transactions-' . Carbon::now()->format('Y-m-d-His') . '.xlsx';

        return Excel::download(new NajmBaharTransactionsExport($transactions, $account), $fileName);
    }

    /**
     * دریافت تراکنش‌ها برای Export (بدون pagination)
     */
    private function getTransactionsForExport($account, $accountNumber, $dateFrom, $dateTo, $type, $search)
    {
        if ($account) {
            $query = Transaction::where(function($q) use ($account) {
                $q->where('from_account_id', $account->id)
                  ->orWhere('to_account_id', $account->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ])
            ->where('status', 'completed');

            if ($type === 'in') {
                $query->where('to_account_id', $account->id);
            } elseif ($type === 'out') {
                $query->where('from_account_id', $account->id);
            }

            if ($search) {
                $query->where('description', 'like', "%{$search}%");
            }

            return $query->orderBy('created_at', 'desc')->get();
        } else {
            $spring = \App\Models\Spring::where('user_id', auth()->id())->first();
            if (!$spring) {
                return collect();
            }

            $query = \App\Models\Transaction::where(function($q) use ($spring) {
                $q->where('from_account_id', $spring->id)
                  ->orWhere('to_account_id', $spring->id);
            })
            ->whereBetween('created_at', [
                Carbon::parse($dateFrom)->startOfDay(),
                Carbon::parse($dateTo)->endOfDay()
            ]);

            if ($type === 'in') {
                $query->where('to_account_id', $spring->id);
            } elseif ($type === 'out') {
                $query->where('from_account_id', $spring->id);
            }

            if ($search) {
                $query->where('description', 'like', "%{$search}%");
            }

            return $query->orderBy('created_at', 'desc')->get();
        }
    }

    /**
     * Export به PDF
     */
    public function exportPdf(Request $request)
    {
        $user = auth()->user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            $spring = \App\Models\Spring::where('user_id', $user->id)->first();
            if (!$spring || $spring->status == 0) {
                return redirect()->route('spring-accounts');
            }
        }

        // دریافت فیلترها
        $dateFrom = $request->input('date_from', Carbon::now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));
        $type = $request->input('type', 'all');
        $search = $request->input('search');

        // دریافت تراکنش‌ها
        $transactions = $this->getTransactionsForExport($account, $accountNumber, $dateFrom, $dateTo, $type, $search);
        $summary = $this->getSummary($account, $accountNumber, $dateFrom, $dateTo);

        // استفاده از view برای PDF
        $html = view('najm-bahar.reports.pdf', compact('transactions', 'summary', 'dateFrom', 'dateTo', 'user'))->render();

        // استفاده از DomPDF (اگر نصب باشد) یا بازگشت HTML
        // برای حال حاضر، HTML را برمی‌گردانیم
        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="najm-bahar-report-' . Carbon::now()->format('Y-m-d') . '.html"');
    }
}

