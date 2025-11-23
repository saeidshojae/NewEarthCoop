@extends('layouts.app')

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
  <div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header text-center bg-primary text-white fs-5">
          خوش آمدید به ارث کوپ
        </div>
        
        <form id="registrationForm" action="{{ route('invite.store') }}" method="POST" novalidate>
          @csrf
          <div class="card-body" style="display: flex; flex-direction: column; align-items: center; gap: 1.5rem;">

            <div style='display: flex; align-items: center; flex-direction: row-reverse;'>
                <p class="text-center fs-5" style='margin: 0; margin-right: .5rem'>درخواست کد دعوت</p>
<svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
  <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/>
  <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/>
</svg>
            </div>

            {{-- ایمیل --}}
            <div class="form-group w-100">
              <label for="email" class="fw-bold">ایمیل شما:</label>
              <input type="email" name="email" placeholder="main@example.com" class="form-control @error('email') is-invalid @enderror" required>
              @error('email')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- پیام آشنایی --}}
            <div class="form-group w-100">
              <label for="message" class="fw-bold">چطور با ما آشنا شدید؟</label>
              <textarea name="message" rows="3" placeholder="تجربه، لینک، دوست، شبکه اجتماعی و ..." class="form-control @error('message') is-invalid @enderror" required></textarea>
              @error('message')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            {{-- تخصص --}}
            <div class="form-group w-100">
              <label for="job" class="fw-bold">حرفه کاری یا زمینه تخصصی شما:</label>
              <input type="text" name="job" placeholder="مثلاً: توسعه‌دهنده وب" class="form-control @error('job') is-invalid @enderror" required>
              @error('job')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <button type="submit" class="btn btn-success w-100 mt-3">ثبت درخواست</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
