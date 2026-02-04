@extends('layouts.unified')

@section('title', 'داشبورد حساب نجم بهار - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    .dashboard-container {
        direction: rtl;
    }
    
    .stat-card {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    
    .stat-card-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-card-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-gentle-black);
        margin-bottom: 0.5rem;
    }
    
    .stat-card-label {
        font-size: 0.9rem;
        color: #64748b;
    }
    
    .balance-card {
        background: linear-gradient(135deg, var(--color-earth-green) 0%, #047857 100%);
        color: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 8px 24px rgba(5, 150, 105, 0.25);
    }
    
    .balance-card .balance-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
    }
    
    .balance-card .balance-value {
        font-size: 3rem;
        font-weight: 900;
        margin-bottom: 1rem;
    }
    
    .chart-container {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        height: 350px;
    }
    
    .transactions-table {
        background: var(--color-pure-white);
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    
    .transaction-item {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.2s ease;
    }
    
    .transaction-item:hover {
        background-color: #f8fafc;
    }
    
    .transaction-item:last-child {
        border-bottom: none;
    }
    
    .transaction-type-in {
        color: var(--color-earth-green);
    }
    
    .transaction-type-out {
        color: var(--color-red-tomato);
    }
    
    .transaction-amount {
        font-size: 1.25rem;
        font-weight: 700;
    }
    
    @media (max-width: 768px) {
        .stat-card-value {
            font-size: 1.5rem;
        }
        
        .balance-card .balance-value {
            font-size: 2rem;
        }
        
        .chart-container {
            height: 250px;
        }
    }
</style>
@endpush

@section('content')
<div class="bg-light-gray/60 py-8 md:py-10" style="background-color: var(--color-light-gray);">
    <div class="container mx-auto px-4 md:px-6 max-w-7xl">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gentle-black mb-2">
                    <i class="fas fa-wallet ml-3" style="color: var(--color-earth-green);"></i>
                    داشبورد حساب نجم بهار
                </h1>
                <p class="text-slate-600 dark:text-slate-400">مدیریت و مشاهده وضعیت حساب مالی شما</p>
            </div>

            <!-- Balance Card -->
            <div class="balance-card mb-6">
                <div class="balance-label">موجودی فعلی</div>
                <div class="balance-value">{{ number_format($stats['balance'] ?? 0) }}</div>
                <div class="text-sm opacity-90">بهار</div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total In -->
                <div class="stat-card">
                    <div class="stat-card-icon" style="background-color: rgba(16, 185, 129, 0.1); color: var(--color-earth-green);">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-card-value">{{ number_format($stats['totalIn'] ?? 0) }}</div>
                    <div class="stat-card-label">مجموع ورودی (30 روز گذشته)</div>
                </div>

                <!-- Total Out -->
                <div class="stat-card">
                    <div class="stat-card-icon" style="background-color: rgba(239, 68, 68, 0.1); color: var(--color-red-tomato);">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-card-value">{{ number_format($stats['totalOut'] ?? 0) }}</div>
                    <div class="stat-card-label">مجموع خروجی (30 روز گذشته)</div>
                </div>

                <!-- Transaction Count -->
                <div class="stat-card">
                    <div class="stat-card-icon" style="background-color: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <div class="stat-card-value">{{ number_format($stats['transactionCount'] ?? 0) }}</div>
                    <div class="stat-card-label">تعداد تراکنش‌ها</div>
                </div>

                <!-- Pending -->
                <div class="stat-card">
                    <div class="stat-card-icon" style="background-color: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-card-value">{{ number_format($stats['pendingCount'] ?? 0) }}</div>
                    <div class="stat-card-label">تراکنش‌های در انتظار</div>
                </div>
            </div>

            <!-- Chart and Transactions Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chart -->
                <div class="chart-container">
                    <h3 class="text-xl font-bold text-gentle-black mb-4">
                        <i class="fas fa-chart-line ml-2" style="color: var(--color-earth-green);"></i>
                        نمودار تراکنش‌ها (30 روز گذشته)
                    </h3>
                    <canvas id="transactionsChart"></canvas>
                </div>

                <!-- Recent Transactions -->
                <div class="transactions-table">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gentle-black">
                            <i class="fas fa-list ml-2" style="color: var(--color-earth-green);"></i>
                            آخرین تراکنش‌ها
                        </h3>
                        <a href="{{ route('spring-accounts') }}" class="text-sm text-blue-600 hover:text-blue-700">
                            مشاهده همه
                            <i class="fas fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                    
                    @if($recentTransactions && $recentTransactions->count() > 0)
                        <div class="space-y-2">
                            @foreach($recentTransactions as $transaction)
                                <div class="transaction-item">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="fas {{ $transaction['type'] == 'in' ? 'fa-arrow-down text-green-600' : 'fa-arrow-up text-red-600' }}"></i>
                                                <span class="text-sm text-slate-600">{{ $transaction['description'] ?? 'تراکنش' }}</span>
                                            </div>
                                            <div class="text-xs text-slate-500">
                                                {{ \Morilog\Jalali\Jalalian::fromCarbon($transaction['created_at'])->format('Y/m/d H:i') }}
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <div class="transaction-amount {{ $transaction['type'] == 'in' ? 'transaction-type-in' : 'transaction-type-out' }}">
                                                {{ $transaction['type'] == 'in' ? '+' : '-' }}{{ number_format($transaction['amount']) }}
                                            </div>
                                            <div class="text-xs text-slate-500">بهار</div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                            <p>هنوز تراکنشی ثبت نشده است</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                <h3 class="text-xl font-bold text-gentle-black mb-4">
                    <i class="fas fa-bolt ml-2" style="color: var(--color-earth-green);"></i>
                    دسترسی سریع
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('najm-bahar.sub-accounts.index') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-wallet text-2xl mb-2" style="color: var(--color-earth-green);"></i>
                        <span class="text-sm font-medium">حساب‌های فرعی</span>
                    </a>
                    <a href="{{ route('najm-bahar.reports') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-file-invoice text-2xl mb-2" style="color: var(--color-digital-gold);"></i>
                        <span class="text-sm font-medium">گزارش‌ها</span>
                    </a>
                    <a href="{{ route('notifications.settings') }}" class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-bell text-2xl mb-2" style="color: #8b5cf6;"></i>
                        <span class="text-sm font-medium">تنظیمات اعلان‌ها</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('transactionsChart');
    if (!ctx) return;
    
    const chartData = @json($chartData);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'ورودی',
                    data: chartData.in,
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'خروجی',
                    data: chartData.out,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        font: {
                            family: 'Vazirmatn',
                            size: 12
                        }
                    }
                },
                tooltip: {
                    rtl: true,
                    titleFont: {
                        family: 'Vazirmatn'
                    },
                    bodyFont: {
                        family: 'Vazirmatn'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            family: 'Vazirmatn'
                        },
                        callback: function(value) {
                            return value.toLocaleString('fa-IR');
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            family: 'Vazirmatn'
                        },
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
});
</script>
@endpush

