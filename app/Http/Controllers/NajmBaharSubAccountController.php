<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\NajmBahar\Models\Account;
use App\Modules\NajmBahar\Models\SubAccount;
use App\Modules\NajmBahar\Services\SubAccountService;
use App\Modules\NajmBahar\Services\AccountNumberService;
use Illuminate\Support\Facades\Auth;

class NajmBaharSubAccountController extends Controller
{
    protected $subAccountService;

    public function __construct(SubAccountService $subAccountService)
    {
        $this->subAccountService = $subAccountService;
    }

    /**
     * نمایش لیست حساب‌های فرعی
     */
    public function index()
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            return redirect()->route('najm-bahar.dashboard')
                ->with('error', 'حساب اصلی شما یافت نشد. لطفاً ابتدا حساب خود را فعال کنید.');
        }

        $subAccounts = $this->subAccountService->getSubAccountsForAccount($account->id);

        return view('najm-bahar.sub-accounts.index', compact('account', 'subAccounts'));
    }

    /**
     * نمایش فرم ایجاد حساب فرعی
     */
    public function create()
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            return redirect()->route('najm-bahar.dashboard')
                ->with('error', 'حساب اصلی شما یافت نشد.');
        }

        return view('najm-bahar.sub-accounts.create', compact('account'));
    }

    /**
     * ذخیره حساب فرعی جدید
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account) {
            return redirect()->route('najm-bahar.dashboard')
                ->with('error', 'حساب اصلی شما یافت نشد.');
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
        ]);

        try {
            $subAccount = $this->subAccountService->createSubAccount($account->id, $validated['name'] ?? null);

            return redirect()->route('najm-bahar.sub-accounts.index')
                ->with('success', 'حساب فرعی با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در ایجاد حساب فرعی: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * نمایش جزئیات حساب فرعی
     */
    public function show(SubAccount $subAccount)
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account || $subAccount->account_id !== $account->id) {
            return redirect()->route('najm-bahar.sub-accounts.index')
                ->with('error', 'دسترسی غیرمجاز.');
        }

        return view('najm-bahar.sub-accounts.show', compact('account', 'subAccount'));
    }

    /**
     * انتقال وجه به حساب فرعی
     */
    public function transferTo(Request $request, SubAccount $subAccount)
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account || $subAccount->account_id !== $account->id) {
            return back()->with('error', 'دسترسی غیرمجاز.');
        }

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $this->subAccountService->transferToSubAccount(
                $account->id,
                $subAccount->id,
                $validated['amount'],
                $validated['description'] ?? null
            );

            return back()->with('success', 'وجه با موفقیت به حساب فرعی منتقل شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در انتقال وجه: ' . $e->getMessage());
        }
    }

    /**
     * انتقال وجه از حساب فرعی
     */
    public function transferFrom(Request $request, SubAccount $subAccount)
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account || $subAccount->account_id !== $account->id) {
            return back()->with('error', 'دسترسی غیرمجاز.');
        }

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'description' => 'nullable|string|max:500',
        ]);

        try {
            $this->subAccountService->transferFromSubAccount(
                $subAccount->id,
                $account->id,
                $validated['amount'],
                $validated['description'] ?? null
            );

            return back()->with('success', 'وجه با موفقیت از حساب فرعی منتقل شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در انتقال وجه: ' . $e->getMessage());
        }
    }

    /**
     * غیرفعال کردن حساب فرعی
     */
    public function deactivate(SubAccount $subAccount)
    {
        $user = Auth::user();
        $accountNumber = AccountNumberService::makeMainAccountNumberForUser($user->id);
        $account = Account::where('account_number', $accountNumber)->first();

        if (!$account || $subAccount->account_id !== $account->id) {
            return back()->with('error', 'دسترسی غیرمجاز.');
        }

        try {
            $this->subAccountService->deactivateSubAccount($subAccount->id);

            return redirect()->route('najm-bahar.sub-accounts.index')
                ->with('success', 'حساب فرعی با موفقیت غیرفعال شد.');
        } catch (\Exception $e) {
            return back()->with('error', 'خطا در غیرفعال کردن حساب فرعی: ' . $e->getMessage());
        }
    }
}

