@extends('layouts.admin')
@section('title', 'ویرایش اطلاعات پایه سهام')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">ویرایش اطلاعات پایه سهام</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.stock.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ارزش پایه استارتاپ (تومان)</label>
                            <input type="number" name="startup_valuation" class="form-control" value="{{ $stock->startup_valuation ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تعداد کل سهام</label>
                            <input type="number" name="total_shares" class="form-control" value="{{ $stock->total_shares ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ارزش پایه هر سهم (تومان)</label>
                            <input type="number" name="base_share_price" class="form-control" value="{{ $stock->base_share_price ?? '' }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات تکمیلی</label>
                            <textarea name="info" class="form-control">{{ $stock->info ?? '' }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success">ذخیره اطلاعات</button>
                        <a href="{{ route('admin.stock.index') }}" class="btn btn-secondary">بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
