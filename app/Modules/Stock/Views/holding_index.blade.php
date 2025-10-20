@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>کیف‌سهام من</h4>
                </div>
                <div class="card-body">
                    @if($holdings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>نام سهام</th>
                                        <th>تعداد</th>
                                        <th>قیمت پایه</th>
                                        <th>ارزش کل</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($holdings as $holding)
                                        <tr>
                                            <td>{{ $holding->stock->info ?? 'سهام' }}</td>
                                            <td>{{ number_format($holding->quantity) }}</td>
                                            <td>{{ number_format($holding->stock->base_share_price) }} ریال</td>
                                            <td>{{ number_format($holding->total_value) }} ریال</td>
                                            <td>
                                                <a href="{{ route('holding.show', $holding->stock_id) }}" class="btn btn-sm btn-primary">
                                                    جزئیات
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h5 class="text-muted">هنوز سهامی ندارید</h5>
                            <p class="text-muted">برای خرید سهام در حراج‌ها شرکت کنید</p>
                            <a href="{{ route('auction.index') }}" class="btn btn-primary">
                                مشاهده حراج‌ها
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
