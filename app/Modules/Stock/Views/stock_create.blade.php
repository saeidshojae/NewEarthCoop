@extends('layouts.app')
@section('title', 'ثبت اطلاعات پایه سهام')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">ثبت اطلاعات پایه سهام</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('stock.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">ارزش پایه استارتاپ (تومان)</label>
                            <input type="number" name="startup_valuation" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">تعداد کل سهام</label>
                            <input type="number" name="total_shares" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ارزش پایه هر سهم (تومان)</label>
                            <input type="number" name="base_share_price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات تکمیلی</label>
                            <textarea name="info" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">ثبت اطلاعات</button>
                        <a href="{{ route('stock.index') }}" class="btn btn-secondary">بازگشت</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
