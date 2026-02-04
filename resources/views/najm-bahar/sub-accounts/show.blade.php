@extends('layouts.unified')

@section('title', 'جزئیات حساب فرعی - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    .sub-account-detail-container {
        direction: rtl;
    }
    
    .detail-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    
    .balance-large {
        font-size: 3rem;
        font-weight: 800;
        color: var(--color-earth-green);
        margin: 1rem 0;
    }
</style>
@endpush

@section('content')
<div class="bg-light-gray/60 py-8 md:py-10" style="background-color: var(--color-light-gray);">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <div class="sub-account-detail-container">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gentle-black mb-2">
                        <i class="fas fa-wallet ml-3" style="color: var(--color-earth-green);"></i>
                        {{ $subAccount->name }}
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">جزئیات حساب فرعی</p>
                </div>
                <a href="{{ route('najm-bahar.sub-accounts.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-arrow-right ml-2"></i>
                    بازگشت
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 border-r-4 border-green-500 text-green-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle ml-3"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-r-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle ml-3"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                </div>
            @endif

            <!-- اطلاعات حساب -->
            <div class="detail-card">
                <h2 class="text-xl font-bold text-gentle-black mb-4">
                    <i class="fas fa-info-circle ml-2" style="color: var(--color-ocean-blue);"></i>
                    اطلاعات حساب
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">شماره حساب</p>
                        <p class="text-lg font-mono font-bold text-ocean-blue">{{ $subAccount->sub_account_code }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">نام حساب</p>
                        <p class="text-lg font-bold text-gentle-black">{{ $subAccount->name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-slate-600 mb-1">موجودی</p>
                        <p class="balance-large">{{ number_format($subAccount->balance) }} بهار</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">تاریخ ایجاد</p>
                        <p class="text-base text-gentle-black">{{ \Morilog\Jalali\Jalalian::fromCarbon($subAccount->created_at)->format('Y/m/d H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">وضعیت</p>
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            فعال
                        </span>
                    </div>
                </div>
            </div>

            <!-- انتقال وجه -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- انتقال به حساب فرعی -->
                <div class="detail-card">
                    <h3 class="text-lg font-bold text-gentle-black mb-4">
                        <i class="fas fa-arrow-down ml-2" style="color: var(--color-earth-green);"></i>
                        انتقال به حساب فرعی
                    </h3>
                    <p class="text-sm text-slate-600 mb-4">
                        موجودی حساب اصلی: <strong>{{ number_format($account->balance) }} بهار</strong>
                    </p>
                    <form action="{{ route('najm-bahar.sub-accounts.transfer-to', $subAccount) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="amount_to" class="block text-sm font-semibold text-slate-700 mb-2">
                                مبلغ (بهار)
                            </label>
                            <input type="number" 
                                   id="amount_to" 
                                   name="amount" 
                                   required
                                   min="1"
                                   max="{{ $account->balance }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent"
                                   placeholder="مبلغ را وارد کنید">
                        </div>
                        <div class="mb-4">
                            <label for="description_to" class="block text-sm font-semibold text-slate-700 mb-2">
                                توضیحات (اختیاری)
                            </label>
                            <textarea id="description_to" 
                                      name="description" 
                                      rows="2"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent"
                                      placeholder="توضیحات تراکنش"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-earth-green text-white rounded-lg hover:bg-opacity-90 transition-colors font-medium">
                            <i class="fas fa-arrow-down ml-2"></i>
                            انتقال به حساب فرعی
                        </button>
                    </form>
                </div>

                <!-- انتقال از حساب فرعی -->
                <div class="detail-card">
                    <h3 class="text-lg font-bold text-gentle-black mb-4">
                        <i class="fas fa-arrow-up ml-2" style="color: var(--color-red-tomato);"></i>
                        انتقال از حساب فرعی
                    </h3>
                    <p class="text-sm text-slate-600 mb-4">
                        موجودی حساب فرعی: <strong>{{ number_format($subAccount->balance) }} بهار</strong>
                    </p>
                    <form action="{{ route('najm-bahar.sub-accounts.transfer-from', $subAccount) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="amount_from" class="block text-sm font-semibold text-slate-700 mb-2">
                                مبلغ (بهار)
                            </label>
                            <input type="number" 
                                   id="amount_from" 
                                   name="amount" 
                                   required
                                   min="1"
                                   max="{{ $subAccount->balance }}"
                                   class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-tomato focus:border-transparent"
                                   placeholder="مبلغ را وارد کنید">
                        </div>
                        <div class="mb-4">
                            <label for="description_from" class="block text-sm font-semibold text-slate-700 mb-2">
                                توضیحات (اختیاری)
                            </label>
                            <textarea id="description_from" 
                                      name="description" 
                                      rows="2"
                                      class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-red-tomato focus:border-transparent"
                                      placeholder="توضیحات تراکنش"></textarea>
                        </div>
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-tomato text-white rounded-lg hover:bg-opacity-90 transition-colors font-medium">
                            <i class="fas fa-arrow-up ml-2"></i>
                            انتقال از حساب فرعی
                        </button>
                    </form>
                </div>
            </div>

            <!-- عملیات -->
            <div class="detail-card">
                <h3 class="text-lg font-bold text-gentle-black mb-4">
                    <i class="fas fa-cog ml-2" style="color: var(--color-digital-gold);"></i>
                    عملیات
                </h3>
                <form action="{{ route('najm-bahar.sub-accounts.deactivate', $subAccount) }}" 
                      method="POST" 
                      onsubmit="return confirm('آیا از غیرفعال کردن این حساب فرعی اطمینان دارید؟ توجه: حساب باید موجودی صفر داشته باشد.');">
                    @csrf
                    <button type="submit" 
                            class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
                            {{ $subAccount->balance > 0 ? 'disabled' : '' }}>
                        <i class="fas fa-ban ml-2"></i>
                        غیرفعال کردن حساب فرعی
                    </button>
                    @if($subAccount->balance > 0)
                        <p class="mt-2 text-sm text-red-600">
                            برای غیرفعال کردن حساب، ابتدا باید موجودی آن را به حساب اصلی منتقل کنید.
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

