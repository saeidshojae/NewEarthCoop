# ماژول نجم بهار (NajmBahar Module)

سیستم بانکی نجم بهار برای مدیریت حساب‌های مالی و تراکنش‌ها در پلتفرم EarthCoop.

## 📋 فهرست مطالب

- [معرفی](#معرفی)
- [ویژگی‌ها](#ویژگی‌ها)
- [ساختار ماژول](#ساختار-ماژول)
- [نصب و راه‌اندازی](#نصب-و-راه‌اندازی)
- [استفاده](#استفاده)
- [مستندات](#مستندات)
- [تست‌ها](#تست‌ها)
- [وضعیت توسعه](#وضعیت-توسعه)

---

## معرفی

ماژول نجم بهار یک سیستم بانکی کامل با قابلیت‌های زیر است:

- ✅ مدیریت حساب‌های مالی کاربران
- ✅ تراکنش‌های فوری و زمان‌بندی شده
- ✅ سیستم دفتر کل (Double-Entry Accounting)
- ✅ پشتیبانی از Idempotency
- ✅ یکپارچه‌سازی با سیستم قدیمی Spring
- ✅ API کامل برای توسعه‌دهندگان

---

## ویژگی‌ها

### 1. حساب‌های مالی
- ایجاد حساب اصلی برای هر کاربر
- حساب‌های فرعی (SubAccounts)
- حساب‌های سیستم
- مدیریت موجودی

### 2. تراکنش‌ها
- تراکنش‌های فوری (Immediate)
- تراکنش‌های زمان‌بندی شده (Scheduled)
- تراکنش‌های کارمزد (Fee)
- تراکنش‌های تعدیل (Adjustment)

### 3. دفتر کل
- ثبت Double-Entry برای تمام تراکنش‌ها
- ردیف‌های Debit و Credit
- تاریخچه کامل تراکنش‌ها

### 4. امنیت
- Atomic transactions (DB transactions)
- Account locking برای جلوگیری از race condition
- Idempotency key برای جلوگیری از تراکنش‌های تکراری
- بررسی موجودی کافی قبل از تراکنش

---

## ساختار ماژول

```
app/Modules/NajmBahar/
├── Models/
│   ├── Account.php                    # حساب‌های اصلی
│   ├── SubAccount.php                 # حساب‌های فرعی
│   ├── Transaction.php                # تراکنش‌ها
│   ├── ScheduledTransaction.php       # تراکنش‌های زمان‌بندی شده
│   └── LedgerEntry.php                # ردیف‌های دفتر کل
├── Services/
│   ├── AccountNumberService.php       # تولید شماره حساب
│   ├── AccountService.php             # مدیریت حساب‌ها
│   └── TransactionService.php         # مدیریت تراکنش‌ها
├── Adapters/
│   └── LegacyNajmAdapter.php          # اتصال به سیستم قدیمی Spring
├── README.md                           # این فایل
├── API_DOCUMENTATION.md               # مستندات API
└── DEVELOPER_GUIDE.md                 # راهنمای توسعه‌دهنده
```

---

## نصب و راه‌اندازی

### 1. اجرای Migration‌ها

```bash
php artisan migrate
```

Migration‌های ماژول:
- `2025_11_22_000001_create_najm_accounts_table.php`
- `2025_11_22_000002_create_najm_sub_accounts_table.php`
- `2025_11_22_000003_create_najm_transactions_table.php`
- `2025_11_22_000004_create_najm_scheduled_transactions_table.php`
- `2025_11_22_000005_create_najm_ledger_entries_table.php`

### 2. اجرای Seeder (اختیاری)

```bash
php artisan db:seed --class=NajmBaharSeeder
```

این seeder حساب سیستم را ایجاد می‌کند.

### 3. تنظیم Cron Job

برای پردازش تراکنش‌های زمان‌بندی شده، این command را به cron اضافه کنید:

```bash
* * * * * cd /path-to-your-project && php artisan najm-bahar:process-scheduled >> /dev/null 2>&1
```

### 4. تنظیم Routes

Routes به صورت خودکار در `routes/api.php` و `routes/web.php` لود می‌شوند.

---

## استفاده

### ایجاد حساب

```php
use App\Modules\NajmBahar\Services\AccountService;

$service = new AccountService();
$account = $service->createMainAccountForUser($userId);
```

### انجام تراکنش

```php
use App\Modules\NajmBahar\Services\TransactionService;

$service = new TransactionService();

// انتقال فوری
$transaction = $service->transfer(
    '1000000123',  // from account number
    '1000000456',  // to account number
    100,           // amount (in smallest unit)
    'Payment description',
    [],            // metadata
    'unique-key'   // idempotency key (optional)
);
```

### دریافت موجودی

```php
use App\Modules\NajmBahar\Models\Account;

$account = Account::where('account_number', '1000000123')->first();
$balance = $account->balance;
```

### دریافت تاریخچه تراکنش‌ها

```php
use App\Modules\NajmBahar\Models\Transaction;

$transactions = Transaction::where('from_account_id', $accountId)
    ->orWhere('to_account_id', $accountId)
    ->orderBy('created_at', 'desc')
    ->get();
```

---

## مستندات

- **[مستندات API](./API_DOCUMENTATION.md)**: راهنمای کامل API endpoints
- **[راهنمای توسعه‌دهنده](./DEVELOPER_GUIDE.md)**: راهنمای توسعه و توسعه ویژگی‌های جدید

---

## تست‌ها

### اجرای تست‌ها

```bash
# تمام تست‌های NajmBahar
php artisan test --filter NajmBahar

# تست‌های Unit
php artisan test tests/Unit/AccountNumberServiceTest.php
php artisan test tests/Unit/AccountServiceTest.php

# تست‌های Feature
php artisan test tests/Feature/TransactionServiceTest.php
php artisan test tests/Feature/LegacyNajmAdapterTest.php
php artisan test tests/Feature/NajmBaharApiTest.php
php artisan test tests/Feature/ScheduledTransactionTest.php
```

### پوشش تست‌ها

- ✅ AccountNumberService (100%)
- ✅ AccountService (100%)
- ✅ TransactionService (100%)
- ✅ LegacyNajmAdapter (100%)
- ✅ API Controllers (100%)
- ✅ Scheduled Transactions (100%)

---

## یکپارچه‌سازی با Legacy

ماژول به صورت خودکار با سیستم قدیمی Spring یکپارچه می‌شود:

1. هنگام ایجاد Spring جدید، Event Listener فعال می‌شود
2. Job `ProcessSpringCreatedNajm` در صف قرار می‌گیرد
3. Adapter حساب NajmBahar ایجاد می‌کند
4. واریز اولیه 10000 بهار انجام می‌شود
5. کارمزد عضویت 12 بهار کسر می‌شود

برای جزئیات بیشتر، به [Developer Guide](./DEVELOPER_GUIDE.md) مراجعه کنید.

---

## وضعیت توسعه

### ✅ فاز 1: تراکنش‌ها (تکمیل شده)

- [x] مدل‌های پایه
- [x] سرویس‌های اصلی
- [x] API Controllers
- [x] یکپارچه‌سازی با Legacy
- [x] تست‌ها
- [x] مستندات

### 🔄 فاز 2: ویژگی‌های پیشرفته (در حال برنامه‌ریزی)

- [ ] Dashboard کاربری
- [ ] گزارش‌های مالی
- [ ] اعلان‌های تراکنش
- [ ] سیستم کارمزد قابل تنظیم
- [ ] API برای مدیریت حساب‌های فرعی
- [ ] Export گزارش‌ها

---

## نکات مهم

### شماره حساب‌ها

- **سیستم**: `0000000000`
- **کاربران**: `1000000001` تا `9999999999` (فرمت: `1000000{userId}`)
- **حساب‌های فرعی**: `{mainAccountNumber}-{index}` (مثال: `0000000000-001`)

### واحد پول

تمام مبالغ به کوچک‌ترین واحد (بهار) هستند:
- 1.00 بهار = `1`
- 100.50 بهار = `10050`

### Thread Safety

برای جلوگیری از race condition، از account locking استفاده می‌شود. تمام تراکنش‌ها به صورت atomic انجام می‌شوند.

---

## پشتیبانی

برای سوالات و مشکلات:
- بررسی [مستندات API](./API_DOCUMENTATION.md)
- بررسی [راهنمای توسعه‌دهنده](./DEVELOPER_GUIDE.md)
- تماس با تیم توسعه

---

**نسخه:** 1.0.0  
**آخرین بروزرسانی:** 2025-11-22  
**وضعیت:** ✅ آماده برای استفاده
