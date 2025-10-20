@extends('layouts.app')
@section('title', 'ثبت پیشنهاد خرید سهام')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">ثبت پیشنهاد خرید سهام</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('auction.bid', $auction->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">تعداد سهام موردنظر</label>
                            <input type="number" name="shares_count" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">قیمت پیشنهادی هر سهم (تومان)</label>
                            <input type="number" name="bid_price" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">ثبت پیشنهاد</button>
                        <a href="{{ route('auction.index') }}" class="btn btn-secondary">بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
