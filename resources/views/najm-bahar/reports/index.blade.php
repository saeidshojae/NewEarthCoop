@extends('layouts.unified')

@section('title', 'گزارش‌های مالی نجم بهار - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    .reports-container {
        direction: rtl;
    }
    
    .summary-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    
    .summary-card-value {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .summary-card-label {
        font-size: 0.9rem;
        color: #64748b;
    }
    
    .filters-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    
    .transactions-table {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table thead {
        background-color: #f8fafc;
    }
    
    .table th {
        padding: 1rem;
        text-align: right;
        font-weight: 600;
        color: var(--color-gentle-black);
        border-bottom: 2px solid #e2e8f0;
    }
    
    .table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .transaction-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        border-radius: 999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .transaction-type-in {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--color-earth-green);
    }
    
    .transaction-type-out {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--color-red-tomato);
    }
    
    .export-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
</style>
@endpush

@section('content')
<div class="bg-light-gray/60 py-8 md:py-10" style="background-color: var(--color-light-gray);">
    <div class="container mx-auto px-4 md:px-6 max-w-7xl">
        <div class="reports-container">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gentle-black mb-2">
                        <i class="fas fa-chart-bar ml-3" style="color: var(--color-earth-green);"></i>
                        گزارش‌های مالی
                    </h1>
                    <p class="text-slate-600 dark:text-slate-400">مشاهده و تحلیل تراکنش‌های مالی</p>
                </div>
                <div class="export-buttons">
                    <a href="{{ route('najm-bahar.reports.export-pdf', request()->all()) }}" 
                       class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-file-pdf ml-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('najm-bahar.reports.export-excel', request()->all()) }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-file-excel ml-2"></i>
                        Export Excel
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="summary-card">
                    <div class="summary-card-value" style="color: var(--color-earth-green);">
                        {{ number_format($summary['totalIn']) }}
                    </div>
                    <div class="summary-card-label">مجموع ورودی</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-card-value" style="color: var(--color-red-tomato);">
                        {{ number_format($summary['totalOut']) }}
                    </div>
                    <div class="summary-card-label">مجموع خروجی</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-card-value" style="color: var(--color-ocean-blue);">
                        {{ number_format($summary['net']) }}
                    </div>
                    <div class="summary-card-label">خالص</div>
                </div>
                
                <div class="summary-card">
                    <div class="summary-card-value" style="color: var(--color-digital-gold);">
                        {{ number_format($summary['count']) }}
                    </div>
                    <div class="summary-card-label">تعداد تراکنش‌ها</div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-card">
                <form method="GET" action="{{ route('najm-bahar.reports') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">از تاریخ</label>
                        <input type="date" 
                               name="date_from" 
                               value="{{ $dateFrom }}"
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent dark:bg-slate-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">تا تاریخ</label>
                        <input type="date" 
                               name="date_to" 
                               value="{{ $dateTo }}"
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent dark:bg-slate-700 dark:text-white">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">نوع تراکنش</label>
                        <select name="type" 
                                class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent dark:bg-slate-700 dark:text-white">
                            <option value="all" {{ $type == 'all' ? 'selected' : '' }}>همه</option>
                            <option value="in" {{ $type == 'in' ? 'selected' : '' }}>ورودی</option>
                            <option value="out" {{ $type == 'out' ? 'selected' : '' }}>خروجی</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">جستجو</label>
                        <input type="text" 
                               name="search" 
                               value="{{ $search }}"
                               placeholder="جستجو در توضیحات..."
                               class="w-full px-4 py-2 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent dark:bg-slate-700 dark:text-white">
                    </div>
                    
                    <div class="md:col-span-4 flex items-center gap-4">
                        <button type="submit" 
                                class="px-6 py-2 bg-earth-green text-white rounded-lg hover:bg-opacity-90 transition-colors">
                            <i class="fas fa-search ml-2"></i>
                            اعمال فیلتر
                        </button>
                        <a href="{{ route('najm-bahar.reports') }}" 
                           class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            <i class="fas fa-redo ml-2"></i>
                            پاک کردن فیلترها
                        </a>
                    </div>
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="transactions-table">
                <h3 class="text-xl font-bold text-gentle-black mb-4">
                    <i class="fas fa-list ml-2" style="color: var(--color-earth-green);"></i>
                    لیست تراکنش‌ها
                </h3>
                
                @if($transactions->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>تاریخ</th>
                                    <th>نوع</th>
                                    <th>مبلغ</th>
                                    <th>توضیحات</th>
                                    <th>وضعیت</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    @php
                                        $isIncoming = isset($transaction->to_account_id) && $account && $transaction->to_account_id == $account->id;
                                        if (!$account) {
                                            $spring = \App\Models\Spring::where('user_id', auth()->id())->first();
                                            $isIncoming = $spring && isset($transaction->to_account_id) && $transaction->to_account_id == $spring->id;
                                        }
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($transaction->created_at)->format('Y/m/d H:i') }}
                                        </td>
                                        <td>
                                            <span class="transaction-type-badge {{ $isIncoming ? 'transaction-type-in' : 'transaction-type-out' }}">
                                                <i class="fas {{ $isIncoming ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
                                                {{ $isIncoming ? 'ورودی' : 'خروجی' }}
                                            </span>
                                        </td>
                                        <td class="font-bold {{ $isIncoming ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $isIncoming ? '+' : '-' }}{{ number_format($transaction->amount) }}
                                        </td>
                                        <td>{{ $transaction->description ?? 'تراکنش' }}</td>
                                        <td>
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                تکمیل شده
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $transactions->links() }}
                    </div>
                @else
                    <div class="text-center py-12 text-slate-500">
                        <i class="fas fa-inbox text-5xl mb-4 opacity-50"></i>
                        <p class="text-lg">هیچ تراکنشی یافت نشد</p>
                        <p class="text-sm mt-2">لطفاً فیلترها را تغییر دهید</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

