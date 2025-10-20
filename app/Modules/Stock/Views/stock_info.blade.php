@extends('layouts.app')
@section('title', 'مدیریت سهام')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">اطلاعات پایه سهام</h4>
        </div>
        <div class="card-body">
            @if($stock)
                <ul class="list-group list-group-flush mb-3">
                    <li class="list-group-item">ارزش پایه استارتاپ: <strong>{{ number_format($stock->startup_valuation) }} تومان</strong></li>
                    <li class="list-group-item">تعداد کل سهام: <strong>{{ number_format($stock->total_shares) }}</strong></li>
                    <li class="list-group-item">ارزش پایه هر سهم: <strong>{{ number_format($stock->base_share_price) }} تومان</strong></li>
                    <li class="list-group-item">توضیحات: <span>{{ $stock->info }}</span></li>
                </ul>
            @else
                <div class="alert alert-warning">هنوز اطلاعات سهام ثبت نشده است.</div>
            @endif
            <a href="{{ route('stock.create') }}" class="btn btn-success">ثبت اطلاعات پایه سهام</a>
        </div>
    </div>
</div>
@endsection
