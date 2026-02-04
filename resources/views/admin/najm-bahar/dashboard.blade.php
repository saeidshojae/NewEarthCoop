@extends('layouts.admin')

@section('title', 'داشبورد نجم بهار - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    .stats-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        padding: 1.5rem;
        transition: all 0.3s ease;
        border-top: 4px solid;
        height: 100%;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .stats-card.primary { border-top-color: #3b82f6; }
    .stats-card.success { border-top-color: #10b981; }
    .stats-card.warning { border-top-color: #f59e0b; }
    .stats-card.info { border-top-color: #06b6d4; }
    .stats-card.purple { border-top-color: #8b5cf6; }
    .stats-card.pink { border-top-color: #ec4899; }
    
    .stats-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    
    .stats-value {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        font-size: 0.875rem;
        color: #64748b;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-6" dir="rtl">
    <!-- هدر صفحه -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">
            <i class="fas fa-chart-line ml-2"></i>
            داشبورد نجم بهار
        </h1>
        <p class="text-slate-600 dark:text-slate-400 mt-1">نمای کلی سیستم مالی نجم بهار</p>
    </div>

    <!-- کارت‌های آماری -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="stats-card primary">
            <div class="stats-icon text-blue-600">
                <i class="fas fa-users"></i>
            </div>
            <div class="stats-value text-blue-600">{{ number_format($stats['total_accounts']) }}</div>
            <div class="stats-label">کل حساب‌های کاربری</div>
        </div>

        <div class="stats-card success">
            <div class="stats-icon text-green-600">
                <i class="fas fa-exchange-alt"></i>
            </div>
            <div class="stats-value text-green-600">{{ number_format($stats['total_transactions']) }}</div>
            <div class="stats-label">کل تراکنش‌ها</div>
        </div>

        <div class="stats-card warning">
            <div class="stats-icon text-amber-600">
                <i class="fas fa-coins"></i>
            </div>
            <div class="stats-value text-amber-600">{{ number_format($stats['total_balance']) }}</div>
            <div class="stats-label">موجودی کل (بهار)</div>
        </div>

        <div class="stats-card info">
            <div class="stats-icon text-cyan-600">
                <i class="fas fa-wallet"></i>
            </div>
            <div class="stats-value text-cyan-600">{{ number_format($stats['total_sub_accounts']) }}</div>
            <div class="stats-label">حساب‌های فرعی فعال</div>
        </div>

        <div class="stats-card purple">
            <div class="stats-icon text-purple-600">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stats-value text-purple-600">{{ number_format($stats['active_fees']) }}</div>
            <div class="stats-label">کارمزدهای فعال</div>
        </div>

        <div class="stats-card pink">
            <div class="stats-icon text-pink-600">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-value text-pink-600">{{ number_format($stats['pending_scheduled']) }}</div>
            <div class="stats-label">تراکنش‌های زمان‌بندی شده</div>
        </div>
    </div>

    <!-- آمار امروز و هفته -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
                <i class="fas fa-calendar-day ml-2"></i>
                آمار امروز
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">تعداد تراکنش‌ها:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($todayTransactions) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">حجم تراکنش‌ها:</span>
                    <span class="font-bold text-green-600">{{ number_format($todayVolume) }} بهار</span>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
                <i class="fas fa-calendar-week ml-2"></i>
                آمار هفته گذشته
            </h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">تعداد تراکنش‌ها:</span>
                    <span class="font-bold text-slate-900 dark:text-white">{{ number_format($weekTransactions) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600 dark:text-slate-400">حجم تراکنش‌ها:</span>
                    <span class="font-bold text-green-600">{{ number_format($weekVolume) }} بهار</span>
                </div>
            </div>
        </div>
    </div>

    <!-- نمودار تراکنش‌های 30 روز گذشته -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 mb-8">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
            <i class="fas fa-chart-area ml-2"></i>
            نمودار تراکنش‌های 30 روز گذشته
        </h3>
        <canvas id="transactionsChart" height="100"></canvas>
    </div>

    <!-- تراکنش‌های اخیر و حساب‌های برتر -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- تراکنش‌های اخیر -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
                <i class="fas fa-history ml-2"></i>
                تراکنش‌های اخیر
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-700">
                        <tr>
                            <th class="px-4 py-2 text-right text-slate-700 dark:text-slate-300">مبلغ</th>
                            <th class="px-4 py-2 text-right text-slate-700 dark:text-slate-300">نوع</th>
                            <th class="px-4 py-2 text-right text-slate-700 dark:text-slate-300">تاریخ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse($recentTransactions as $transaction)
                            <tr>
                                <td class="px-4 py-2 font-bold">{{ number_format($transaction->amount) }} بهار</td>
                                <td class="px-4 py-2">
                                    <span class="px-2 py-1 rounded-full text-xs {{ $transaction->type === 'immediate' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $transaction->type === 'immediate' ? 'فوری' : $transaction->type }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-slate-600 dark:text-slate-400">
                                    {{ \Morilog\Jalali\Jalalian::fromCarbon($transaction->created_at)->format('Y/m/d H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-8 text-center text-slate-500">هیچ تراکنشی یافت نشد</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- حساب‌های برتر -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
                <i class="fas fa-trophy ml-2"></i>
                حساب‌های با بیشترین موجودی
            </h3>
            <div class="space-y-3">
                @forelse($topAccounts as $index => $account)
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-700 rounded-lg">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 flex items-center justify-center font-bold">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $account->account_number }}</p>
                                @if($account->user_id)
                                    <p class="text-xs text-slate-500">کاربر #{{ $account->user_id }}</p>
                                @endif
                            </div>
                        </div>
                        <span class="font-bold text-green-600">{{ number_format($account->balance) }} بهار</span>
                    </div>
                @empty
                    <p class="text-center text-slate-500 py-8">هیچ حسابی یافت نشد</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- توزیع نوع تراکنش‌ها -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4">
            <i class="fas fa-pie-chart ml-2"></i>
            توزیع نوع تراکنش‌ها
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($transactionTypes as $type)
                <div class="p-4 bg-slate-50 dark:bg-slate-700 rounded-lg">
                    <p class="text-sm text-slate-600 dark:text-slate-400 mb-1">
                        @if($type->type === 'immediate')
                            فوری
                        @elseif($type->type === 'scheduled')
                            زمان‌بندی شده
                        @else
                            {{ $type->type }}
                        @endif
                    </p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($type->count) }}</p>
                    <p class="text-xs text-slate-500 mt-1">حجم: {{ number_format($type->volume) }} بهار</p>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // نمودار تراکنش‌های 30 روز گذشته
    const ctx = document.getElementById('transactionsChart').getContext('2d');
    const dailyData = @json($dailyTransactions);
    
    const labels = dailyData.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('fa-IR', { month: 'short', day: 'numeric' });
    });
    
    const counts = dailyData.map(item => item.count);
    const volumes = dailyData.map(item => item.volume);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'تعداد تراکنش‌ها',
                    data: counts,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y',
                },
                {
                    label: 'حجم تراکنش‌ها (بهار)',
                    data: volumes,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'تعداد'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'حجم (بهار)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                }
            }
        }
    });
</script>
@endpush
@endsection

