@extends('layouts.app')

@section('content')
<div class="container">



    <div class="row justify-content-center">
        <div class="col-md-8">
                        
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

            <div class="card" style='    direction: rtl;'>
                <div class="card-header text-center bg-primary text-white fs-5">فراموشی رمز عبور</div>

                <div class="card-body">
                                    
                    <form method="POST" action="{{ route('password.reset.send') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">ایمیل</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">ارسال کد تایید</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
