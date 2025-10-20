@extends('layouts.app')
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
                    <form method="POST" action="{{ route('auction.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">تعداد سهام قابل حراج</label>
                            <input type="number" name="shares_count" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">قیمت پایه هر سهم (تومان)</label>
                            <input type="number" name="base_price" class="form-control" required>
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
                            <label class="form-label">توضیحات تکمیلی</label>
                            <textarea name="info" class="form-control"></textarea>
                        </div>
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                        <button type="submit" class="btn btn-success">ایجاد حراج</button>
                        <a href="{{ route('auction.index') }}" class="btn btn-secondary">بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
