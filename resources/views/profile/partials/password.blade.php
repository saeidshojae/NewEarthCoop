
<div class="toggle-box">
    <div class="toggle-header" onclick="toggleBox(this)">
        <span>رمز حساب من</span>
        <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
    </div>
    <div class="toggle-content" id="toggleContent">
        <hr>
        <form action="{{ route('profile.update.password') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="form-group col-12">
                <label for="">رمز فعلی</label>
                <input type="password" name="current_password" placeholder="رمز عبور فعلی خود را وارد کنید" class="form-control" @error('current_password') is-invalid @enderror>
                @error('current_password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>

        <div class="row">
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">رمز جدید</label>
                <input type="password" name="password" placeholder="رمز عبور جدید خود را وارد کنید" class="form-control" @error('password') is-invalid @enderror>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">تکرار رمز عبور جدید</label>
                <input type="password" name="password_confirmation" placeholder="تکرار رمز عبور جدید فعلی خود را وارد کنید" class="form-control" @error('password_confirmation') is-invalid @enderror>
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>

        <input type="submit" value="ذخیره تغییرات" class="btn btn-primary" style="background-color: #518dbdcc !important;">
        </form>
    </div>
    </div>
