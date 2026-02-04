@extends('layouts.unified')

@section('title', 'حساب‌های فرعی نجم بهار - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    .sub-accounts-container {
        direction: rtl;
    }
    
    .account-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    
    .sub-account-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .sub-account-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .balance-display {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-earth-green);
        margin: 1rem 0;
    }
    
    .sub-account-code {
        font-family: monospace;
        font-size: 1.1rem;
        color: var(--color-ocean-blue);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="bg-light-gray/60 py-8 md:py-10" style="background-color: var(--color-light-gray);">
    <div class="container mx-auto px-4 md:px-6 max-w-6xl">
        <div class="sub-accounts-container">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gentle-black mb-2">
                        <i class="fas fa-wallet ml-3" style="color: var(--color-earth-green);"></i>
                        حساب‌های فرعی
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">مدیریت حساب‌های فرعی خود</p>
                </div>
                <a href="{{ route('najm-bahar.sub-accounts.create') }}" 
                   class="px-6 py-3 bg-earth-green text-white rounded-lg hover:bg-opacity-90 transition-colors font-medium">
                    <i class="fas fa-plus ml-2"></i>
                    ایجاد حساب فرعی جدید
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

            <!-- حساب اصلی -->
            <div class="account-card">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gentle-black">
                        <i class="fas fa-credit-card ml-2" style="color: var(--color-earth-green);"></i>
                        حساب اصلی
                    </h2>
                    <a href="{{ route('najm-bahar.dashboard') }}" 
                       class="text-sm text-ocean-blue hover:underline">
                        مشاهده داشبورد
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-slate-600 mb-1">شماره حساب</p>
                        <p class="sub-account-code">{{ $account->account_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-slate-600 mb-1">موجودی</p>
                        <p class="balance-display">{{ number_format($account->balance) }} بهار</p>
                    </div>
                </div>
            </div>

            <!-- لیست حساب‌های فرعی -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gentle-black mb-4">
                    <i class="fas fa-list ml-2" style="color: var(--color-ocean-blue);"></i>
                    حساب‌های فرعی ({{ $subAccounts->count() }})
                </h2>
            </div>

            @if($subAccounts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($subAccounts as $subAccount)
                        <div class="sub-account-card">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-bold text-gentle-black">
                                    {{ $subAccount->name }}
                                </h3>
                                <a href="{{ route('najm-bahar.sub-accounts.show', $subAccount) }}" 
                                   class="text-ocean-blue hover:text-dark-blue">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-sm text-slate-600 mb-1">شماره حساب</p>
                                <p class="sub-account-code">{{ $subAccount->sub_account_code }}</p>
                            </div>
                            
                            <div class="mb-4">
                                <p class="text-sm text-slate-600 mb-1">موجودی</p>
                                <p class="text-2xl font-bold" style="color: var(--color-earth-green);">
                                    {{ number_format($subAccount->balance) }} بهار
                                </p>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('najm-bahar.sub-accounts.show', $subAccount) }}" 
                                   class="flex-1 text-center px-3 py-2 bg-ocean-blue text-white rounded-lg hover:bg-opacity-90 transition-colors text-sm">
                                    <i class="fas fa-eye ml-1"></i>
                                    مشاهده
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="account-card text-center py-12">
                    <i class="fas fa-inbox text-5xl text-slate-400 mb-4"></i>
                    <p class="text-lg text-slate-600 mb-2">هنوز حساب فرعی ایجاد نکرده‌اید</p>
                    <p class="text-sm text-slate-500 mb-4">حساب‌های فرعی به شما امکان مدیریت بهتر وجوه را می‌دهند</p>
                    <a href="{{ route('najm-bahar.sub-accounts.create') }}" 
                       class="inline-block px-6 py-3 bg-earth-green text-white rounded-lg hover:bg-opacity-90 transition-colors">
                        <i class="fas fa-plus ml-2"></i>
                        ایجاد اولین حساب فرعی
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

