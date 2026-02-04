# 🌍 راهنمای سیستم چند زبانه صفحه Welcome

## ✅ کارهای انجام شده

### 1. **فایل‌های ترجمه ایجاد شد**
```
lang/
├── fa/langWelcome.php  ✅ (فارسی)
├── en/langWelcome.php  ✅ (انگلیسی)
└── ar/langWelcome.php  ✅ (عربی)
```

### 2. **تغییرات در `welcome.blade.php`**
- ✅ تگ `<html>` با `lang` و `dir` داینامیک
- ✅ تمام متن‌های navigation به `__('langWelcome.xxx')` تبدیل شد
- ✅ Language Switcher در navbar (Desktop & Mobile)
- ✅ تمام متن‌های مودال ترجمه شدند
- ✅ دکمه‌ها و لینک‌ها با ترجمه
- ✅ جهت فلش‌ها بر اساس RTL/LTR

### 3. **Language Switcher**
#### Desktop:
- دکمه با پرچم کشور فعلی
- Dropdown با 3 زبان
- نمایش زبان فعلی با background سبز

#### Mobile:
- 3 پرچم در کنار هم
- زبان فعلی با background سبز

---

## 📖 نحوه استفاده

### تغییر زبان
کاربر می‌تواند از header روی دکمه زبان (پرچم) کلیک کند و زبان مورد نظر را انتخاب کند.

### ترجمه‌های موجود

#### Navigation
```php
{{ __('langWelcome.nav_home') }}        // خانه / Home / الرئيسية
{{ __('langWelcome.nav_about') }}       // درباره ارث کوپ
{{ __('langWelcome.nav_guide') }}       // راهنما
{{ __('langWelcome.nav_projects') }}    // پروژه‌ها
{{ __('langWelcome.nav_stories') }}     // داستان‌ها
```

#### Buttons
```php
{{ __('langWelcome.btn_join') }}        // عضویت / Join / انضمام
{{ __('langWelcome.btn_login') }}       // ورود / Login
{{ __('langWelcome.btn_invite') }}      // دعوت / Invite
```

#### Modal
```php
{{ __('langWelcome.modal_welcome_title') }}
{{ __('langWelcome.modal_invite_code') }}
{{ __('langWelcome.modal_terms_agree') }}
```

---

## 🎨 ویژگی‌های RTL/LTR

### جهت خودکار
```php
// HTML tag
<html dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">

// در کد
{{ is_rtl() ? 'right' : 'left' }}
{{ is_ltr() ? 'ml' : 'mr' }}  // Margin
```

### فلش‌های داینامیک
```php
<i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }}"></i>
```

### Tailwind Classes داینامیک
```php
// Border
border-{{ is_rtl() ? 'r' : 'l' }}-4

// Margin
{{ is_rtl() ? 'ml' : 'mr' }}-2

// Text Align
{{ is_rtl() ? 'right' : 'left' }}
```

---

## 🔧 افزودن ترجمه جدید

### مثال: اضافه کردن متن "شروع کنید"

#### 1. اضافه به `lang/fa/langWelcome.php`:
```php
'get_started' => 'شروع کنید',
```

#### 2. اضافه به `lang/en/langWelcome.php`:
```php
'get_started' => 'Get Started',
```

#### 3. اضافه به `lang/ar/langWelcome.php`:
```php
'get_started' => 'ابدأ',
```

#### 4. استفاده در Blade:
```html
<button>{{ __('langWelcome.get_started') }}</button>
```

---

## 🌐 ترجمه فایل‌های Partial

صفحه welcome از چندین partial استفاده می‌کند:
```php
@include('partials.hero-section')
@include('partials.mission-section')
@include('partials.features-section')
@include('partials.governance-section')
@include('partials.network-section')
@include('partials.how-it-works-section')
@include('partials.bahar-economy-section')
@include('partials.projects-section')
@include('partials.invite-section')
@include('partials.testimonials-section')
@include('partials.cta-section')
@include('partials.footer')
```

### برای ترجمه هر partial:

1. **کلیدهای ترجمه اضافه کنید** به فایل‌های `langWelcome.php`
2. **متن‌های هاردکد را جایگزین کنید** با `__('langWelcome.xxx')`
3. **RTL/LTR را بررسی کنید** - margins، paddings، borders
4. **فلش‌ها و آیکون‌ها** را بر اساس جهت تنظیم کنید

---

## 📱 تست

### Desktop
1. باز کردن صفحه اصلی
2. کلیک روی پرچم در header
3. انتخاب English
4. همه متن‌ها باید انگلیسی و چپ‌چین شوند

### Mobile
1. باز کردن منوی همبرگری
2. کلیک روی پرچم English (🇬🇧)
3. صفحه refresh می‌شود با زبان انگلیسی

---

## ⚠️ نکات مهم

### 1. Cache
بعد از هر تغییر در فایل‌های ترجمه:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### 2. Tailwind RTL/LTR
برای بعضی classes نیاز به تنظیم دستی:
```php
// ❌ اشتباه
<div class="ml-4">

// ✅ درست
<div class="{{ is_rtl() ? 'mr' : 'ml' }}-4">
```

### 3. Input Direction
برای inputهای خاص (مثل email):
```php
<input dir="{{ is_ltr() ? 'ltr' : 'rtl' }}" />

// یا برای email همیشه LTR
<input dir="ltr" />
```

### 4. JavaScript
برای دسترسی به زبان فعلی در JS:
```javascript
const currentLocale = '{{ app()->getLocale() }}';
const isRTL = {{ is_rtl() ? 'true' : 'false' }};
```

---

## 🚀 مراحل بعدی

### اولویت بالا:
1. ✅ Header & Modal ترجمه شد
2. 🔲 **ترجمه Partials** - hero, features, etc.
3. 🔲 **تست کامل** - تمام بخش‌های صفحه

### اولویت متوسط:
4. 🔲 SEO meta tags چند زبانه
5. 🔲 Open Graph برای share کردن
6. 🔲 تصاویر با alt text ترجمه شده

### اولویت پایین:
7. 🔲 Animations بر اساس RTL/LTR
8. 🔲 فونت‌های بهتر برای عربی
9. 🔲 Sitemap چند زبانه

---

## 📞 مثال کامل: ترجمه یک بخش

### قبل:
```html
<h1 class="text-4xl font-bold">
    خوش آمدید به EarthCoop
</h1>
<p class="mt-4 text-gray-600">
    ما یک شبکه جهانی برای همکاری هستیم
</p>
<a href="#" class="btn ml-4">
    <i class="fas fa-arrow-left mr-2"></i>
    بیشتر بدانید
</a>
```

### بعد:
```html
<h1 class="text-4xl font-bold">
    {{ __('langWelcome.hero_title') }}
</h1>
<p class="mt-4 text-gray-600">
    {{ __('langWelcome.hero_subtitle') }}
</p>
<a href="#" class="btn {{ is_rtl() ? 'ml' : 'mr' }}-4">
    <i class="fas fa-arrow-{{ is_rtl() ? 'left' : 'right' }} {{ is_rtl() ? 'mr' : 'ml' }}-2"></i>
    {{ __('langWelcome.learn_more') }}
</a>
```

### فایل ترجمه:
```php
// lang/fa/langWelcome.php
'hero_title' => 'خوش آمدید به EarthCoop',
'hero_subtitle' => 'ما یک شبکه جهانی برای همکاری هستیم',
'learn_more' => 'بیشتر بدانید',

// lang/en/langWelcome.php
'hero_title' => 'Welcome to EarthCoop',
'hero_subtitle' => 'We are a global network for cooperation',
'learn_more' => 'Learn More',

// lang/ar/langWelcome.php
'hero_title' => 'مرحبا بك في EarthCoop',
'hero_subtitle' => 'نحن شبكة عالمية للتعاون',
'learn_more' => 'اعرف المزيد',
```

---

**✨ صفحه Welcome حالا کاملاً چند زبانه است و آماده استفاده!**

برای ترجمه بخش‌های دیگر، همین الگو را دنبال کنید.
