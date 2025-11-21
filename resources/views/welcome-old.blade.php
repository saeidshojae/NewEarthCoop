@extends('layouts.app')

@section('content')
<div class="container " style="direction: rtl;">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card shadow rounded-3">
        <div class="card-header text-center bg-primary text-white fs-5">
          خوش آمدید به EarthCoop
        </div>
        <h1 style='text-align: center; margin: 1rem'>{{ \App\Models\Setting::find(1)->welcome_titre }}</h1>
        <label style='margin: 1rem;
    text-align: center;'>{!! \App\Models\Setting::find(1)->welcome_content !!}</label>

        {{-- اسلایدر تصاویر --}}  
        <swiper-container class="mySwiper" pagination="true" loop="true" style="width: 100%; padding: .75rem; padding-bottom: 0; padding-top: 0" autoplay-delay="6000">
          @foreach(\App\Models\Slider::where('position', 0)->get() as $slider)
                <swiper-slide>
                  <img src="{{ asset('images/sliders/' . $slider->src) }}" class="w-100 rounded-3" alt="slide 1">
                </swiper-slide>
                @endforeach
        </swiper-container>

        

        
        <br>
        {{-- فرم اصلی --}}
        <form id="registrationForm" action="{{ route('register.accept') }}" method="POST" class="px-4 pb-4" novalidate>
          @csrf
        
          
          @php
           $setting = \App\Models\Setting::find(1);
           @endphp
            
            @if($setting->invation_status == 1)
            <div class="text-center my-4">
              <h5 class="fw-bold">در مرحله آزمایشی هستیم ثبت نام فقط برای کاربران فارسی زبان و با کد دعوت امکان پذیر است</h5>
              <p class="text-muted">برای ادامه، لطفاً کد دعوت را وارد کرده و شرایط را بپذیرید</p>
            </div>
            
            @if($setting->invation_status == 1)
          <div class="mb-3 text-center">
            <p class="mb-1">کد دعوت ندارید؟ <a href="{{ route('invite') }}">درخواست کنید</a></p>
          </div>
@endif
          <div class="mb-3">
            <label for="invite_code" class="form-label">کد دعوت:</label>
            <input type="text" name="invite_code" id="invite_code" class="form-control" placeholder="مثلاً: ABC123" value='{{ isset($_GET['code']) ?  $_GET['code']: '' }}'>
            @error('invite_code')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

@endif

          {{-- توافق‌نامه و لینک‌ها --}}
          <div class="form-check mb-3">
            <input class="form-check-input" style='    border: 1px solid #333;' type="checkbox" id="agreement">
            <label class="form-check-label" for="agreement">
              <span>با <a href="javascript:void(0)" onclick="openTerms()">اساسنامه و شرایط استفاده</a> موافقم</span>
            </label>
            <div id="agreementError" class="text-danger small mt-1" style="display: none;">برای ادامه باید قوانین را بپذیرید.</div>
          </div>


          <div class="d-grid">
            <button type="submit" class="btn btn-success">شروع عضویت</button><br>
            <a href="{{ route('login') }}" class="btn btn-primary">ورود به سامانه</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- اسکریپت‌های مربوطه --}}
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
@if(session('success'))
  <script>alert('{{ session('success') }}');</script>
@endif

<script>
  document.addEventListener("DOMContentLoaded", function() {
    if (localStorage.getItem('termsAccepted') === 'true') {
        document.getElementById('agreement').checked = true;
    }

    window.addEventListener("storage", function(event) {
      if (event.key === "termsAccepted" && event.newValue === 'true') {
          document.getElementById('agreement').checked = true;
      }
    });

    document.getElementById('registrationForm').addEventListener('submit', function(event) {
      var agreementCheckbox = document.getElementById('agreement');
      if (!agreementCheckbox.checked) {
        event.preventDefault();
        document.getElementById('agreementError').style.display = 'block';
      } else {
        document.getElementById('agreementError').style.display = 'none';
      }
    });
  });

  function openTerms() {
    var termsWindow = window.open("{{ route('terms') }}", "_blank");
    var checkInterval = setInterval(function() {
      if (termsWindow.closed) {
        if (localStorage.getItem('termsAccepted') === 'true') {
          document.getElementById('agreement').checked = true;
        }
        clearInterval(checkInterval);
      }
    }, 500);
  }
</script>
@endsection
