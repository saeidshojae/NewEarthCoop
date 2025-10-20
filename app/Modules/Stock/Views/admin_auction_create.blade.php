@extends('layouts.admin')
@section('title', 'ایجاد حراج جدید سهام')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">ایجاد حراج جدید سهام</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.auction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">تعداد سهام قابل حراج</label>
                            <input type="number" name="shares_count" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">قیمت پایه هر سهم (ریال)</label>
                            <input type="number" name="base_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نوع حراج</label>
                            <select name="type" class="form-control" required>
                                <option value="single_winner">تک برنده</option>
                                <option value="uniform_price">قیمت یکسان</option>
                                <option value="pay_as_bid">پرداخت به قیمت پیشنهادی</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">حداقل قیمت پیشنهادی (ریال)</label>
                                    <input type="number" name="min_bid" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">حداکثر قیمت پیشنهادی (ریال)</label>
                                    <input type="number" name="max_bid" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">اندازه لات (حداکثر تعداد سهام در هر پیشنهاد)</label>
                            <input type="number" name="lot_size" class="form-control" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">زمان شروع حراج</label>
                            <input type="datetime-local" name="start_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">زمان پایان حراج</label>
                            <input type="datetime-local" name="end_time" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">زمان بسته شدن خودکار</label>
                            <input type="datetime-local" name="ends_at" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات تکمیلی</label>
                            <textarea name="info" class="form-control"></textarea>
                        </div>
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                        <button type="submit" class="btn btn-success">ایجاد حراج</button>
                        <a href="{{ route('admin.auction.index') }}" class="btn btn-secondary">بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
