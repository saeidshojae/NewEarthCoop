@extends('layouts.app')

@section('content')
<div class="container">


    <div class="row justify-content-center">
        <div class="col-md-8">
            
@if(session('success'))
    <div class="alert alert-success text-end" dir="rtl">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger text-end" dir="rtl">
        {{ session('error') }}
    </div>
@endif


            <div class="card" style='    direction: rtl;'>
                <div class="card-header text-center bg-primary text-white fs-5">تغییر رمز عبور</div>

                <div class="card-body">
                    
                    <form method="POST" action="{{ route('password.reset') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">
                
                        <div class="form-group">
                            <label for="code">کد تایید</label>
                            <input type="text" name="code" id="code" class="form-control" required>
                            @error('code') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                
                      <!-- رمز جدید -->
<div class="form-group mt-3 position-relative">
    <label for="password">رمز جدید</label>
    <input type="password" name="password" id="password" class="form-control" required>
    <span class="toggle-password" onclick="togglePassword('password', this)">
        <i class="fa fa-eye"></i>
    </span>
    @error('password')
        <span class="text-danger">{{ $message }}</span>
    @enderror
</div>

<!-- تکرار رمز جدید -->
<div class="form-group mt-3 position-relative">
    <label for="password_confirmation">تکرار رمز جدید</label>
    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
    <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
        <i class="fa fa-eye"></i>
    </span>
</div>

<style>
    .toggle-password {
        position: absolute;
        top: 70%;
        left: 10px;
        cursor: pointer;
        user-select: none;
        transform: translateY(-50%);
        color: #555;
    }
    .toggle-password:hover {
        color: #000;
    }
</style>

<script>
    function togglePassword(inputId, el) {
        const input = document.getElementById(inputId);
        const icon = el.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>

<!-- یادت نره FontAwesome رو لود کنی -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

                
                        <button type="submit" class="btn btn-success mt-3">ذخیره رمز جدید</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
