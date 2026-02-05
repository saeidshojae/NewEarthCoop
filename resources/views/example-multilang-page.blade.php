<!-- 
    مثال: نحوه استفاده از سیستم چند زبانه در یک صفحه نمونه
    این فایل یک الگوی کامل برای ایجاد صفحات چند زبانه است
-->

@extends('layouts.app')

@section('title', __('langFront.home'))

@section('content')
<div class="container py-5">
    
    <!-- نمایش اطلاعات زبان فعلی -->
    <div class="alert alert-info mb-4">
        <h5>
            {{ get_locale_flag() }} 
            {{ __('langFront.welcome') }} - 
            {{ get_locale_name() }}
        </h5>
        <p class="mb-0">
            @if(is_rtl())
                این صفحه در حالت راست‌چین (RTL) نمایش داده می‌شود
            @else
                This page is displayed in Left-to-Right (LTR) mode
            @endif
        </p>
    </div>

    <!-- عنوان صفحه -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center">
                {{ __('langFront.home') }}
            </h1>
            <p class="text-center text-muted">
                {{ __('langFront.description') }}
            </p>
        </div>
    </div>

    <!-- کارت‌های نمونه با ترجمه -->
    <div class="row g-4">
        
        <!-- کارت 1: خدمات -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-briefcase {{ is_ltr() ? 'me-2' : 'ms-2' }}"></i>
                        {{ __('langFront.services') }}
                    </h5>
                    <p class="card-text">
                        {{ __('langFront.description') }}
                    </p>
                    <a href="#" class="btn btn-primary w-100">
                        {{ __('langFront.read_more') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- کارت 2: درباره ما -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-info-circle {{ is_ltr() ? 'me-2' : 'ms-2' }}"></i>
                        {{ __('langFront.about_us') }}
                    </h5>
                    <p class="card-text">
                        {{ __('langFront.description') }}
                    </p>
                    <a href="#" class="btn btn-primary w-100">
                        {{ __('langFront.read_more') }}
                    </a>
                </div>
            </div>
        </div>

        <!-- کارت 3: تماس با ما -->
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-envelope {{ is_ltr() ? 'me-2' : 'ms-2' }}"></i>
                        {{ __('langFront.contact') }}
                    </h5>
                    <p class="card-text">
                        {{ __('langFront.description') }}
                    </p>
                    <a href="#" class="btn btn-primary w-100">
                        {{ __('langFront.send') }}
                    </a>
                </div>
            </div>
        </div>

    </div>

    <!-- فرم نمونه با ترجمه -->
    <div class="row mt-5">
        <div class="col-md-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('langFront.contact_form') }}</h4>
                </div>
                <div class="card-body">
                    <form>
                        <!-- نام -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                {{ __('langFront.name') }}
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="name" 
                                placeholder="{{ __('langFront.name') }}"
                                dir="{{ get_direction() }}"
                            >
                        </div>

                        <!-- ایمیل -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                {{ __('langFront.email') }}
                            </label>
                            <input 
                                type="email" 
                                class="form-control" 
                                id="email" 
                                placeholder="{{ __('langFront.your_email_address') }}"
                                dir="ltr"
                            >
                        </div>

                        <!-- موضوع -->
                        <div class="mb-3">
                            <label for="subject" class="form-label">
                                {{ __('langFront.subject') }}
                            </label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="subject" 
                                placeholder="{{ __('langFront.subject') }}"
                                dir="{{ get_direction() }}"
                            >
                        </div>

                        <!-- پیام -->
                        <div class="mb-3">
                            <label for="message" class="form-label">
                                {{ __('langFront.text') }}
                            </label>
                            <textarea 
                                class="form-control" 
                                id="message" 
                                rows="5"
                                placeholder="{{ __('langFront.text') }}"
                                dir="{{ get_direction() }}"
                            ></textarea>
                        </div>

                        <!-- دکمه ارسال -->
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane {{ is_ltr() ? 'me-2' : 'ms-2' }}"></i>
                            {{ __('langFront.send') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول نمونه -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ __('langFront.recent_posts') }}</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('langFront.name') }}</th>
                                    <th>{{ __('langFront.email') }}</th>
                                    <th>{{ __('langFront.date') }}</th>
                                    <th>{{ __('langFront.operation') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        @if(app()->getLocale() == 'fa')
                                            علی احمدی
                                        @elseif(app()->getLocale() == 'en')
                                            Ali Ahmadi
                                        @else
                                            علي أحمدي
                                        @endif
                                    </td>
                                    <td dir="ltr" class="{{ is_rtl() ? 'text-end' : 'text-start' }}">
                                        ali@example.com
                                    </td>
                                    <td>2024-10-24</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">
                                            {{ __('langFront.view') }}
                                        </button>
                                        <button class="btn btn-sm btn-warning">
                                            {{ __('langPanel.edit') }}
                                        </button>
                                        <button class="btn btn-sm btn-danger">
                                            {{ __('langFront.delete') }}
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- نمایش متن شرطی بر اساس زبان -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="alert alert-success">
                <h5>
                    @switch(app()->getLocale())
                        @case('fa')
                            🇮🇷 شما در حال استفاده از نسخه فارسی هستید
                            @break
                        @case('en')
                            🇬🇧 You are using the English version
                            @break
                        @case('ar')
                            🇸🇦 أنت تستخدم النسخة العربية
                            @break
                        @default
                            Default language
                    @endswitch
                </h5>
                <p class="mb-0">
                    {{ __('langFront.mission_accomplished') }}
                </p>
            </div>
        </div>
    </div>

    <!-- استایل‌های داینامیک بر اساس جهت زبان -->
    <style>
        /* استایل‌های خاص برای RTL */
        @if(is_rtl())
        .custom-box {
            border-right: 4px solid #459f96;
            padding-right: 1rem;
        }
        @else
        /* استایل‌های خاص برای LTR */
        .custom-box {
            border-left: 4px solid #459f96;
            padding-left: 1rem;
        }
        @endif

        /* استایل یکسان */
        .custom-box {
            background: #f8f9fa;
            padding-top: 1rem;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.25rem;
        }
    </style>

    <!-- نمونه استفاده از استایل سفارشی -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="custom-box">
                <h5>{{ __('langFront.important_link') }}</h5>
                <p>
                    {{ __('langFront.description') }}
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // دسترسی به زبان فعلی در JavaScript
    const currentLocale = '{{ app()->getLocale() }}';
    const direction = '{{ get_direction() }}';
    const isRTL = {{ is_rtl() ? 'true' : 'false' }};

    // مثال: نمایش پیام با SweetAlert بر اساس زبان
    function showLocalizedAlert() {
        const messages = {
            fa: 'پیام شما با موفقیت ارسال شد',
            en: 'Your message was sent successfully',
            ar: 'تم إرسال رسالتك بنجاح'
        };

        Swal.fire({
            text: messages[currentLocale] || messages['fa'],
            icon: 'success',
            confirmButtonText: currentLocale === 'en' ? 'OK' : 'باشه'
        });
    }
</script>
@endsection
