@extends('layouts.admin')
@section('title', 'مدیریت حراج‌های سهام')
@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">لیست حراج‌های سهام</h4>
            <a href="{{ route('admin.auction.create') }}" class="btn btn-success">ایجاد حراج جدید</a>
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
                                @if($auction->status === 'scheduled')
                                    <form method="POST" action="{{ route('admin.auction.start', $auction) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">شروع</button>
                                    </form>
                                @endif
                                
                                @if($auction->status === 'running')
                                    <form method="POST" action="{{ route('admin.auction.close', $auction) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">بستن</button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('auction.show', $auction) }}" class="btn btn-primary btn-sm">جزئیات</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">هیچ حراجی ثبت نشده است.</div>
            @endif
        </div>
    </div>
</div>
@endsection
