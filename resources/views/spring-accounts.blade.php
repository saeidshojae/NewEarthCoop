@extends('layouts.app')

@section('title', 'حساب نجم بهار')

@section('head-tag')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>

<link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
<style>
    .text-gray-400 { color: var(--gray-400) !important; }
    .text-gray-600 { color: var(--gray-600) !important; }
    
    .hover-shadow {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .fade-in {
        opacity: 0;
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')

@php
 $userCount = \App\Models\User::all()->count() - 1;
@endphp

<div class="container py-4" style='    direction: rtl;'>


        <div class="alert alert-warning mb-4 shadow-sm border-0 fade-in">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-warning bg-opacity-10 me-3">
                    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                </div>
                <div>
                    <h5 class="alert-heading mb-1">تراکنش‌ها تا زمان رسیدن کاربران به 11,111,111 نفر غیرفعال هستند</h5>
                    <p class="mb-0 text-gray-600">
                       
                    </p>
                </div>
            </div>
        </div>

    <!-- کارت‌های آماری -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow fade-in">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-info bg-opacity-10 me-3">
                                <i class="bi bi-cash-stack fs-4 text-info"></i>
                            </div>
                            <h6 class="card-title mb-0 text-gray-600">گردش مالی کل</h6>
                        </div>
                    </div>
                    <div class="stat-value" style='font-weight: 900; font-size: 2rem;color: #459f96;'>
                        <p>{{ number_format(\App\Models\Spring::where('status', 1)->get()->sum('amount')) . ' بهار ' }}</p>
                    </div>
                    <p class="stat-description">مجموع کل گردش مالی موفق در سیستم</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow fade-in">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle bg-primary bg-opacity-10 me-3">
                                <i class="bi bi-wallet fs-4 text-primary"></i>
                            </div>
                            <h6 class="card-title mb-0 text-gray-600">کل بهار خلق شده</h6>
                        </div>
                    </div>
                    <div class="stat-value" style='font-weight: 900; font-size: 2rem;color: #459f96;'>
                        <p>{{ number_format($userCount * 10000) }}</p>
                    </div>
                    <p class="stat-description">مجموع کل بهار خلق شده در سیستم مالی ({{ number_format($userCount) }} کاربر × 10000 بهار)</p>
                </div>
            </div>
        </div>
      

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow fade-in" style="animation-delay: 0.2s">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle {{ 1 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }} me-3">
                                <i class="bi bi-toggle2-{{ 1 ? 'on' : 'off' }} fs-4 {{ 1 ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <h6 class="card-title mb-0 text-gray-600">وضعیت تراکنش‌ها</h6>
                        </div>
                    </div>
                    <div class="stat-value text-danger" style='font-weight: 900; font-size: 2rem;color: #459f96;'>
                        غیرفعال
                    </div>
                    <p class="stat-description">
                        تراکنش ها تا رسیدن تعداد کاربران ارث کوپ به 11,111,111 کاربر غیرفعال است
                    </p>
                </div>
            </div>
        </div>
        
                <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-shadow fade-in" style="animation-delay: 0.2s">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <div class="icon-circle {{ 1 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }} me-3">
                                <i class="bi bi-toggle2-{{ 1 ? 'on' : 'off' }} fs-4 {{ 1 ? 'text-success' : 'text-danger' }}"></i>
                            </div>
                            <h6 class="card-title mb-0 text-gray-600">تعداد کاربران سیستم</h6>
                        </div>
                    </div>
                    <div class="stat-value" style='font-weight: 900; font-size: 2rem;color: #459f96;'>
                        {{ number_format($userCount) . ' کاربر ' }}
                    </div>
                    <p class="stat-description">
                        تا رسیدن به حد نصاب: {{ number_format(11111111 - $userCount) }}
                    </p>
                </div>
            </div>
        </div>
        
    </div>

    <!-- حساب‌های من -->
    <div class="card border-0 shadow-sm mb-4 fade-in" style="animation-delay: 0.3s">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-info bg-opacity-10 me-3">
                    <i class="bi bi-wallet2 fs-4 text-info"></i>
                </div>
                <h6 class="mb-0 text-gray-600">حساب‌های من</h6>
            </div>
            <a href="" class="btn btn-primary btn-sm disabled" style='width: auto; background-color: #37c4b4'>
                <i class="bi bi-plus-lg me-1"></i>
                ایجاد حساب جدید
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">شماره حساب</th>
                            <th class="border-0">نوع حساب</th>
                            <th class="border-0">موجودی</th>
                            <th class="border-0">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Spring::where('user_id', auth()->user()->id)->get() as $account)
                        <tr>
                                <td class="align-middle">{{ $account->cart_number }}</td>
                                <td class="align-middle">
                                    شخص حقیقی
                                </td>
                                <td class="align-middle">
                                <p>{{ number_format($account->amount) . ' بهار ' }}</p>
                                </td>
                                <td class="align-middle">
                                    <a href="" class="btn btn-light btn-sm">
                                        <i class="bi bi-eye me-1"></i>
                                        جزئیات
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <div class="icon-circle bg-light mx-auto mb-3">
                                            <i class="bi bi-wallet2 fs-4 text-gray-400"></i>
                                        </div>
                                        <p class="mb-0">هنوز هیچ حسابی ایجاد نکرده‌اید</p>
                                        <a href="" class="btn btn-primary btn-sm mt-3">
                                            <i class="bi bi-plus-lg me-1"></i>
                                            ایجاد اولین حساب
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- آخرین تراکنش‌ها -->
    <div class="card border-0 shadow-sm fade-in" style="animation-delay: 0.4s">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="icon-circle bg-primary bg-opacity-10 me-3">
                    <i class="bi bi-clock-history fs-4 text-primary"></i>
                </div>
                <h6 class="mb-0 text-gray-600">آخرین تراکنش‌ها</h6>
            </div>
            <a href="" class="btn btn-primary btn-sm disabled" style='width: auto; background-color: #37c4b4'>
                <i class="bi bi-list me-1"></i>
                مشاهده همه
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">تاریخ</th>
                            <th class="border-0">نوع</th>
                            <th class="border-0">مبلغ (بهار)</th>
                            <th class="border-0">از حساب</th>
                            <th class="border-0">به حساب</th>
                            <th class="border-0">توضیحات</th>
                            <th class="border-0">وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Transaction::where('from_account_id', \App\Models\Spring::where('user_id', auth()->user()->id)->first()->id)->orWhere('to_account_id',\App\Models\Spring::where('user_id', auth()->user()->id)->first()->id)->get() as $account)
                        <tr>
                            <td class="align-middle">{{ verta($account->created_at)->format('Y-m-d') }}</td>
                                <td class="align-middle">
                                    @if($account->from_account_id != null)
                                        <p>برداشت</p>
                                    @else
                                        <p>واریز</p>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <p>{{ number_format($account->amount) . ' بهار ' }}</p>
                                </td>
                                <td class="align-middle">
                                    <span class="text-muted">
                                        @if($account->from_account_id == null)
                                        
                                        0000000000 <br> سامانه ارثکوپ
                                        
                                        @else
                                            {{ \App\Models\Spring::find($account->from_account_id)->cart_number }}
                                        @endif
                                        
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <span class="text-muted">
                                        @if(\App\Models\Spring::find($account->to_account_id)->cart_number == 0000000000)
                                        
                                        0000000000 <br> سامانه ارثکوپ
                                        
                                        @else
                                            {{ \App\Models\Spring::find($account->to_account_id)->cart_number }}
                                        @endif
                                        </span>
                                </td>
                                                                <td class="align-middle">
                                    <span class="text-muted">{{ $account->description }}</span>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="bi bi-check-circle me-1"></i>
                                        انجام شده
                                    </span>
                                </td>
                            </tr>
                            
                             @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <div class="icon-circle bg-light mx-auto mb-3">
                                            <i class="bi bi-inbox fs-4 text-gray-400"></i>
                                        </div>
                                        <p class="mb-0">هنوز هیچ تراکنشی انجام نشده است</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

