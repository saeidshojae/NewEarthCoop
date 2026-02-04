# 🧹 اسکریپت‌های پاکسازی و سازماندهی UI

## 📋 فهرست کارها

این فایل شامل دستورات و اسکریپت‌های عملی برای پاکسازی و سازماندهی رابط کاربری است.

---

## 🗑️ مرحله 1: حذف فایل‌های Backup و Old

### Windows PowerShell:

```powershell
# حذف فایل‌های backup
Remove-Item "resources\views\home-old-backup.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\home.blade.php.backup" -ErrorAction SilentlyContinue
Remove-Item "resources\views\auth\register_step1_old_backup.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\auth\register_step2_old_backup.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\auth\register_step3_old_backup.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\welcome-old.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\welcome.blade.php.backup" -ErrorAction SilentlyContinue
Remove-Item "resources\views\terms-old.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\groups\index-old-backup.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\invitation\index-old.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\auth\login-old.blade.php" -ErrorAction SilentlyContinue
Remove-Item "resources\views\auth\register-old.blade.php" -ErrorAction SilentlyContinue

Write-Host "✅ فایل‌های backup حذف شدند" -ForegroundColor Green
```

### Linux/Mac Bash:

```bash
# حذف فایل‌های backup
rm -f resources/views/home-old-backup.blade.php
rm -f resources/views/home.blade.php.backup
rm -f resources/views/auth/register_step1_old_backup.blade.php
rm -f resources/views/auth/register_step2_old_backup.blade.php
rm -f resources/views/auth/register_step3_old_backup.blade.php
rm -f resources/views/welcome-old.blade.php
rm -f resources/views/welcome.blade.php.backup
rm -f resources/views/terms-old.blade.php
rm -f resources/views/groups/index-old-backup.blade.php
rm -f resources/views/invitation/index-old.blade.php
rm -f resources/views/auth/login-old.blade.php
rm -f resources/views/auth/register-old.blade.php

echo "✅ فایل‌های backup حذف شدند"
```

---

## 📁 مرحله 2: ایجاد ساختار CSS جدید

### ساختار پیشنهادی:

```bash
# ایجاد دایرکتوری‌های جدید
mkdir -p public/Css/core
mkdir -p public/Css/components
mkdir -p public/Css/pages
mkdir -p public/Css/utilities
```

### انتقال فایل‌های موجود:

```bash
# انتقال فایل‌های core
mv public/Css/design-system.css public/Css/core/design-system.css
mv public/Css/dark-mode.css public/Css/core/dark-mode.css
mv public/Css/fonts.css public/Css/core/fonts.css
mv public/Css/lang-direction.css public/Css/core/lang-direction.css

# انتقال فایل‌های pages
mv public/Css/welcome-new.css public/Css/pages/welcome.css
mv public/Css/comment.chat.css public/Css/pages/chat.css
mv public/Css/group-chat.css public/Css/pages/groups.css
```

---

## 📝 مرحله 3: ایجاد فایل CSS مرکزی

### ایجاد `public/Css/main.css`:

```css
/**
 * NewEarthCoop - Main CSS File
 * این فایل تمام استایل‌های پروژه را import می‌کند
 */

/* ==================== Core Styles ==================== */
@import 'core/design-system.css';
@import 'core/dark-mode.css';
@import 'core/fonts.css';
@import 'core/lang-direction.css';

/* ==================== Components ==================== */
@import 'components/navbar.css';
@import 'components/footer.css';
@import 'components/buttons.css';
@import 'components/cards.css';
@import 'components/forms.css';
@import 'components/modals.css';

/* ==================== Pages ==================== */
@import 'pages/home.css';
@import 'pages/welcome.css';
@import 'pages/groups.css';
@import 'pages/chat.css';
@import 'pages/profile.css';

/* ==================== Utilities ==================== */
@import 'utilities/animations.css';
@import 'utilities/helpers.css';
```

---

## 🔄 مرحله 4: اسکریپت مهاجرت Layout

### اسکریپت PowerShell برای مهاجرت صفحات:

```powershell
# لیست صفحات برای مهاجرت
$pages = @(
    "resources\views\home.blade.php",
    "resources\views\groups\index.blade.php",
    "resources\views\groups\show.blade.php",
    "resources\views\profile\profile.blade.php",
    "resources\views\notifications\index.blade.php"
)

foreach ($page in $pages) {
    if (Test-Path $page) {
        # خواندن محتوای فایل
        $content = Get-Content $page -Raw
        
        # تغییر layout
        $content = $content -replace "@extends\(['""]layouts\.app['""]\)", "@extends('layouts.master')"
        
        # اضافه کردن section title اگر وجود ندارد
        if ($content -notmatch "@section\('title'") {
            $titlePattern = '<title>([^<]+)</title>'
            if ($content -match $titlePattern) {
                $title = $matches[1]
                $content = $content -replace "@extends\('layouts\.master'\)", "@extends('layouts.master')`n`n@section('title', '$title')"
            } else {
                $content = $content -replace "@extends\('layouts\.master'\)", "@extends('layouts.master')`n`n@section('title', 'New Earth Coop')"
            }
        }
        
        # ذخیره فایل
        Set-Content $page $content
        Write-Host "✅ $page مهاجرت شد" -ForegroundColor Green
    }
}
```

---

## 📋 مرحله 5: چک‌لیست برای هر صفحه

### Template برای بررسی صفحات:

```markdown
## بررسی صفحه: [نام صفحه]

### Layout:
- [ ] از `layouts.master` استفاده می‌کند؟
- [ ] `@section('title')` دارد؟

### CSS:
- [ ] از `main.css` استفاده می‌کند؟
- [ ] استایل‌های inline ندارد؟
- [ ] از متغیرهای CSS استفاده می‌کند؟

### Components:
- [ ] Navbar خودش ندارد (از component استفاده می‌کند)؟
- [ ] Footer خودش ندارد (از component استفاده می‌کند)؟

### Responsive:
- [ ] در موبایل (320px) درست نمایش داده می‌شود؟
- [ ] در تبلت (768px) درست نمایش داده می‌شود؟
- [ ] در دسکتاپ (1920px) درست نمایش داده می‌شود؟

### Dark Mode:
- [ ] Dark Mode را پشتیبانی می‌کند؟
- [ ] رنگ‌ها در Dark Mode درست هستند؟

### Accessibility:
- [ ] تصاویر `alt` دارند؟
- [ ] دکمه‌ها `aria-label` دارند؟
- [ ] از semantic HTML استفاده می‌کند؟
```

---

## 🔍 مرحله 6: اسکریپت بررسی مشکلات

### بررسی فایل‌هایی که از `layouts.app` استفاده می‌کنند:

```powershell
# جستجوی فایل‌هایی که از layouts.app استفاده می‌کنند
$files = Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse | 
    Where-Object { 
        (Get-Content $_.FullName -Raw) -match "layouts\.app"
    }

Write-Host "📊 تعداد فایل‌های باقیمانده: $($files.Count)" -ForegroundColor Yellow

foreach ($file in $files) {
    Write-Host "  - $($file.FullName)" -ForegroundColor Gray
}
```

### بررسی فایل‌هایی که Navbar خودشان دارند:

```powershell
# جستجوی فایل‌هایی که Navbar خودشان دارند
$files = Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse | 
    Where-Object { 
        $content = Get-Content $_.FullName -Raw
        ($content -match '<nav') -and ($content -notmatch '@include.*navbar')
    }

Write-Host "📊 تعداد فایل‌هایی که Navbar خودشان دارند: $($files.Count)" -ForegroundColor Yellow

foreach ($file in $files) {
    Write-Host "  - $($file.FullName)" -ForegroundColor Gray
}
```

---

## 🎨 مرحله 7: ایجاد Component های CSS

### ایجاد `public/Css/components/buttons.css`:

```css
/**
 * Button Components
 * استایل‌های یکپارچه برای دکمه‌ها
 */

.btn-primary-new {
    background: var(--color-earth-green);
    color: var(--color-pure-white);
    padding: 0.75rem 1.5rem;
    border-radius: var(--radius-md);
    border: none;
    font-weight: 500;
    cursor: pointer;
    transition: all var(--transition-base);
}

.btn-primary-new:hover {
    background: var(--color-dark-green);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

body.dark-mode .btn-primary-new {
    background: var(--color-ocean-blue);
}

body.dark-mode .btn-primary-new:hover {
    background: var(--color-dark-blue);
}
```

### ایجاد `public/Css/components/cards.css`:

```css
/**
 * Card Components
 * استایل‌های یکپارچه برای کارت‌ها
 */

.card-new {
    background: var(--card-light);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    box-shadow: var(--shadow-md);
    transition: all var(--transition-base);
}

.card-new:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-4px);
}

body.dark-mode .card-new {
    background: var(--card-dark);
    border: 1px solid var(--border-dark);
}
```

---

## 📊 مرحله 8: گزارش‌گیری

### اسکریپت ایجاد گزارش:

```powershell
# ایجاد گزارش از وضعیت UI
$report = @{
    "تاریخ" = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    "فایل‌های backup" = (Get-ChildItem -Path "resources\views" -Filter "*backup*" -Recurse).Count
    "فایل‌های old" = (Get-ChildItem -Path "resources\views" -Filter "*old*" -Recurse).Count
    "صفحات با layouts.app" = (Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse | 
        Where-Object { (Get-Content $_.FullName -Raw) -match "layouts\.app" }).Count
    "صفحات با layouts.master" = (Get-ChildItem -Path "resources\views" -Filter "*.blade.php" -Recurse | 
        Where-Object { (Get-Content $_.FullName -Raw) -match "layouts\.master" }).Count
}

$report | ConvertTo-Json | Out-File "ui-cleanup-report.json"

Write-Host "✅ گزارش ایجاد شد: ui-cleanup-report.json" -ForegroundColor Green
```

---

## 🚀 اجرای کامل

### اسکریپت کامل PowerShell:

```powershell
# اسکریپت کامل پاکسازی و سازماندهی UI

Write-Host "🚀 شروع پاکسازی و سازماندهی UI..." -ForegroundColor Cyan

# مرحله 1: حذف فایل‌های backup
Write-Host "`n📁 مرحله 1: حذف فایل‌های backup..." -ForegroundColor Yellow
# ... (کدهای حذف فایل‌ها)

# مرحله 2: ایجاد ساختار CSS
Write-Host "`n📁 مرحله 2: ایجاد ساختار CSS..." -ForegroundColor Yellow
# ... (کدهای ایجاد ساختار)

# مرحله 3: ایجاد فایل CSS مرکزی
Write-Host "`n📁 مرحله 3: ایجاد فایل CSS مرکزی..." -ForegroundColor Yellow
# ... (کدهای ایجاد main.css)

# مرحله 4: مهاجرت Layout ها
Write-Host "`n📁 مرحله 4: مهاجرت Layout ها..." -ForegroundColor Yellow
# ... (کدهای مهاجرت)

# مرحله 5: ایجاد گزارش
Write-Host "`n📁 مرحله 5: ایجاد گزارش..." -ForegroundColor Yellow
# ... (کدهای گزارش)

Write-Host "`n✅ پاکسازی و سازماندهی UI با موفقیت انجام شد!" -ForegroundColor Green
```

---

## ⚠️ نکات مهم

### قبل از اجرای اسکریپت‌ها:

1. ✅ **Backup بگیرید**: قبل از هر تغییر، یک backup کامل از پروژه بگیرید
2. ✅ **Git commit**: تغییرات را commit کنید
3. ✅ **تست کنید**: بعد از هر تغییر، صفحه را تست کنید

### بعد از اجرای اسکریپت‌ها:

1. ✅ **بررسی کنید**: تمام صفحات را بررسی کنید
2. ✅ **تست کنید**: در مرورگرهای مختلف تست کنید
3. ✅ **Commit کنید**: تغییرات را commit کنید

---

## 📞 پشتیبانی

اگر مشکلی پیش آمد:

1. بررسی لاگ‌ها
2. بررسی فایل‌های تغییر یافته
3. مراجعه به `UI_UX_ANALYSIS_AND_RECOMMENDATIONS.md`

---

**موفق باشید!** 🚀



