@extends('layouts.app')
@section('head-tag')
    <style>
      .google-login-btn button{
        padding: .3rem;
        margin-top: 1rem;
        border-radius: 0.3rem;
        background-color: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row-reverse;
        color: #fff;
        border: none;
        direction: rtl
      }
      .google-login-btn img{
        width: 1rem;
        background-color: #fff;
        border-radius: .2rem;
        margin-left: .7rem;
      }
      .google-login-btn p {
        margin: 0;
      }
      .google-login-btn {
        text-decoration: none;
        direction: rtl;

        display: flex;
      }
    </style>
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
@if(session('success'))
    <div class="alert alert-success text-end" dir="rtl">
        {{ session('success') }}
    </div>
@endif


            <div class="card" style='    direction: rtl;'>
                <div class="card-header text-center bg-primary text-white fs-5">ورود به سیستم</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">آدرس ایمیل</label>

                            <div class="col-md-6">
                                <input id="email" type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       name="email" value="{{ old('email') }}" 
                                       required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
<div class="row mb-3">
    <label for="password" class="col-md-4 col-form-label text-md-end">رمز عبور</label>

    <div class="col-md-6 position-relative">
        <input id="password" type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" required autocomplete="current-password">

        <span class="toggle-password" onclick="togglePassword('password', this)">
            <i class="fa fa-eye"></i>
        </span>

        @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<style>
    .toggle-password {
        position: absolute;
        top: 50%;
        left: 20px; /* 👈 آیکون سمت چپ اینپوت */
        transform: translateY(-50%);
        cursor: pointer;
        user-select: none;
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

<!-- FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" style='border: 1px solid #333'
                                           name="remember" id="remember" 
                                           {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        مرا به خاطر بسپار
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="form-check">
                              
                                    <a class="form-check-label" href='{{ route('password.reset.view') }}'>
                                        رمز عبورم را فراموش کرده ام
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        
<button type="submit" class="btn btn-primary" style='width: 100%; margin: 0'>
                                    ورود 
                                </button>

                    </form>

                    <a href="{{ route('google.login', ['login' => true]) }}" class="google-login-btn" >
                        <button style='width: 100%'>
                          <p>ورود با حساب گوگل</p>
                          <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png" alt="">
                        </button>
                      </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
