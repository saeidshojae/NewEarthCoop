# راهنمای بررسی مشکل ارسال ایمیل

## 1. بررسی لاگ‌ها

بعد از تأیید درخواست کد دعوت، فایل لاگ را بررسی کنید:

```bash
# در PowerShell
Get-Content storage\logs\laravel.log -Tail 100 | Select-String -Pattern "invitation|email|mail" -Context 3
```

یا فایل `storage/logs/laravel.log` را باز کنید و دنبال این خطوط بگردید:
- `Attempting to send invitation email`
- `Invitation email sent successfully`
- `Failed to send invitation email`

## 2. تست مستقیم ارسال ایمیل

به آدرس زیر بروید (بعد از لاگین به عنوان super admin):
```
http://localhost:8000/admin/test-email?email=your-email@example.com
```

این route یک ایمیل تست ارسال می‌کند و تنظیمات SMTP را نمایش می‌دهد.

## 3. بررسی تنظیمات SMTP

در فایل `.env` بررسی کنید:

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.earthcoop.info
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=info@earthcoop.info
MAIL_FROM_NAME="Earth Coop"
```

## 4. استفاده از Log Driver برای تست (موقت)

اگر می‌خواهید فقط محتوای ایمیل را ببینید (بدون ارسال واقعی):

در `.env` تغییر دهید:
```env
MAIL_MAILER=log
```

سپس ایمیل‌ها در فایل `storage/logs/laravel.log` ذخیره می‌شوند.

## 5. بررسی صف (Queue)

اگر از Queue استفاده می‌کنید، مطمئن شوید که worker در حال اجرا است:

```bash
php artisan queue:work
```

یا در `.env`:
```env
QUEUE_CONNECTION=sync
```

## 6. مشکلات رایج

### مشکل: "Connection timeout"
- بررسی کنید که `MAIL_HOST` و `MAIL_PORT` درست باشند
- فایروال یا آنتی‌ویروس را بررسی کنید

### مشکل: "Authentication failed"
- `MAIL_USERNAME` و `MAIL_PASSWORD` را بررسی کنید
- مطمئن شوید که رمز عبور درست است

### مشکل: "Could not instantiate mailer"
- بررسی کنید که extension `openssl` در PHP فعال باشد
- بررسی کنید که `MAIL_ENCRYPTION` درست باشد (tls یا ssl)

## 7. بررسی در کد

در `InvitationCodeController@approveInvitation`، لاگ‌های زیر اضافه شده‌اند:
- قبل از ارسال: `Attempting to send invitation email`
- بعد از ارسال موفق: `Invitation email sent successfully`
- در صورت خطا: `Failed to send invitation email` با جزئیات خطا

