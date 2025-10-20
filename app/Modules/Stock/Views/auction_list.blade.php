@extends('layouts.app')
@section('title', 'لیست حراج‌های سهام')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">لیست حراج‌های سهام</h4>
            <div>
                <a href="{{ route('wallet.index') }}" class="btn btn-warning me-2">کیف‌پول</a>
                <a href="{{ route('holding.index') }}" class="btn btn-success">کیف‌سهام</a>
            </div>
        </div>
        <div class="card-body">
            @if($auctions->count())
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>تعداد سهام</th>
                            <th>قیمت پایه</th>
                            <th>نوع حراج</th>
                            <th>زمان شروع</th>
                            <th>زمان پایان</th>
                            <th>وضعیت</th>
                            <th>پیشنهادات</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($auctions as $auction)
                        <tr>
                            <td>{{ number_format($auction->shares_count) }}</td>
                            <td>{{ number_format($auction->base_price) }} ریال</td>
                            <td>
                                @switch($auction->type)
                                    @case('single_winner') تک برنده @break
                                    @case('uniform_price') قیمت یکسان @break
                                    @case('pay_as_bid') پرداخت به قیمت پیشنهادی @break
                                @endswitch
                            </td>
                            <td>{{ $auction->start_time->format('Y/m/d H:i') }}</td>
                            <td>{{ $auction->ends_at ? $auction->ends_at->format('Y/m/d H:i') : '-' }}</td>
                            <td>
                                <span class="badge badge-{{ $auction->status === 'running' ? 'success' : ($auction->status === 'scheduled' ? 'warning' : 'secondary') }}">
                                    @switch($auction->status)
                                        @case('scheduled') برنامه‌ریزی شده @break
                                        @case('running') فعال @break
                                        @case('settling') در حال تسویه @break
                                        @case('settled') تسویه شده @break
                                        @case('canceled') لغو شده @break
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $auction->bids()->count() }}</td>
                            <td>
                                <a href="{{ route('auction.show', $auction) }}" class="btn btn-primary btn-sm">
                                    {{ $auction->isActive() ? 'شرکت در حراج' : 'مشاهده' }}
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">هیچ حراج فعالی وجود ندارد.</div>
            @endif
        </div>
    </div>
</div>
@endsection
