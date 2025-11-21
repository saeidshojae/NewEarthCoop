# 🚀 دستور‌العمل راه‌اندازی ماژول وبلاگ

## مرحله 1️⃣: کپی Migrations

ابتدا فایل‌های migration را به پوشه اصلی کپی کنید:

```powershell
# در PowerShell اجرا کنید
Copy-Item "app\Modules\Blog\Migrations\*.php" -Destination "database\migrations\"
```

یا به صورت دستی:
- فایل‌های داخل `app/Modules/Blog/Migrations/` را به `database/migrations/` کپی کنید

## مرحله 2️⃣: اجرای Migrations

```powershell
php artisan migrate
```

اگر خطای "Table already exists" دریافت کردید:
```powershell
php artisan migrate:fresh  # هشدار: این دستور تمام دیتا را پاک می‌کند!
```

## مرحله 3️⃣: ایجاد دیتای نمونه (اختیاری)

```powershell
php artisan db:seed --class=BlogSeeder
```

این دستور موارد زیر را ایجاد می‌کند:
- 4 دسته‌بندی
- 15 برچسب
- 5 مقاله نمونه

## مرحله 4️⃣: بررسی Routes

```powershell
php artisan route:list --path=blog
```

باید روت‌های زیر را ببینید:
- GET /blog
- GET /blog/{slug}
- GET /blog/category/{slug}
- GET /blog/tag/{slug}
- و...

## مرحله 5️⃣: پاک کردن Cache

```powershell
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

## مرحله 6️⃣: اجرای سرور

```powershell
php artisan serve
```

## مرحله 7️⃣: دسترسی به وبلاگ

### صفحات عمومی:
- 🏠 صفحه اصلی وبلاگ: http://localhost:8000/blog
- 📰 مقاله نمونه: http://localhost:8000/blog/intro-to-laravel-10

### پنل ادمین (نیاز به ورود):
- 📊 داشبورد: http://localhost:8000/admin/blog/dashboard
- 📝 مقالات: http://localhost:8000/admin/blog/posts
- 📁 دسته‌بندی‌ها: http://localhost:8000/admin/blog/categories
- 🏷️  برچسب‌ها: http://localhost:8000/admin/blog/tags
- 💬 نظرات: http://localhost:8000/admin/blog/comments

## ⚠️ نکات مهم:

### 1. دسترسی ادمین
برای دسترسی به پنل ادمین باید:
- وارد سیستم شوید
- کاربر شما باید دسترسی ادمین داشته باشد (مطابق با `AdminMiddleware`)

### 2. دسترسی به پوشه‌های آپلود
مطمئن شوید این پوشه‌ها وجود دارند و قابل نوشتن هستند:
```
public/images/blog/posts/
public/images/blog/categories/
```

### 3. تنظیمات .env
اطمینان حاصل کنید database به درستی تنظیم شده است:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 🎯 تست عملکرد:

### تست 1: مشاهده لیست مقالات
```
✅ بروید به http://localhost:8000/blog
✅ باید لیست مقالات را ببینید
✅ جستجو و فیلتر را امتحان کنید
```

### تست 2: مشاهده یک مقاله
```
✅ روی یک مقاله کلیک کنید
✅ باید محتوای کامل نمایش داده شود
✅ مقالات مرتبط را ببینید
```

### تست 3: ثبت نظر
```
✅ وارد شوید
✅ در صفحه مقاله نظر بگذارید
✅ در پنل ادمین نظر را تایید کنید
```

### تست 4: ایجاد مقاله جدید
```
✅ وارد پنل ادمین شوید
✅ مقاله جدید ایجاد کنید
✅ تصویر آپلود کنید
✅ برچسب‌ها را انتخاب کنید
```

## 🐛 رفع مشکلات رایج:

### خطا: "View [Blog::frontend.index] not found"
```powershell
# مطمئن شوید namespace ثبت شده است
# در AppServiceProvider.php:
View::addNamespace('Blog', base_path('app/Modules/Blog/Views'));

# سپس cache را پاک کنید
php artisan config:clear
php artisan view:clear
```

### خطا: "Class 'App\Modules\Blog\Models\Post' not found"
```powershell
# Composer را به‌روزرسانی کنید
composer dump-autoload
```

### خطا: "SQLSTATE[42S02]: Base table or view not found"
```powershell
# Migration ها را اجرا کنید
php artisan migrate
```

### خطا: 404 در روت‌ها
```powershell
# Route cache را پاک کنید
php artisan route:clear
php artisan route:cache
```

## 📚 منابع بیشتر:

- 📖 مستندات کامل: `app/Modules/Blog/README.md`
- 🎨 سفارشی‌سازی Views: `app/Modules/Blog/Views/`
- 🔧 تنظیمات Controllers: `app/Modules/Blog/Controllers/`

## ✅ چک‌لیست نهایی:

- [ ] Migrations اجرا شده است
- [ ] Seeder اجرا شده است (اختیاری)
- [ ] Cache ها پاک شده‌اند
- [ ] Namespace ثبت شده است
- [ ] پوشه‌های images ایجاد شده‌اند
- [ ] Routes به درستی کار می‌کنند
- [ ] صفحه وبلاگ باز می‌شود
- [ ] پنل ادمین قابل دسترسی است

---

**🎉 تبریک! ماژول وبلاگ با موفقیت نصب شد!**

اگر مشکلی داشتید، لطفاً مراحل بالا را دوباره بررسی کنید.
