@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>ایجاد کاربر جدید</h4>
                </div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                <div class="card-body">
                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="first_name" class="form-label">نام</label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="last_name" class="form-label">نام خانوادگی</label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">ایمیل</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">شماره تماس</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="national_id" class="form-label">کد ملی</label>
                            <input type="text" class="form-control @error('national_id') is-invalid @enderror" id="national_id" name="national_id" value="{{ old('national_id') }}" required>
                            @error('national_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="gender" class="form-label">جنسیت</label>
                            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" >
                                <option value='male' {{ old('gender') == 'male' ? 'selected' : '' }}>مرد</option>
                                <option value='female' {{ old('gender') == 'female' ? 'selected' : '' }}>زن</option>
                            </select>
                            @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        

<div class="mb-3">
    <label for="birth_date" class="form-label">تاریخ تولد:</label>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        
        <select name="birth_date[]" class="form-control" id="" style="width: 30%">
            @for ($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}" {{ old('birth_date.0') == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>

        <select name="birth_date[]" class="form-control" id="" style="width: 30%">
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
                <label for="password" class="form-label">رمز عبور:</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror" required>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password_confirmation" class="form-label">تایید رمز عبور:</label>
                <input type="password" name="password_confirmation"
                       id="password_confirmation" class="form-control" required>
              </div>

                        
                        <button type="submit" class="btn btn-primary">ایجاد کاربر</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">انصراف</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection 