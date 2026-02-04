# 🎨 برنامه یکپارچه‌سازی UI - بر اساس طراحی Home

## 📋 خلاصه

این سند برنامه عملی برای یکپارچه‌سازی تمام صفحات با استفاده از **طراحی صفحه Home** است که شامل:
- Header یکپارچه
- رنگ‌ها و فونت‌های یکسان
- استایل‌های یکپارچه
- Component های قابل استفاده مجدد

---

## 🎯 هدف

یکپارچه‌سازی تمام صفحات با استفاده از:
- ✅ طراحی صفحه `home.blade.php` به عنوان **base**
- ✅ Header یکپارچه برای همه صفحات
- ✅ رنگ‌ها، فونت‌ها و استایل‌های یکسان
- ✅ Component های قابل استفاده مجدد

---

## 📦 مرحله 1: ایجاد Layout یکپارچه جدید

### 1.1 ایجاد `layouts/unified.blade.php`

این Layout جدید بر اساس طراحی `home.blade.php` است و شامل:

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'New Earth Coop')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark Mode -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <!-- Unified Styles -->
    <link rel="stylesheet" href="{{ asset('Css/unified-styles.css') }}">
    
    @stack('styles')
</head>

<body class="font-vazirmatn leading-relaxed min-h-screen flex flex-col" 
      x-data="{ 
          mobileMenuOpen: false, 
          userDropdownOpen: false,
          sidebarOpen: false
      }">
    
    <!-- Unified Header -->
    @include('components.header-unified')
    
    <!-- Flash Messages -->
    @include('components.flash-messages')
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>
    
    <!-- Unified Footer -->
    @include('components.footer-unified')
    
    <!-- Scripts -->
    @stack('scripts')
    
    <!-- Najm Hoda Widget -->
    @if(config('najm-hoda.widget.enabled', true))
        @include('components.najm-hoda-widget')
    @endif
</body>
</html>
```

---

## 📦 مرحله 2: ایجاد Header Component یکپارچه

### 2.1 ایجاد `components/header-unified.blade.php`

این Component بر اساس Header صفحه `home.blade.php` است:

```blade
{{-- Unified Header Component - بر اساس طراحی home --}}
<header class="bg-pure-white shadow-md py-4 px-6 md:px-8 sticky top-0 z-50 transition-all duration-300" 
        style="background-color: var(--color-pure-white);">
    <div class="container mx-auto flex justify-between items-center">
        
        <!-- Logo Section -->
        <div class="flex items-center space-x-3 md:space-x-reverse rtl:space-x-reverse">
            @if(request()->routeIs('home'))
                {{-- بدون دکمه Back در صفحه Home --}}
            @else
                <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" 
                   class="text-gray-600 hover:text-green-600 transition-colors mr-3">
                    <i class="fa fa-arrow-left text-xl"></i>
                </a>
            @endif
            
            <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-animated">
                <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" fill="#10b981" opacity="0.8"/>
                <path d="M12 2C10.5 4 8 6 8 9C8 12 12 14 12 14C12 14 16 12 16 9C16 6 13.5 4 12 2ZM12 14C12 14 10 16 10 18C10 20 12 22 12 22" fill="#047857"/>
            </svg>
            
            <a href="{{ route('home') }}" class="text-2xl md:text-3xl font-extrabold text-gentle-black" 
               style="color: var(--color-gentle-black);">
                EarthCoop
            </a>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8 rtl:space-x-reverse text-gentle-black flex-grow justify-center" 
             style="color: var(--color-gentle-black);">
            
            <a href="{{ route('home') }}" 
               class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group flex items-center" 
               style="hover:color: var(--color-earth-green);">
                <i class="fas fa-home ml-2" style="color: var(--color-earth-green);"></i> 
                <span>خانه</span>
                <span class="absolute bottom-0 right-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" 
                      style="background-color: var(--color-earth-green);"></span>
            </a>
            
            @if(auth()->check())
                <a href="{{ route('blog.index') }}" 
                   class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group flex items-center">
                    <i class="fas fa-blog ml-2" style="color: var(--color-earth-green);"></i> 
                    <span>وبلاگ</span>
                    <span class="absolute bottom-0 right-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" 
                          style="background-color: var(--color-earth-green);"></span>
                </a>
                
                <a href="{{ route('stock.book') }}" 
                   class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group flex items-center">
                    <i class="fas fa-chart-line ml-2" style="color: var(--color-earth-green);"></i> 
                    <span>دفتر سهام</span>
                    <span class="absolute bottom-0 right-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" 
                          style="background-color: var(--color-earth-green);"></span>
                </a>
            @else
                <a href="{{ route('blog.index') }}" 
                   class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group flex items-center">
                    <i class="fas fa-blog ml-2" style="color: var(--color-earth-green);"></i> 
                    <span>وبلاگ</span>
                    <span class="absolute bottom-0 right-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" 
                          style="background-color: var(--color-earth-green);"></span>
                </a>
            @endif
            
            @foreach(\App\Models\Page::where('is_published', 1)->get() as $page)
                <a href="{{ url('/pages/' . $page->slug) }}" 
                   class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group flex items-center">
                    <i class="fas fa-file-alt ml-2" style="color: var(--color-earth-green);"></i> 
                    <span>{{ $page->title }}</span>
                    <span class="absolute bottom-0 right-0 w-0 h-0.5 group-hover:w-full transition-all duration-300" 
                          style="background-color: var(--color-earth-green);"></span>
                </a>
            @endforeach
        </nav>

        <!-- User Actions -->
        <div class="flex items-center gap-3">
            <!-- Mobile Menu Button -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" 
                    class="md:hidden text-gentle-black focus:outline-none" 
                    style="color: var(--color-gentle-black);">
                <i class="fas fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
                <i class="fas fa-times text-2xl" x-show="mobileMenuOpen" x-transition></i>
            </button>

            @auth
                <!-- User Dropdown -->
                @include('components.user-dropdown')
            @else
                <!-- Login/Register Buttons -->
                <a href="{{ route('login') }}" 
                   class="bg-earth-green text-pure-white px-4 py-2 rounded-full shadow-md hover:bg-dark-green transition duration-300 font-medium transform hover:scale-105">
                    ورود
                </a>
                <a href="{{ route('register.form') }}" 
                   class="bg-ocean-blue text-pure-white px-4 py-2 rounded-full shadow-md hover:bg-dark-blue transition duration-300 font-medium transform hover:scale-105">
                    ثبت‌نام
                </a>
            @endauth
        </div>
    </div>
    
    <!-- Mobile Menu -->
    @include('components.mobile-menu')
</header>
```

---

## 📦 مرحله 3: ایجاد فایل CSS یکپارچه

### 3.1 ایجاد `public/Css/unified-styles.css`

این فایل شامل تمام استایل‌های یکپارچه است:

```css
/**
 * Unified Styles - بر اساس طراحی صفحه Home
 * این فایل شامل تمام استایل‌های یکپارچه برای تمام صفحات است
 */

/* ==================== CSS Variables ==================== */
:root {
    --color-earth-green: #10b981;
    --color-ocean-blue: #3b82f6;
    --color-digital-gold: #f59e0b;
    --color-pure-white: #ffffff;
    --color-light-gray: #f8fafc;
    --color-gentle-black: #1e293b;
    --color-dark-green: #047857;
    --color-dark-blue: #1d4ed8;
    --color-red-tomato: #FF6347;
}

/* ==================== Font Setup ==================== */
* {
    font-family: 'Vazirmatn', 'Poppins', sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);
    min-height: 100vh;
}

/* ==================== Animations ==================== */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes bounce-custom {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: .7; }
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.logo-animated {
    animation: bounce-custom 3s infinite ease-in-out;
}

.badge-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.blinking-item {
    animation: blink 2s ease-in-out infinite;
}

/* ==================== Ripple Effect ==================== */
.ripple {
    position: relative;
    overflow: hidden;
}

.ripple::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

.ripple:active::after {
    width: 300px;
    height: 300px;
}

/* ==================== Scrollbar ==================== */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #10b981;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #059669;
}

/* ==================== Dark Mode Support ==================== */
body.dark-mode {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

body.dark-mode .bg-pure-white {
    background-color: #2d2d2d !important;
}

body.dark-mode .text-gentle-black {
    color: #e0e0e0 !important;
}
```

---

## 📋 مرحله 4: برنامه مهاجرت صفحات

### 4.1 اولویت‌بندی صفحات

#### اولویت 1 (صفحات پرکاربرد):
1. ✅ `/groups/index` - لیست گروه‌ها
2. ✅ `/groups/show` - نمایش گروه
3. ✅ `/groups/chat` - چت گروهی
4. ✅ `/profile/profile` - پروفایل کاربر
5. ✅ `/notifications` - اعلان‌ها

#### اولویت 2 (صفحات مهم):
1. ✅ `/auth/*` - صفحات احراز هویت
2. ✅ `/admin/*` - پنل ادمین
3. ✅ `/blog/*` - ماژول وبلاگ
4. ✅ `/auctions/*` - ماژول سهام

#### اولویت 3 (سایر صفحات):
1. ✅ تمام صفحات باقیمانده

---

### 4.2 مراحل مهاجرت هر صفحه

برای هر صفحه، این مراحل را انجام دهید:

#### مرحله 1: تغییر Layout
```php
// قبل:
@extends('layouts.app')

// بعد:
@extends('layouts.unified')

@section('title', 'عنوان صفحه')
```

#### مرحله 2: حذف Header و Footer محلی
```php
// اگر صفحه Header خودش دارد، حذف کنید:
// <header>...</header>  ← حذف شود

// اگر صفحه Footer خودش دارد، حذف کنید:
// <footer>...</footer>  ← حذف شود
```

#### مرحله 3: حذف استایل‌های inline
```php
// استایل‌های inline را به فایل CSS منتقل کنید:
// <style>...</style>  ← حذف شود یا به @push('styles') منتقل شود
```

#### مرحله 4: استفاده از متغیرهای CSS
```php
// قبل:
style="background-color: #10b981;"

// بعد:
style="background-color: var(--color-earth-green);"
```

---

## 🛠️ مرحله 5: ایجاد Component های کمکی

### 5.1 User Dropdown Component

`components/user-dropdown.blade.php`:

```blade
<div class="relative">
    <button @click="userDropdownOpen = !userDropdownOpen" 
            class="px-4 py-2 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105 flex items-center ripple" 
            style="background-color: var(--color-earth-green); color: var(--color-pure-white);">
        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white text-lg ml-2">
            <i class="fas fa-user"></i>
        </div>
        <span class="hidden sm:inline">{{ Auth::user()->fullName() }}</span>
        <i class="fas fa-chevron-down mr-2 text-sm transition-transform duration-300" 
           :class="{ 'rotate-180': userDropdownOpen }"></i>
    </button>
    
    <div x-show="userDropdownOpen" 
         @click.away="userDropdownOpen = false" 
         x-transition
         class="absolute left-0 mt-2 w-56 bg-white rounded-lg shadow-xl py-2 z-50 text-right origin-top-left"
         style="display: none; background-color: var(--color-pure-white);">
        
        <a href="{{ route('profile.show') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-earth-green);">
            پروفایل <i class="fas fa-user-circle mr-3"></i>
        </a>
        
        <hr class="my-1 border-gray-200">
        
        <h6 class="px-4 py-2 text-sm font-bold" style="color: var(--color-ocean-blue);">
            دفتر سهام
        </h6>
        
        <a href="{{ route('auction.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            حراج‌های سهام <i class="fas fa-gavel mr-3"></i>
        </a>
        
        <a href="{{ route('wallet.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            کیف‌پول <i class="fas fa-wallet mr-3"></i>
        </a>
        
        <a href="{{ route('holding.index') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            کیف‌سهام <i class="fas fa-chart-line mr-3"></i>
        </a>
        
        <hr class="my-1 border-gray-200">
        
        <a href="{{ route('terms') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start" 
           style="color: var(--color-gentle-black);">
            اساسنامه <i class="fas fa-file-alt mr-3"></i>
        </a>
        
        <a href="{{ route('logout') }}" 
           class="block px-4 py-2 hover:bg-gray-50 transition duration-200 flex items-center justify-start text-red-600">
            خروج <i class="fas fa-sign-out-alt mr-3"></i>
        </a>
    </div>
</div>
```

---

## 📝 مرحله 6: چک‌لیست برای هر صفحه

برای هر صفحه که مهاجرت می‌کنید، این موارد را بررسی کنید:

- [ ] Layout به `layouts.unified` تغییر یافته است
- [ ] Header محلی حذف شده و از component استفاده می‌شود
- [ ] Footer محلی حذف شده و از component استفاده می‌شود
- [ ] استایل‌های inline حذف یا منتقل شده‌اند
- [ ] از متغیرهای CSS استفاده می‌شود
- [ ] Dark Mode درست کار می‌کند
- [ ] Responsive درست است
- [ ] تست شده است

---

## 🚀 اجرای برنامه

### گام 1: ایجاد فایل‌های پایه
1. ✅ ایجاد `layouts/unified.blade.php`
2. ✅ ایجاد `components/header-unified.blade.php`
3. ✅ ایجاد `components/user-dropdown.blade.php`
4. ✅ ایجاد `components/mobile-menu.blade.php`
5. ✅ ایجاد `public/Css/unified-styles.css`

### گام 2: مهاجرت صفحات اولویت 1
1. ✅ مهاجرت `/groups/index`
2. ✅ مهاجرت `/groups/show`
3. ✅ مهاجرت `/groups/chat`
4. ✅ مهاجرت `/profile/profile`
5. ✅ مهاجرت `/notifications`

### گام 3: مهاجرت صفحات اولویت 2
1. ✅ مهاجرت صفحات احراز هویت
2. ✅ مهاجرت پنل ادمین
3. ✅ مهاجرت ماژول وبلاگ
4. ✅ مهاجرت ماژول سهام

### گام 4: مهاجرت صفحات باقیمانده
1. ✅ مهاجرت تمام صفحات دیگر

---

## 📊 نتیجه

بعد از پیاده‌سازی این برنامه:

- ✅ تمام صفحات از یک Layout یکپارچه استفاده می‌کنند
- ✅ تمام صفحات Header یکپارچه دارند
- ✅ تمام صفحات از رنگ‌ها و فونت‌های یکسان استفاده می‌کنند
- ✅ Dark Mode در تمام صفحات یکپارچه کار می‌کند
- ✅ Responsive Design یکپارچه است
- ✅ نگهداری و توسعه آسان‌تر است

---

**آماده برای شروع!** 🚀



