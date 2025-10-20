@extends('layouts.app')

@section('head-tag')
  <!-- Persian Datepicker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
  <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">

  <style>
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    .card-header {
      background-color: #f8f9fa;
      font-size: 1.2rem;
      font-weight: bold;
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
    input.normal-example {
      border-radius: 0.4rem;
      border: 1px solid #ced4da;
    }
    #plotId{
    font-family: vazir !important
  }
  
  </style>
@endsection

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header text-center bg-primary text-white fs-5">مرحله ۱: اطلاعات هویتی</div><br>
        <p style='text-align: center'>به جز رمز عبور میتوانید سایر اطلاعات هویتی خود را بعدا وارد نمایید</p>

        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
        <div class="card-body">
          <form action="{{ route('register.step1.process') }}" method="POST" novalidate>
            @csrf

            <div class="mb-3">
              <label for="first_name" class="form-label">نام:</label>
              <input type="text" name="first_name" id="first_name"
                     value="{{ old('first_name') }}"
                     class="form-control @error('first_name') is-invalid @enderror" required placeholder='فقط نام فارسی مجاز است'>
              @error('first_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="last_name" class="form-label">نام خانوادگی:</label>
              <input type="text" name="last_name" id="last_name"
                     value="{{ old('last_name') }}"
                     class="form-control @error('last_name') is-invalid @enderror" required placeholder='فقط نام فارسی مجاز است'>
              @error('last_name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            

<div class="mb-3">
    <label for="birth_date" class="form-label">تاریخ تولد:</label>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        
        <select name="birth_date[]" class="form-control" id="" style="width: 30%">
                            <option value="1">انتخاب</option>

            @for ($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}" {{ old('birth_date.0') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <select name="birth_date[]" class="form-control" id="" style="width: 30%">
            <option value="1" {{ old('birth_date.1') == 1 ? 'selected' : '' }}>انتخاب</option>
            <option value="1" {{ old('birth_date.1') == 1 ? 'selected' : '' }}>فروردین</option>
            <option value="2" {{ old('birth_date.1') == 2 ? 'selected' : '' }}>اردیبهشت</option>
            <option value="3" {{ old('birth_date.1') == 3 ? 'selected' : '' }}>خرداد</option>
            <option value="4" {{ old('birth_date.1') == 4 ? 'selected' : '' }}>تیر</option>
            <option value="5" {{ old('birth_date.1') == 5 ? 'selected' : '' }}>مرداد</option>
            <option value="6" {{ old('birth_date.1') == 6 ? 'selected' : '' }}>شهریور</option>
            <option value="7" {{ old('birth_date.1') == 7 ? 'selected' : '' }}>مهر</option>
            <option value="8" {{ old('birth_date.1') == 8 ? 'selected' : '' }}>آبان</option>
            <option value="9" {{ old('birth_date.1') == 9 ? 'selected' : '' }}>آذر</option>
            <option value="10" {{ old('birth_date.1') == 10 ? 'selected' : '' }}>دی</option>
            <option value="11" {{ old('birth_date.1') == 11 ? 'selected' : '' }}>بهمن</option>
            <option value="12" {{ old('birth_date.1') == 12 ? 'selected' : '' }}>اسفند</option>
        </select>

        @php
            use Morilog\Jalali\Jalalian;

            $currentYear = Jalalian::now()->getYear() - 15;
            $startYear = $currentYear - 135;
        @endphp

        <select name="birth_date[]" class="form-control" id="" style="width: 30%">
                            <option value="1">انتخاب</option>
            @for ($i = $currentYear; $i >= $startYear; $i--)
                <option value="{{ $i }}" {{ old('birth_date.2') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

    </div>

    @error('birth_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>


            <div class="mb-3">
              <label for="gender" class="form-label">جنسیت:</label>
              <select name="gender" id="gender"
                      class="form-control @error('gender') is-invalid @enderror" required>
                <option value="">انتخاب کنید</option>
                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>مرد</option>
                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>زن</option>
              </select>
              @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="nationality" class="form-label">ملیت:</label>
              <select name="nationality" id="nationality"
                      class="form-control @error('nationality') is-invalid @enderror" required>
                <option value="ایرانی" {{ old('nationality') == 'ایرانی' ? 'selected' : '' }}>ایرانی</option>
                <option value="مهاجر" {{ old('nationality') == 'مهاجر' ? 'selected' : '' }}>مهاجر</option>
              </select>
              @error('nationality')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="national_id" class="form-label">کدملی:</label>
              <input type="text" name="national_id" id="national_id"
                     value="{{ old('national_id') }}"
                     class="form-control @error('national_id') is-invalid @enderror" required>
              @error('national_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            @include('partials.countries-list')

            <br>

            @if (auth()->user()->password == null)
            <!-- لود FontAwesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>

<style>
  .position-relative { position: relative; }
  .toggle-password {
    position: absolute;
    top: 50%;
    left: 1rem;
    top: 3rem;
    transform: translateY(-50%);
    cursor: pointer;
    user-select: none;
    font-size: 1.1rem;
    color: #777;
  }
  /* فضای خالی برای آیکون */
  .has-eye { padding-right: 2.5rem; }
</style>

<div class="mb-3 position-relative">
  <label for="password" class="form-label">رمز عبور (*)</label>
  <input
    type="password"
    name="password"
    id="password"
    class="form-control has-eye @error('password') is-invalid @enderror"
    required
  >
  <i class="fa fa-eye toggle-password" data-target="#password"></i>
  @error('password')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

<div class="mb-3 position-relative">
  <label for="password_confirmation" class="form-label">تایید رمز عبور (*)</label>
  <input
    type="password"
    name="password_confirmation"
    id="password_confirmation"
    class="form-control has-eye"
    required
  >
  <i class="fa fa-eye toggle-password" data-target="#password_confirmation"></i>
</div>

<script>
  document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', () => {
      const input = document.querySelector(toggle.getAttribute('data-target'));
      const icon  = toggle;
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  });
</script>

            @endif

            <div class="mb-3">
              <label for="email" class="form-label">ایمیل:</label>
              <input type="email" id="email" disabled
                     value="{{ old('email', auth()->user()->email) }}"
                     class="form-control @error('email') is-invalid @enderror">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="text-center mt-4">
              <button type="submit" class="btn btn-primary">ادامه</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')


<script>

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showSuccessAlert('{{ session('success') }}');
    });
@endif

 const focusable = document.querySelectorAll('form input, form select, form textarea');

focusable.forEach((field, index) => {
    field.addEventListener('keydown', function (e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // جلوگیری از سابمیت فرم

            // پیدا کردن عنصر بعدی قابل فوکوس
            const next = focusable[index + 1];
            if (next) next.focus();
        }
    });
});
</script>

<script>
  $('.normal-example').persianDatepicker({
    initialValueType: 'persian',
    format: 'YYYY-MM-DD'
  });

  function updatePlaceholder() {
    const select = document.getElementById('country_code');
    const selected = select.options[select.selectedIndex];
    const placeholder = selected.getAttribute('data-placeholder');
    document.getElementById('phone').placeholder = 'برای مثال: ' + placeholder;
  }
</script>
@endsection
