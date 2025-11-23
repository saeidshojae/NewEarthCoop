@extends('layouts.app')

@section('head-tag')
<style>
  .register-container {
    direction: rtl;
    text-align: right;
    margin-top: 0;
  }

  .card {
    border-radius: 1rem;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
  }

  .card-header {
    background-color: #f8f9fa;
    font-weight: bold;
    font-size: 1.3rem;
  }

  .form-label {
    font-weight: 500;
  }

  .btn-primary {
    padding: 0.6rem 2rem;
    border-radius: 0.5rem;
    font-weight: 500;
    font-size: 1rem;
  }
      .google-login-btn button{
        padding: .3rem;
        margin-top: 1rem;
        border-radius: 0.3rem;
        background-color: #000000;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: row-reverse;
        color: #333;
        border: none;
        direction: rtl;
        width: 100%;
                color: #fff;

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
      .google-login-btn a {
        width: 100%;
      }
</style>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

@endsection

@section('content')
<div class="container register-container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow rounded-3">
        <div class="card-header text-center bg-primary text-white fs-5">ثبت‌نام اولیه</div>

        <div class="card-body">
          <form action="{{ route('register.process') }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">ایمیل:</label>
              <input type="email" name="email" id="email"
                     value="{{ old('email') }}"
                     class="form-control @error('email') is-invalid @enderror" required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            
            @if(isset($_GET['code']))
            <input type='hidden' name='invation_code' value='{{ $_GET['code'] }}'>
            @endif
            
<div class="mb-3 position-relative">
  <label for="password" class="form-label">رمز عبور:</label>
  <input type="password" name="password" id="password"
         class="form-control @error('password') is-invalid @enderror" required>
  <i class="bi bi-eye-slash toggle-password"
     toggle="#password"
     style="position:absolute; top:38px; left:10px; cursor:pointer;"></i>
  @error('password')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3 position-relative">
  <label for="password_confirmation" class="form-label">تایید رمز عبور:</label>
  <input type="password" name="password_confirmation" id="password_confirmation"
         class="form-control" required>
  <i class="bi bi-eye-slash toggle-password"
     toggle="#password_confirmation"
     style="position:absolute; top:38px; left:10px; cursor:pointer;"></i>
</div>
<script>
  document.querySelectorAll('.toggle-password').forEach(function (icon) {
    icon.addEventListener('click', function () {
      const input = document.querySelector(this.getAttribute('toggle'));
      if (input.type === 'password') {
        input.type = 'text';
        this.classList.remove('bi-eye-slash');
        this.classList.add('bi-eye');
      } else {
        input.type = 'password';
        this.classList.remove('bi-eye');
        this.classList.add('bi-eye-slash');
      }
    });
  });
</script>


            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary" style="width: 100%;     padding: .3rem; margin: 0;
    border-radius: .3rem;">ادامه</button>
            </div>
            
    
          </form>
      <div class="google-login-btn">
           
            <a href="{{ route('google.login') }}" class="google-login-btn" >
              <button style='width: 100%'>
                <p>عضویت با حساب گوگل</p>
                <img src="https://cdn1.iconfinder.com/data/icons/google-s-logo/150/Google_Icons-09-512.png" alt="">
              </button>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
