# 🔄 مثال عملی: مهاجرت صفحه Profile

این فایل یک مثال عملی از نحوه مهاجرت صفحات قدیمی به سیستم جدید است.

---

## 📝 مثال 1: صفحه Profile (ساده)

### ❌ قبل (استفاده از layouts.app):

```php
@extends('layouts.app')

@section('head-tag')
<style>
    .custom-style {
        color: #459f96;
    }
</style>
@endsection

@section('content')
<div class="container">
    <h1>پروفایل کاربر</h1>
    <div class="card">
        <!-- محتوا -->
    </div>
</div>
@endsection
```

### ✅ بعد (استفاده از layouts.master):

```php
@extends('layouts.master')

@section('title', 'پروفایل کاربر')

{{-- استایل‌های سفارشی --}}
@push('styles')
<style>
    .custom-style {
        color: var(--color-primary); /* استفاده از متغیر */
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="heading-xl text-earth-green mb-6">پروفایل کاربر</h1>
    <div class="card-new">
        <!-- محتوا -->
    </div>
</div>
@endsection
```

---

## 📝 مثال 2: صفحه با Sidebar

### ❌ قبل:

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="sidebar">
                <!-- منوی کناری -->
            </div>
        </div>
        <div class="col-md-9">
            <div class="content">
                <!-- محتوای اصلی -->
            </div>
        </div>
    </div>
</div>
@endsection
```

### ✅ بعد:

```php
@extends('layouts.master')

@section('title', 'داشبورد')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Sidebar --}}
        <aside class="lg:col-span-3">
            <div class="card-new sticky top-24">
                {{-- منوی کناری --}}
                <nav class="space-y-2">
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-user"></i>
                        <span>پروفایل</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        <i class="fas fa-cog"></i>
                        <span>تنظیمات</span>
                    </a>
                </nav>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <main class="lg:col-span-9">
            <div class="card-new">
                {{-- محتوای اصلی --}}
            </div>
        </main>
    </div>
</div>
@endsection
```

---

## 📝 مثال 3: صفحه با جدول

### ❌ قبل:

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>نام</th>
                <th>ایمیل</th>
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <a href="#" class="btn btn-primary">ویرایش</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
```

### ✅ بعد (با طراحی مدرن):

```php
@extends('layouts.master')

@section('title', 'لیست کاربران')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="heading-lg text-earth-green">لیست کاربران</h1>
        <button class="btn-primary-new">
            <i class="fas fa-plus ml-2"></i>
            افزودن کاربر
        </button>
    </div>
    
    {{-- جدول مدرن --}}
    <div class="card-new overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            نام
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            ایمیل
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            عملیات
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-earth-green text-white flex items-center justify-center font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="mr-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $user->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="#" class="text-ocean-blue hover:text-dark-blue ml-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="text-red-600 hover:text-red-900">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
```

---

## 📝 مثال 4: صفحه با فرم

### ❌ قبل:

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <form method="POST">
        @csrf
        <div class="form-group">
            <label>نام</label>
            <input type="text" class="form-control" name="name">
        </div>
        <button type="submit" class="btn btn-primary">ذخیره</button>
    </form>
</div>
@endsection
```

### ✅ بعد:

```php
@extends('layouts.master')

@section('title', 'ویرایش پروفایل')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="card-new">
        <h2 class="heading-md text-earth-green mb-6">ویرایش پروفایل</h2>
        
        <form method="POST" class="space-y-6">
            @csrf
            
            {{-- فیلد نام --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    نام
                </label>
                <input 
                    type="text" 
                    name="name" 
                    class="input-new @error('name') border-red-500 @enderror"
                    value="{{ old('name', $user->name) }}"
                    placeholder="نام خود را وارد کنید"
                >
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- فیلد ایمیل --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    ایمیل
                </label>
                <input 
                    type="email" 
                    name="email" 
                    class="input-new @error('email') border-red-500 @enderror"
                    value="{{ old('email', $user->email) }}"
                    placeholder="email@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            {{-- دکمه‌ها --}}
            <div class="flex gap-3">
                <button type="submit" class="btn-primary-new flex-1">
                    <i class="fas fa-save ml-2"></i>
                    ذخیره تغییرات
                </button>
                <a href="{{ route('profile.show') }}" class="btn-outline-new flex-1 text-center">
                    <i class="fas fa-times ml-2"></i>
                    انصراف
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
```

---

## 📝 مثال 5: تبدیل طرح HTML به Blade

فرض کنید شما این HTML در `New ui/form.html` دارید:

### 📄 HTML اصلی:

```html
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>فرم نمونه</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-green-600 mb-4">فرم نمونه</h1>
            <form>
                <input type="text" class="w-full p-3 border rounded">
                <button class="bg-green-500 text-white px-6 py-3 rounded">ارسال</button>
            </form>
        </div>
    </div>
</body>
</html>
```

### ✅ Blade تبدیل شده:

```php
@extends('layouts.master')

@section('title', 'فرم نمونه')

@section('content')
    {{-- HTML اصلی را فقط از داخل body کپی کنید --}}
    <div class="container mx-auto px-4 py-8">
        <div class="card-new"> {{-- از card-new استفاده کنید --}}
            <h1 class="heading-lg text-earth-green mb-4">فرم نمونه</h1>
            <form method="POST" action="{{ route('form.submit') }}">
                @csrf
                <input 
                    type="text" 
                    name="field" 
                    class="input-new" {{-- از input-new استفاده کنید --}}
                >
                <button type="submit" class="btn-primary-new mt-4">
                    ارسال
                </button>
            </form>
        </div>
    </div>
@endsection
```

---

## 🎯 نکات کلیدی

### 1. جایگزینی کلاس‌ها

| قدیمی (Bootstrap) | جدید (Design System) |
|---|---|
| `class="btn btn-primary"` | `class="btn-primary-new"` |
| `class="card"` | `class="card-new"` |
| `class="form-control"` | `class="input-new"` |
| `class="container"` | `class="container mx-auto px-4"` |

### 2. رنگ‌ها

| قدیمی | جدید |
|---|---|
| `style="color: #459f96"` | `class="text-earth-green"` |
| `style="background: #3b82f6"` | `class="bg-ocean-blue"` |

### 3. Dark Mode

```html
<!-- همیشه دو حالت را در نظر بگیرید -->
<div class="bg-white dark:bg-gray-800 text-black dark:text-white">
```

---

## ✅ چک‌لیست مهاجرت هر صفحه

- [ ] `@extends('layouts.app')` → `@extends('layouts.master')`
- [ ] افزودن `@section('title', '...')`
- [ ] `@section('head-tag')` → `@push('styles')`
- [ ] استفاده از کلاس‌های design-system
- [ ] افزودن Dark Mode support
- [ ] تست در Desktop
- [ ] تست در Mobile
- [ ] تست Dark Mode

---

**💡 نکته:** برای هر صفحه‌ای که مهاجرت می‌دهید، این مثال‌ها را به عنوان راهنما استفاده کنید!
