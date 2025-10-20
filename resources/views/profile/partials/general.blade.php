<div class="toggle-box">
    <div class="toggle-header" onclick="toggleBox(this)">
        <span>اطلاعات کلی من (وضعیت: {{ $user->status == 0 ? 'غیر فعال' : 'فعال' }})</span>
        <i class="fas fa-chevron-down toggle-icon" id="toggleIcon"></i>
    </div>
    @if($user->edited == 0)
        <span style='text-align: center'>توجه داشته باشید که اطلاعات هویتی شما فقط برای یکبار قابلیت ویرایش دارد، لطفا در وارد کردن اطلاعات دفت فرمایید.</span>
    @endif
    <div class="toggle-content" id="toggleContent">
        <hr>
        <form action="{{ route('profile.update.general') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">نام</label>
                <input type="text" name="first_name" placeholder="نام خود را وارد کنید" class="form-control" @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }} @endif @error('first_name') is-invalid @enderror value="{{ old('first_name', auth()->user()->first_name) }}">
                @error('first_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">نام خانوادگی</label>
                <input type="text" name="last_name" placeholder="نام خانوادگی خود را وارد کنید" class="form-control"  @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }} @endif @error('last_name') is-invalid @enderror value="{{ old('last_name', auth()->user()->last_name) }}">
                @error('last_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>
        <div class="row">
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">ایمیل</label>
                <input type="text" name="email" disabled placeholder="ایمیل خود را وارد کنید" class="form-control" @error('email') is-invalid @enderror value="{{ old('email', auth()->user()->email) }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">شماره تلفن</label>
                <div style="display: flex; justify-content: space-between; position: relative">
                    <input type="text" name="phone" id="phone" {{ $user->status == 1 ? 'disabled' : '' }} class="form-control @error('phone') is-invalid @enderror" 
                        placeholder="برای مثال: 9123456789" value="{{ old('phone', auth()->user()->phone) }}">
                    <select name="country_code" class="form-control" id="country_code" onchange="updatePlaceholder()" style="position: absolute; left: 1px; width: 15%; border: none; top: 2px; border-right: 1px solid #33333347; border-radius: 0;">
                        @foreach ($countryCodes as $country)
                            <option value="{{ $country['code'] }}"
                                data-placeholder="{{ $country['example'] }}"
                                {{ old('country_code', '+98') == $country['code'] ? 'selected' : '' }}>
                                {{ $country['flag'] }} {{ $country['name'] }} ({{ $country['code'] }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>
        <div class="row">
            
@php
    use Morilog\Jalali\Jalalian;

    // تبدیل تاریخ میلادی به شمسی
    $birthDate = $user->birth_date ? Jalalian::fromDateTime($user->birth_date) : null;

    $defaultDay   = $birthDate ? $birthDate->getDay() : null;
    $defaultMonth = $birthDate ? $birthDate->getMonth() : null;
    $defaultYear  = $birthDate ? $birthDate->getYear() : null;
@endphp

<div class="form-group col-lg-6 col-md-12 col-12">
    <label for="birth_date" class="form-label">تاریخ تولد:</label>
    <div style="display: flex; align-items: center; justify-content: space-between;">
        {{-- روز --}}
        <select name="birth_date[]" @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }}  @endif class="form-control" style="width: 30%">
            @for ($i = 1; $i <= 31; $i++)
                <option value="{{ $i }}"
                    {{ old('birth_date.0', $defaultDay) == $i ? 'selected' : '' }}>
                    {{ $i }}
                </option>
            @endfor
        </select>

        {{-- ماه --}}
        <select name="birth_date[]" @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }}  @endif class="form-control" style="width: 30%">
            @php
                $months = [
                    1 => 'فروردین', 2 => 'اردیبهشت', 3 => 'خرداد',
                    4 => 'تیر', 5 => 'مرداد', 6 => 'شهریور',
                    7 => 'مهر', 8 => 'آبان', 9 => 'آذر',
                    10 => 'دی', 11 => 'بهمن', 12 => 'اسفند'
                ];
            @endphp
            @foreach ($months as $num => $name)
                <option value="{{ $num }}"
                    {{ old('birth_date.1', $defaultMonth) == $num ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @endforeach
        </select>

        {{-- سال --}}
        @php
            $currentYear = Jalalian::now()->getYear() - 15;
            $startYear = $currentYear - 135;
        @endphp
        <select name="birth_date[]" @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }} @endif class="form-control" style="width: 30%">
            @for ($i = $currentYear; $i >= $startYear; $i--)
                <option value="{{ $i }}"
                    {{ old('birth_date.2', $defaultYear) == $i ? 'selected' : '' }}>
                    {{ $i }}
                </option>
            @endfor
        </select>
    </div>

    @error('birth_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">کدملی</label>
                <input type="text" name="national_id" {{ $user->status == 1 ? 'disabled' : '' }} placeholder="کد ملی خود را وارد کنید" class="form-control" @error('national_id') is-invalid @enderror value="{{ old('national_id', auth()->user()->national_id) }}">
                @error('national_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>
        <div class="row">
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">جنسیت</label>
                <select name="gender" id="gender"  @if($user->status == 1) {{ $user->edited == 1 ? 'disabled' : '' }} @endif
                    class="form-control @error('gender') is-invalid @enderror" >
                    <option value="">انتخاب کنید</option>
                    <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>مرد</option>
                    <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>زن</option>
                </select>
                @error('gender')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-lg-6 col-md-12 col-12">

    @if (auth()->user()->documents != null)
        <div style="margin-top: 5px">
            <a onclick="toggleDocumentsBox()" style='    color: blue; cursor: pointer;
    text-decoration: underline;'>نمایش مدارک بارگذاری شده</a>
        </div>
    @endif

    <div id="documents-box" style="display: none; margin-top: 15px;
    margin-top: 15px;">
        {{-- لیست مدارک فعلی --}}
        @if(auth()->user()->documents && auth()->user()->documents != null)
           <ul class="list-group mb-3" style="padding: 0">
    @foreach(explode(',', auth()->user()->documents) as $index => $doc)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <a href="{{ asset('images/users/documents/' . $doc) }}" target="_blank">مدرک {{ $index + 1 }}</a>

            <button type="button"
                class="btn btn-danger btn-sm"
                style="width: auto"
                onclick="deleteDocument({{ $index }})"
            >حذف</button>
        </li>
    @endforeach
</ul>

        @else
            <p class="text-muted">مدارکی بارگذاری نشده است.</p>
        @endif

        @error('documents')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
</div>

<script>
    function toggleDocumentsBox() {
        const box = document.getElementById('documents-box');
        if (box.style.display === 'none' || box.style.display === '') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }
</script>
        <label>افزودن مدارک جدید:</label>
                <input type="file" name="documents[]" class="form-control" @error('documents') is-invalid @enderror multiple>
                @error('documents')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div><br>
        <div class="row">
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">آواتار (اختیاری)</label>
                <input type="file" name="avatar" id="avatar-input" class="form-control" accept="image/*" style="display: none;">
                <input type="hidden" name="cropped_avatar" id="cropped-avatar">
                
                <!-- دکمه انتخاب تصویر -->
                <button type="button" class="btn btn-secondary w-100" id="avatar-select-btn">انتخاب تصویر</button>
                
                <!-- نمایش پیش‌نمایش -->
                <div class="mt-3">
                    <div id="avatar-preview-container" style="display: none; max-width: 100%; margin-top: 10px;">
                        <img id="avatar-preview" src="" style="max-width: 100%;">
                    </div>
                </div>

                @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-lg-6 col-md-12 col-12">
                <label for="">بیوگرافی (اختیاری)</label>
                <textarea name="biografie" class="form-control" placeholder="بیوگرافی خود را وارد کنید" @error('biografie') is-invalid @enderror>{{ old('biografie', auth()->user()->biografie) }}</textarea>
                @error('biografie')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <input type="submit" value="ذخیره تغییرات" class="btn btn-primary" style="background-color: #518dbdcc !important;">
        </form>
    </div>
</div>

<form id="delete-document-form" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteDocument(index) {
        if (confirm('آیا از حذف این مدرک مطمئن هستید؟')) {
            const form = document.getElementById('delete-document-form');
            form.action = `/profile/document/${index}`; // یا route() در blade
            form.submit();
        }
    }
</script>
