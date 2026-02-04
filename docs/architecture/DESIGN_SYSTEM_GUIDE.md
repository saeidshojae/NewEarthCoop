# 🎨 راهنمای سیستم طراحی یکپارچه New Earth Coop

## 📋 فهرست مطالب

1. [معرفی](#معرفی)
2. [ساختار فایل‌ها](#ساختار-فایلها)
3. [نحوه استفاده](#نحوه-استفاده)
4. [رنگ‌های سیستم](#رنگهای-سیستم)
5. [Components](#components)
6. [Dark Mode](#dark-mode)
7. [مهاجرت صفحات قدیمی](#مهاجرت-صفحات-قدیمی)

---

## 🌟 معرفی

این سیستم طراحی یکپارچه برای یکدست‌سازی ظاهر تمام صفحات سایت New Earth Coop ایجاد شده است. با استفاده از این سیستم:

- ✅ تمام صفحات از یک طراحی واحد استفاده می‌کنند
- ✅ Dark Mode در همه جا یکپارچه کار می‌کند
- ✅ Navbar و Footer در همه صفحات یکسان هستند
- ✅ هم Tailwind CSS و هم Bootstrap پشتیبانی می‌شوند

---

## 📁 ساختار فایل‌ها

```
project/
├── public/
│   ├── Css/
│   │   ├── design-system.css      # متغیرها، رنگ‌ها، فونت‌ها و استایل‌های مشترک
│   │   └── dark-mode.css          # استایل‌های Dark Mode (گسترش یافته)
│   └── js/
│       └── dark-mode.js           # اسکریپت Dark Mode (بهبود یافته)
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php      # Layout قدیمی (برای سازگاری)
│       │   └── master.blade.php   # ✨ Layout جدید یکپارچه
│       └── components/
│           ├── navbar.blade.php          # ✨ Navbar یکپارچه
│           └── footer-universal.blade.php # ✨ Footer یکپارچه
```

---

## 🚀 نحوه استفاده

### ✅ برای صفحات جدید

```php
@extends('layouts.master')

@section('title', 'عنوان صفحه')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="heading-xl text-earth-green">عنوان اصلی</h1>
        <p class="text-gray-700 dark:text-gray-300">متن شما...</p>
    </div>
@endsection
```

### ✅ مهاجرت صفحات قدیمی

برای تبدیل صفحات قدیمی که از `@extends('layouts.app')` استفاده می‌کنند:

**قبل:**
```php
@extends('layouts.app')

@section('content')
    <!-- محتوا -->
@endsection
```

**بعد:**
```php
@extends('layouts.master')

@section('title', 'عنوان صفحه')

@section('content')
    <!-- همان محتوا - بدون تغییر! -->
@endsection
```

---

## 🎨 رنگ‌های سیستم

### رنگ‌های اصلی برند

```css
--color-earth-green: #10b981   /* سبز زمین */
--color-ocean-blue: #3b82f6    /* آبی اقیانوس */
--color-digital-gold: #f59e0b  /* طلایی دیجیتال */
```

### نحوه استفاده

#### با Tailwind CSS:
```html
<div class="bg-earth-green text-pure-white">
    <h1 class="text-ocean-blue">عنوان</h1>
</div>
```

#### با CSS سفارشی:
```css
.my-element {
    background-color: var(--color-earth-green);
    color: var(--color-pure-white);
}
```

### کلاس‌های Utility آماده

```html
<!-- پس‌زمینه -->
<div class="bg-earth-green">سبز</div>
<div class="bg-ocean-blue">آبی</div>
<div class="bg-digital-gold">طلایی</div>

<!-- متن -->
<p class="text-earth-green">متن سبز</p>
<p class="text-ocean-blue">متن آبی</p>
<p class="text-digital-gold">متن طلایی</p>

<!-- گرادینت‌ها -->
<div class="gradient-bg-primary">گرادینت سبز-آبی</div>
<div class="gradient-text">متن گرادینت‌دار</div>
```

---

## 🧩 Components

### 1️⃣ Navbar

Navbar به صورت خودکار در `layouts/master.blade.php` include شده است.

**ویژگی‌ها:**
- ✅ نمایش متفاوت برای کاربر مهمان و لاگین شده
- ✅ منوی موبایل Responsive
- ✅ تغییر زبان
- ✅ دکمه Dark Mode
- ✅ Dropdown برای کاربر لاگین شده

### 2️⃣ Footer

Footer هم به صورت خودکار include می‌شود.

**ویژگی‌ها:**
- ✅ 4 ستون اطلاعات
- ✅ لینک‌های شبکه‌های اجتماعی
- ✅ Copyright info
- ✅ Dark Mode Support

### 3️⃣ استفاده دستی (در صورت نیاز)

```php
<!-- در هر صفحه‌ای که نیاز دارید -->
@include('components.navbar')

<!-- محتوای شما -->

@include('components.footer-universal')
```

---

## 🌙 Dark Mode

### نحوه عملکرد

Dark Mode به صورت خودکار در تمام صفحات فعال است و:

1. **ذخیره می‌شود** - ترجیح کاربر در localStorage ذخیره می‌شود
2. **سریع اعمال می‌شود** - بدون فلش سفید در بارگذاری صفحه
3. **همه‌جا یکسان است** - در تمام صفحات به یک شکل کار می‌کند

### کنترل برنامه‌نویسی

```javascript
// دریافت تم فعلی
const currentTheme = getCurrentTheme(); // 'light' یا 'dark'

// تنظیم تم
setTheme('dark'); // یا 'light'

// Toggle
toggleTheme();

// گوش دادن به تغییر تم
window.addEventListener('themeChanged', function(e) {
    console.log('New theme:', e.detail.theme);
    console.log('Is dark:', e.detail.isDark);
});
```

### استایل‌دهی سفارشی

```css
/* حالت روشن */
.my-element {
    background: white;
    color: black;
}

/* حالت تاریک */
body.dark-mode .my-element {
    background: #2d2d2d;
    color: white;
}
```

یا با Tailwind:

```html
<div class="bg-white dark:bg-gray-800 text-black dark:text-white">
    محتوا
</div>
```

---

## 🔄 مهاجرت صفحات قدیمی

### مرحله 1: شناسایی صفحات

صفحاتی که از `@extends('layouts.app')` استفاده می‌کنند باید مهاجرت داده شوند:

```bash
# لیست صفحات
- resources/views/profile/profile.blade.php
- resources/views/groups/show.blade.php
- resources/views/auction/*.blade.php
- resources/views/wallet/*.blade.php
- و غیره...
```

### مرحله 2: تغییر Layout

```php
<!-- تغییر این خط -->
@extends('layouts.app')

<!-- به این -->
@extends('layouts.master')
```

### مرحله 3: افزودن Title

```php
@section('title', 'عنوان صفحه شما')
```

### مرحله 4: بررسی استایل‌ها

اگر صفحه استایل‌های سفارشی دارد:

```php
@push('styles')
<style>
    /* استایل‌های سفارشی */
</style>
@endpush
```

### مرحله 5: تست

- ✅ بررسی نمایش صحیح صفحه
- ✅ تست Dark Mode
- ✅ تست در موبایل
- ✅ بررسی Navbar و Footer

---

## 🎯 نکات مهم

### ✅ DO - انجام دهید

```php
<!-- از متغیرهای CSS استفاده کنید -->
<div style="background: var(--color-earth-green)">

<!-- از کلاس‌های آماده استفاده کنید -->
<button class="btn-primary-new">کلیک کنید</button>

<!-- Dark Mode را در نظر بگیرید -->
<div class="bg-white dark:bg-gray-800">
```

### ❌ DON'T - انجام ندهید

```php
<!-- رنگ‌های هارد کد نکنید -->
<div style="background: #10b981"> <!-- ❌ -->

<!-- استایل‌های inline زیاد ننویسید -->
<div style="padding: 20px; margin: 10px; ..."> <!-- ❌ -->

<!-- فونت‌های جدید اضافه نکنید -->
<link href="google-fonts/new-font"> <!-- ❌ -->
```

---

## 📦 استفاده از طرح‌های HTML آماده

اگر طرح HTML/CSS آماده برای صفحه‌ای دارید:

### 1. استخراج HTML

```html
<!-- از body طرح HTML خود -->
<div class="container">
    <!-- محتوای طرح -->
</div>
```

### 2. قرار دادن در Blade

```php
@extends('layouts.master')

@section('title', 'عنوان صفحه')

@section('content')
    <!-- همان HTML طرح شما -->
    <div class="container">
        <!-- محتوا -->
    </div>
@endsection
```

### 3. اضافه کردن استایل‌ها

```php
@push('styles')
<style>
    /* استایل‌های خاص این صفحه از طرح HTML */
</style>
@endpush
```

---

## 🛠 Troubleshooting

### مشکل: Dark Mode کار نمی‌کند

```php
<!-- مطمئن شوید این فایل‌ها لود شده‌اند -->
<link rel="stylesheet" href="{{ asset('Css/dark-mode.css') }}">
<script src="{{ asset('js/dark-mode.js') }}"></script>
```

### مشکل: رنگ‌ها درست نیستند

```php
<!-- مطمئن شوید design-system.css لود شده -->
<link rel="stylesheet" href="{{ asset('Css/design-system.css') }}">
```

### مشکل: Navbar نمایش داده نمی‌شود

```php
<!-- بررسی کنید که از master layout استفاده می‌کنید -->
@extends('layouts.master')
```

---

## 📞 پشتیبانی

برای سوالات یا مشکلات:

1. این فایل را با دقت بخوانید
2. فایل‌های مثال را بررسی کنید:
   - `resources/views/welcome.blade.php`
   - `resources/views/home.blade.php`
3. کد را با دقت کپی کنید

---

**ساخته شده با ❤️ برای New Earth Coop**
