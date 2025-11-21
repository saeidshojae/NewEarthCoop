راهنمای پیکربندی ایمیل و صف‌ها برای سیستم پشتیبانی

هدف: اطمینان از اینکه ایمیل‌های ایجاد تیکت و نوتیفیکیشن‌ها به‌صورت قابل اعتماد و غیرهمزمان ارسال شوند.

موارد مورد نیاز

قدم‌های پیکربندی

---

Najm Hoda integration

- To allow the Najm Hoda service to escalate conversations into tickets, set an internal token in your `.env`:

```
NAJM_HODA_TOKEN=your-secure-token-here
```

- Najm Hoda should call the public API endpoint `POST /api/najm-hoda/escalate` with the header:

```
X-NAJM-HODA-TOKEN: <value of NAJM_HODA_TOKEN>
```

- Payload (JSON):

```
{
  "conversation_id": "string",
  "transcript": "string",
  "user_email": "optional email",
  "user_id": "optional integer",
  "reason": "optional string",
  "metadata": { }
}
```

- The endpoint is implemented to be fast (it queues work). It will create a `tickets` record and write the transcript as a `TicketComment`. If a ticket already exists for the `conversation_id`, it will append a comment to the existing ticket instead of creating a duplicate.
  - مقادیر پیشنهادی به‌صورت مثال:

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.mailgun.org
    MAIL_PORT=587
    MAIL_USERNAME=your_mail_user
    MAIL_PASSWORD=your_mail_password
    MAIL_ENCRYPTION=tls
    MAIL_FROM_ADDRESS=info@earthcoop.org
    MAIL_FROM_NAME="EarthCoop"

    # آدرس ایمیل تیم پشتیبانی که ایمیل‌های گزارش تیکت را دریافت می‌کند
    SUPPORT_EMAIL=support@earthcoop.org

    # پیشنهاد: برای queue از redis استفاده کنید
    QUEUE_CONNECTION=redis

2) نصب و پیکربندی Redis (در صورت انتخاب)

  - ویندوز: می‌توانید از WSL یا یک سرویس Redis مدیریت‌شده استفاده کنید.
  - اگر از WSL استفاده می‌کنید، داخل WSL نصب و اجرا کنید:

    sudo apt update; sudo apt install redis-server -y
    sudo service redis-server start

  - سپس `QUEUE_CONNECTION=redis` را در `.env` بگذارید.

3) اجرای مهاجرت‌ها

    php artisan migrate

4) اجرای worker در محیط توسعه (PowerShell)

    # از ریشه پروژه در PowerShell اجرا کنید
    php artisan queue:work --tries=3

  - در سرور production از supervisor/systemd برای اجرای دائمی worker استفاده کنید.

5) تست

  - فرم تماس را در صفحه تماس ارسال کنید.
  - ردیف جدید در جدول `tickets` ساخته می‌شود.
  - ایمیل‌های ایجاد تیکت به آدرس کاربر (در صورت وجود) و `SUPPORT_EMAIL` ارسال می‌شوند — اگر از queue استفاده می‌کنید، worker باید در حال اجرا باشد تا ایمیل ارسال شود.

نکات عملی
- اگر می‌خواهید ارسال ایمیل را سریع در توسعه ببینید بدون تنظیم SMTP، می‌توانید `MAIL_MAILER=log` قرار دهید تا ایمیل‌ها در لاگ نوشته شوند.
- اگر سرور شما امکان استفاده از Redis ندارد، می‌توانید `QUEUE_CONNECTION=database` را انتخاب کنید و جدول queue را با `php artisan queue:table && php artisan migrate` ایجاد کنید.

اگر می‌خواهید، من می‌توانم همین الآن:
- یک `README` کوتاه‌تر به `README.md` اضافه کنم، یا
- پنل ادمین تیکت را پیاده‌سازی کنم (گام بعدی برنامه) — کدام را ترجیح می‌دهید؟
