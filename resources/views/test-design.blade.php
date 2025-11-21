@extends('layouts.app')

@section('title', 'تست طراحی جدید - Layout قدیمی')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-12">
            <div class="card-new mb-4">
                <h1 class="heading-xl text-earth-green mb-4">
                    🎨 تست سیستم طراحی یکپارچه
                </h1>
                <p class="text-lg text-gray-700 dark:text-gray-300">
                    این صفحه از <code>@extends('layouts.app')</code> استفاده می‌کند ولی با سیستم طراحی جدید!
                </p>
            </div>
        </div>
    </div>

    <!-- تست رنگ‌ها -->
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="heading-md mb-3">تست رنگ‌های جدید</h2>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-earth-green text-white p-4 rounded text-center">
                <i class="fas fa-leaf fa-2x mb-2"></i>
                <h4>سبز زمین</h4>
                <code class="text-white">#10b981</code>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-ocean-blue text-white p-4 rounded text-center">
                <i class="fas fa-water fa-2x mb-2"></i>
                <h4>آبی اقیانوس</h4>
                <code class="text-white">#3b82f6</code>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="bg-digital-gold text-white p-4 rounded text-center">
                <i class="fas fa-coins fa-2x mb-2"></i>
                <h4>طلایی دیجیتال</h4>
                <code class="text-white">#f59e0b</code>
            </div>
        </div>
    </div>

    <!-- تست فونت -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-new">
                <h2 class="heading-md mb-3">تست فونت یکپارچه</h2>
                <p class="text-lg font-vazirmatn mb-2">
                    این متن با فونت <strong>Vazirmatn</strong> نمایش داده می‌شود (فارسی)
                </p>
                <p class="text-lg font-poppins mb-2">
                    This text is displayed with <strong>Poppins</strong> font (English)
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    123456789 - اعداد به صورت واضح و یکسان نمایش داده می‌شوند
                </p>
            </div>
        </div>
    </div>

    <!-- تست دکمه‌ها -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-new">
                <h2 class="heading-md mb-4">تست دکمه‌های جدید</h2>
                <div class="d-flex flex-wrap gap-3">
                    <button class="btn-primary-new">
                        <i class="fas fa-check me-2"></i>
                        دکمه اصلی
                    </button>
                    <button class="btn-secondary-new">
                        <i class="fas fa-info me-2"></i>
                        دکمه ثانویه
                    </button>
                    <button class="btn-outline-new">
                        <i class="fas fa-edit me-2"></i>
                        دکمه Outline
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- تست کارت‌ها -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card-new card-hover">
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-earth-green rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-users text-white fa-2x"></i>
                    </div>
                </div>
                <h4 class="text-lg font-bold text-center mb-2">تعداد کاربران</h4>
                <p class="text-3xl font-bold text-earth-green text-center">1,234</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-new card-hover">
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-ocean-blue rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-project-diagram text-white fa-2x"></i>
                    </div>
                </div>
                <h4 class="text-lg font-bold text-center mb-2">پروژه‌های فعال</h4>
                <p class="text-3xl font-bold text-ocean-blue text-center">56</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card-new card-hover">
                <div class="text-center mb-3">
                    <div class="w-16 h-16 bg-digital-gold rounded-full flex items-center justify-center mx-auto">
                        <i class="fas fa-chart-line text-white fa-2x"></i>
                    </div>
                </div>
                <h4 class="text-lg font-bold text-center mb-2">رشد ماهانه</h4>
                <p class="text-3xl font-bold text-digital-gold text-center">23%</p>
            </div>
        </div>
    </div>

    <!-- تست Input -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card-new">
                <h2 class="heading-md mb-4">تست فیلدهای فرم</h2>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-2">نام شما</label>
                    <input type="text" class="input-new" placeholder="نام خود را وارد کنید">
                </div>
                <div class="mb-3">
                    <label class="block text-sm font-medium mb-2">ایمیل</label>
                    <input type="email" class="input-new" placeholder="email@example.com">
                </div>
                <button class="btn-primary-new w-100">
                    <i class="fas fa-paper-plane me-2"></i>
                    ارسال
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card-new">
                <h2 class="heading-md mb-4">تست Badge ها</h2>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    <span class="badge-new badge-success">
                        <i class="fas fa-check me-1"></i>
                        فعال
                    </span>
                    <span class="badge-new badge-info">
                        <i class="fas fa-info me-1"></i>
                        اطلاعات
                    </span>
                    <span class="badge-new badge-warning">
                        <i class="fas fa-exclamation me-1"></i>
                        هشدار
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Badge های جدید با رنگ‌های سیستم طراحی
                </p>
            </div>
        </div>
    </div>

    <!-- تست Dark Mode -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-new text-center">
                <h2 class="heading-md mb-4">تست Dark Mode یکپارچه</h2>
                <p class="mb-4 text-gray-700 dark:text-gray-300">
                    تم فعلی: <strong id="current-theme-display" class="text-earth-green"></strong>
                </p>
                <button onclick="toggleTheme()" class="btn-primary-new mx-auto">
                    <i class="fas fa-moon me-2"></i>
                    تغییر تم
                </button>
                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                    همه عناصر به صورت خودکار با Dark Mode سازگار هستند
                </p>
            </div>
        </div>
    </div>

    <!-- انیمیشن‌ها -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-new">
                <h2 class="heading-md mb-4">انیمیشن‌های آماده</h2>
                <div class="d-flex flex-wrap gap-4 justify-content-center">
                    <div class="text-center">
                        <div class="bg-earth-green text-white p-4 rounded-lg animate-float" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-rocket fa-2x"></i>
                        </div>
                        <p class="mt-2 text-sm">Float</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-digital-gold text-white p-4 rounded-lg animate-glow" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <p class="mt-2 text-sm">Glow</p>
                    </div>
                    <div class="text-center">
                        <div class="bg-ocean-blue text-white p-4 rounded-lg animate-pulse-light" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-heart fa-2x"></i>
                        </div>
                        <p class="mt-2 text-sm">Pulse</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- راهنما -->
    <div class="row">
        <div class="col-12">
            <div class="card-new bg-blue-50 dark:bg-blue-900 border-2 border-ocean-blue">
                <h3 class="text-lg font-bold mb-3 text-ocean-blue">
                    <i class="fas fa-info-circle me-2"></i>
                    راهنما
                </h3>
                <ul class="list-unstyled space-y-2">
                    <li>✅ این صفحه از <code>layouts.app</code> استفاده می‌کند</li>
                    <li>✅ فونت‌ها یکسان شده‌اند (Vazirmatn + Poppins)</li>
                    <li>✅ رنگ‌ها از سیستم طراحی جدید هستند</li>
                    <li>✅ Dark Mode به صورت یکپارچه کار می‌کند</li>
                    <li>✅ تمام کامپوننت‌ها آماده استفاده هستند</li>
                </ul>
                <hr class="my-3">
                <p class="text-sm mb-0">
                    <strong>نکته:</strong> برای دیدن تفاوت، دکمه تغییر تم را در navbar یا این صفحه امتحان کنید! 🌙
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    // نمایش تم فعلی
    function updateThemeDisplay() {
        const theme = getCurrentTheme();
        document.getElementById('current-theme-display').textContent = theme === 'dark' ? 'تاریک 🌙' : 'روشن ☀️';
    }
    
    // به‌روزرسانی اولیه
    updateThemeDisplay();
    
    // گوش دادن به تغییر تم
    window.addEventListener('themeChanged', updateThemeDisplay);
</script>

@push('styles')
<style>
    /* استایل‌های اضافی برای این صفحه */
    .gap-3 {
        gap: 1rem;
    }
    
    .gap-2 {
        gap: 0.5rem;
    }
    
    .w-16 {
        width: 4rem;
    }
    
    .h-16 {
        height: 4rem;
    }
    
    .mx-auto {
        margin-left: auto;
        margin-right: auto;
    }
    
    .space-y-2 > * + * {
        margin-top: 0.5rem;
    }
</style>
@endpush
@endsection
