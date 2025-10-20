@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>کیف‌پول من</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5>موجودی قابل استفاده</h5>
                                <h3 class="text-success">{{ number_format($wallet->available_balance) }} ریال</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5>موجودی کل</h5>
                                <h3 class="text-primary">{{ number_format($wallet->balance) }} ریال</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center">
                                <h5>مبلغ رزرو شده</h5>
                                <h3 class="text-warning">{{ number_format($wallet->held_amount) }} ریال</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>تاریخچه تراکنش‌ها</h5>
                </div>
                <div class="card-body">
                    @if($transactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>نوع</th>
                                        <th>مبلغ</th>
                                        <th>توضیحات</th>
                                        <th>تاریخ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transactions as $transaction)
                                        <tr>
                                            <td>
                                                <span class="badge badge-{{ $transaction->type === 'credit' ? 'success' : ($transaction->type === 'debit' ? 'danger' : 'warning') }}">
                                                    @switch($transaction->type)
                                                        @case('credit') واریز @break
                                                        @case('debit') برداشت @break
                                                        @case('hold') رزرو @break
                                                        @case('release') آزادسازی @break
                                                        @case('settlement') تسویه @break
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ number_format($transaction->amount) }} ریال</td>
                                            <td>{{ $transaction->description ?? '-' }}</td>
                                            <td>{{ $transaction->created_at->format('Y/m/d H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $transactions->links() }}
                    @else
                        <p class="text-muted">هنوز تراکنشی انجام نداده‌اید.</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>دسترسی سریع</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('auction.index') }}" class="btn btn-primary btn-block mb-2">
                        مشاهده حراج‌ها
                    </a>
                    <a href="{{ route('holding.index') }}" class="btn btn-success btn-block mb-2">
                        کیف‌سهام من
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
