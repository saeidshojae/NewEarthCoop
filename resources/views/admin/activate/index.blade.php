@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <h2 class="text-center mb-4 fw-bold">مدیریت فعال‌سازی‌ها</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.activate.update') }}" method="POST" class="row justify-content-center">
        @csrf
        @method('PUT')

        <div class="col-md-6">


            <div class="card shadow-sm mb-4">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <label for="finger_status" class="form-label mb-0 fw-semibold">فعالسازی کد اثر انگشت</label>
                    <input type="checkbox" name="finger_status" id="finger_status"
                        {{ old('finger_status', \App\Models\Setting::find(1)->finger_status) == 1 ? 'checked' : '' }}>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success px-4">ذخیره تغییرات</button>
            </div>
        </div>
    </form>
</div>
@endsection
