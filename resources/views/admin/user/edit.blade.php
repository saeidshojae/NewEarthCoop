@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>ویرایش کاربر جدید</h4>
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
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
    <label for="first_name" class="form-label">نام</label>
    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required>
    @error('first_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="last_name" class="form-label">نام خانوادگی</label>
    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
    @error('last_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">ایمیل</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="phone" class="form-label">شماره تماس</label>
    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
    @error('phone')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="national_id" class="form-label">کد ملی</label>
    <input type="text" class="form-control @error('national_id') is-invalid @enderror" id="national_id" name="national_id" value="{{ old('national_id', $user->national_id) }}" required>
    @error('national_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="gender" class="form-label">جنسیت</label>
    <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>مرد</option>
        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>زن</option>
    </select>
    @error('gender')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="birth_date" class="form-label">تاریخ تولد:</label>

    <div style="display: flex; align-items: center; justify-content: space-between;">
        
<select name="birth_date[]" class="form-control" style="width: 30%">
    @for ($i = 1; $i <= 31; $i++)
        <option value="{{ $i }}" {{ old('birth_date.0', optional(jdate($user->birth_date))->getDay()) == $i ? 'selected' : '' }}>{{ $i }}</option>
    @endfor
</select>


   <select name="birth_date[]" class="form-control" style="width: 30%">
    @for ($i = 1; $i <= 12; $i++)
        <option value="{{ $i }}" {{ old('birth_date.1', optional(jdate($user->birth_date))->getMonth()) == $i ? 'selected' : '' }}>
            {{ ['فروردین','اردیبهشت','خرداد','تیر','مرداد','شهریور','مهر','آبان','آذر','دی','بهمن','اسفند'][$i - 1] }}
        </option>
    @endfor
</select>




@php
    use Morilog\Jalali\Jalalian;

    $currentYear = Jalalian::now()->getYear() - 15;
    $startYear = $currentYear - 135;
@endphp

<select name="birth_date[]" class="form-control" style="width: 30%">
    @for ($i = $currentYear; $i >= $startYear; $i--)
        <option value="{{ $i }}" {{ old('birth_date.2', optional(jdate($user->birth_date))->getYear()) == $i ? 'selected' : '' }}>{{ $i }}</option>
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
                       class="form-control @error('password') is-invalid @enderror" >
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label for="password_confirmation" class="form-label">تایید رمز عبور:</label>
                <input type="password" name="password_confirmation"
                       id="password_confirmation" class="form-control" >
              </div>
              
              <div class='mb-3'>
                    <label>محدودیت های کاربر</label>
                    <div class='form-group col-md-3'>
                        <label>پست</label>
                        <input type='checkbox' name='blocks[]' value='post' {{ \App\Models\Block::where('user_id', $user->id)->where('position', 'post')->first() != null ? 'checked' : '' }}>
                    </div>
                    <div class='form-group col-md-3'>
                        <label>نظرسنجی</label>
                        <input type='checkbox' name='blocks[]' value='poll' {{ \App\Models\Block::where('user_id', $user->id)->where('position', 'poll')->first() != null ? 'checked' : '' }}>
                    </div>
                    <div class='form-group col-md-3'>
                        <label>رای دادن</label>
                        <input type='checkbox' name='blocks[]' value='election' {{ \App\Models\Block::where('user_id', $user->id)->where('position', 'election')->first() != null ? 'checked' : '' }}>
                    </div>
                    <div class='form-group col-md-3'>
                        <label>پیام</label>
                        <input type='checkbox' name='blocks[]' value='message' {{ \App\Models\Block::where('user_id', $user->id)->where('position', 'message')->first() != null ? 'checked' : '' }}>
                    </div>
              </div>

                        
                        <button type="submit" class="btn btn-primary">ویرایش کاربر</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">انصراف</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection 