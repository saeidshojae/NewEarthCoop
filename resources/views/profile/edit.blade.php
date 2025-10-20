@extends('layouts.app')

@section('title', 'ویرایش پروفایل')

@section('head-tag')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <style>
        .toggle-box {
            border: 2px solid #518476;
            border-radius: 8px;
            padding: 1rem;
            margin: 1rem 0;
            background-color: #fff
        }

        .toggle-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-weight: bold;
        }

        .toggle-content {
            display: none;
            margin-top: 1rem;
        }

        .toggle-icon {
            transition: transform 0.3s ease;
        }

        .open .toggle-icon {
            transform: rotate(180deg);
        }

        .open .toggle-content {
            display: block;
        }

        input, select, textarea {
            background-color: #fff !important;
        }
        .select2-selection__clear{
            display: none;
        }
        
        .btn:hover{
            background-color: #37c4b4 !important
        }

    </style>
@endsection

@section('content')
    <div class="container mt-5" style="direction: rtl; text-align: right;">
        <h1 class="mb-4 text-center">ویرایش اطلاعات پروفایل</h1>
                    <a class='btn' style='margin: 0' href='{{ url('/profile-member/' . auth()->user()->id) }}'>پیش نمایش پروفایل من</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- فرم اطلاعات کاربر --}}
        @include('profile.partials.general')

        {{-- فرم تغییر رمز --}}
        @include('profile.partials.password')

        {{-- فرم شبکه اجتماعی --}}
        @include('profile.partials.social')

        {{-- فرم انتخاب صنف و تخصص --}}
        @include('profile.partials.occupation')

        {{-- فرم موقعیت مکانی --}}
        @include('profile.partials.location')

        {{-- مدال‌ها --}}
        @include('partials.location_modals')
        @include('partials.add_location_modal', ['type' => 'region', 'label' => 'منطقه'])
        @include('partials.add_location_modal', ['type' => 'neighborhood', 'label' => 'محله'])
        @include('partials.add_location_modal', ['type' => 'street', 'label' => 'خیابان'])
        @include('partials.add_location_modal', ['type' => 'alley', 'label' => 'کوچه'])

        {{-- مدال برش تصویر --}}
        @include('profile.partials.cropper_modal')
    </div>
@endsection
@section('scripts')
<script>
  window.profileData = {
      oldOccupational: @json(old('occupational_fields', [])),
      oldExperience: @json(old('experience_fields', []))
  };
  
</script>
<script>
   oldOccupational = {!! json_encode(old('occupational_fields', [])) !!};
   oldExperience = {!! json_encode(old('experience_fields', [])) !!};
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js" defer></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js" defer></script>
<script>
    function toggleBox(element) {
        const box = element.closest('.toggle-box');
        const content = box.querySelector('.toggle-content');
        const icon = element.querySelector('.toggle-icon');

        if (content.style.display === 'none' || getComputedStyle(content).display === 'none') {
            content.style.display = 'block';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            content.style.display = 'none';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    }

    // اختیاری: بستن همه toggle-box ها در شروع
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-content').forEach(function (content) {
            content.style.display = 'none';
        });
    });
</script>

<script>

document.addEventListener('DOMContentLoaded', function () {
  
    // مدیریت باز و بسته شدن toggle-box ها
    document.querySelectorAll('.toggle-header').forEach(header => {
        header.addEventListener('click', function () {
            const parent = header.closest('.toggle-box');
            parent.classList.toggle('open');
        });
    });

    // فعال‌سازی select2
    $('.location-select, select').select2({
        width: '100%',
        placeholder: 'انتخاب کنید',
        allowClear: true
    });

    // فعال‌سازی تقویم شمسی
    $('#birth_date').persianDatepicker({
        initialValueType: 'gregorian',
        format: 'YYYY-MM-DD',
        autoClose: true,
        toolbox: {
            calendarSwitch: {
                enabled: false
            }
        }
    });
});
</script>


<script>
  window.userAddress = {
      continent_id: '{{ $user->address->continent_id }}',
      country_id: '{{ $user->address->country_id }}',
      province_id: '{{ $user->address->province_id }}',
      county_id: '{{ $user->address->county_id }}',
      section_id: '{{ $user->address->section_id }}',
      city_id: '{{ $user->address->city_id == null ? 'rural_' . $user->address->rural_id : 'city_' . $user->address->city_id }}',
      region_id: '{{ $user->address->region_id }}',
      neighborhood_id: '{{ $user->address->neighborhood_id }}',
      street_id: '{{ $user->address->street_id }}',
      alley_id: '{{ $user->address->alley_id }}'
  };

  </script>


<script src="{{ asset('js/register_step2.js') }}" defer></script>
<script src="{{ asset('js/edit_location.js') }}" defer></script>
<script src="{{ asset('js/cropper_avatar.js') }}" defer></script>
<script src="{{ asset('js/profile_forms.js') }}" defer></script>
@endsection
