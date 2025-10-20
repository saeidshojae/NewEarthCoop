@extends('layouts.app')

@section('title', 'توافقنامه حساب نجم بهار')

@section('content')
<div class="container my-5" dir="rtl">

    <!-- هدر بالای صفحه -->
    <div class="mb-4 text-end">
        <h1 class="fw-bold text-success">به نجم بهار خوش آمدید</h1>
        <p class="text-muted">
            (نرم‌افزار جامع مدیریت بانکی هوشمند الکترونیک رایگان ارثکوپ)
        </p>
    </div>

    <!-- کارت توافقنامه -->
    <div class="card shadow-sm text-end">
        <div class="card-body">
            {!! \App\Models\Setting::findOrFail(1)->najm_summary !!}
            
            @if(auth()->user()->status == 1)
        
            
            <div class="text-center mt-4">
                <form action="{{ route('najm.confirm') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        موافقت و ادامه
                    </button>
                </form>
            </div>
            
            @else
                        
            <div class="text-center mt-4">
                <form action="{{ route('profile.edit') }}" method="GET">
                    @csrf
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        ابتدا حساب خود را تکمیل کنید
                    </button>
                </form>
            </div>
            
            @endif
            <div class="text-center mt-3 text-muted">
                <small>توافقنامه به‌روز رسانی می‌شود.</small>
            </div>
        </div>
    </div>

</div>
@endsection
