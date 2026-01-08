# 🛠️ راهنمای توسعه‌دهنده - ماژول نجم بهار

## فهرست مطالب

1. [معماری ماژول](#معماری-ماژول)
2. [ساختار فایل‌ها](#ساختار-فایل‌ها)
3. [مدل‌ها](#مدل‌ها)
4. [سرویس‌ها](#سرویس‌ها)
5. [یکپارچه‌سازی با Legacy](#یکپارچه‌سازی-با-legacy)
6. [توسعه ویژگی‌های جدید](#توسعه-ویژگی‌های-جدید)
7. [تست‌ها](#تست‌ها)
8. [بهینه‌سازی و Performance](#بهینه‌سازی-و-performance)

---

## معماری ماژول

ماژول نجم بهار یک سیستم بانکی ساده با قابلیت‌های زیر است:

- **Double-Entry Accounting**: تمام تراکنش‌ها به صورت دفتر کل ثبت می‌شوند
- **Atomic Transactions**: تمام عملیات مالی به صورت atomic انجام می‌شوند
- **Idempotency**: پشتیبانی از idempotency key برای جلوگیری از تراکنش‌های تکراری
- **Scheduled Transactions**: امکان زمان‌بندی تراکنش‌ها
- **Legacy Integration**: یکپارچه‌سازی با سیستم قدیمی Spring

---

## ساختار فایل‌ها

```
app/Modules/NajmBahar/
├── Models/
│   ├── Account.php              # حساب‌های اصلی
│   ├── SubAccount.php           # حساب‌های فرعی
│   ├── Transaction.php          # تراکنش‌ها
│   ├── ScheduledTransaction.php # تراکنش‌های زمان‌بندی شده
│   └── LedgerEntry.php          # ردیف‌های دفتر کل
├── Services/
│   ├── AccountNumberService.php # تولید شماره حساب
│   ├── AccountService.php       # مدیریت حساب‌ها
│   └── TransactionService.php  # مدیریت تراکنش‌ها
├── Adapters/
│   └── LegacyNajmAdapter.php    # اتصال به سیستم قدیمی
├── README.md                    # راهنمای کلی
├── API_DOCUMENTATION.md         # مستندات API
└── DEVELOPER_GUIDE.md           # این فایل
```

---

## مدل‌ها

### Account

حساب اصلی کاربران و سیستم.

**جدول:** `najm_accounts`

**فیلدهای مهم:**
- `account_number`: شماره حساب (10 رقم، unique)
- `user_id`: شناسه کاربر (nullable برای حساب‌های سیستم)
- `type`: نوع حساب (`system`, `user`, `legal_entity`, `bank`)
- `balance`: موجودی (integer، به کوچک‌ترین واحد)

**مثال استفاده:**
```php
use App\Modules\NajmBahar\Models\Account;

// ایجاد حساب
$account = Account::create([
    'account_number' => '1000000123',
    'user_id' => 123,
    'name' => 'NajmBahar Account',
    'type' => 'user',
    'balance' => 0,
]);

// دریافت حساب
$account = Account::where('account_number', '1000000123')->first();
```

### Transaction

تراکنش‌های مالی.

**جدول:** `najm_transactions`

**فیلدهای مهم:**
- `from_account_id`: حساب مبدا (nullable برای واریز از سیستم)
- `to_account_id`: حساب مقصد
- `amount`: مبلغ (integer)
- `type`: نوع تراکنش (`immediate`, `scheduled`, `fee`, `adjustment`)
- `status`: وضعیت (`pending`, `completed`, `failed`)
- `metadata`: اطلاعات اضافی (JSON)

**مثال استفاده:**
```php
use App\Modules\NajmBahar\Models\Transaction;

$transaction = Transaction::create([
    'from_account_id' => 1,
    'to_account_id' => 2,
    'amount' => 100,
    'type' => 'immediate',
    'status' => 'completed',
    'description' => 'Transfer',
    'metadata' => ['idempotency_key' => 'unique-key'],
]);
```

### LedgerEntry

ردیف‌های دفتر کل (Double-Entry).

**جدول:** `najm_ledger_entries`

**فیلدهای مهم:**
- `transaction_id`: شناسه تراکنش مرتبط
- `account_id`: شناسه حساب
- `amount`: مبلغ (مثبت برای credit، منفی برای debit)
- `entry_type`: نوع ردیف (`debit`, `credit`)

**مثال استفاده:**
```php
use App\Modules\NajmBahar\Models\LedgerEntry;

// ایجاد ردیف debit
LedgerEntry::create([
    'transaction_id' => 1,
    'account_id' => 1,
    'amount' => -100,
    'entry_type' => 'debit',
]);

// ایجاد ردیف credit
LedgerEntry::create([
    'transaction_id' => 1,
    'account_id' => 2,
    'amount' => 100,
    'entry_type' => 'credit',
]);
```

---

## سرویس‌ها

### AccountNumberService

تولید شماره حساب‌ها.

**متدها:**
- `makeMainAccountNumberForUser(int $userId): string`
- `makeSystemAccountNumber(): string`
- `makeSubAccountCode(string $mainAccountNumber, int $subIndex): string`

**مثال:**
```php
use App\Modules\NajmBahar\Services\AccountNumberService;

$userAccountNumber = AccountNumberService::makeMainAccountNumberForUser(123);
// نتیجه: "1000000123"

$systemAccountNumber = AccountNumberService::makeSystemAccountNumber();
// نتیجه: "0000000000"

$subAccountCode = AccountNumberService::makeSubAccountCode('0000000000', 1);
// نتیجه: "0000000000-001"
```

### AccountService

مدیریت حساب‌ها.

**متدها:**
- `createMainAccountForUser(int $userId, string $name = 'NajmBahar Account'): Account`

**مثال:**
```php
use App\Modules\NajmBahar\Services\AccountService;

$service = new AccountService();
$account = $service->createMainAccountForUser(123, 'My Account');
```

### TransactionService

مدیریت تراکنش‌ها (مهم‌ترین سرویس).

**متدها:**
- `transfer(string|null $fromAccountNumber, string $toAccountNumber, int $amount, string $description = null, array $meta = [], string|null $idempotencyKey = null): Transaction`

**ویژگی‌ها:**
- Atomic operations (DB transaction)
- Double-entry ledger entries
- Idempotency support
- Account locking برای جلوگیری از race condition
- بررسی موجودی کافی

**مثال:**
```php
use App\Modules\NajmBahar\Services\TransactionService;

$service = new TransactionService();

// انتقال از حساب کاربر به حساب دیگر
$transaction = $service->transfer(
    '1000000123',  // from
    '1000000456',  // to
    100,           // amount
    'Payment',     // description
    [],            // metadata
    'unique-key'   // idempotency key
);

// واریز از سیستم
$transaction = $service->transfer(
    null,          // from (system)
    '1000000123',  // to
    10000,         // amount
    'Initial funding'
);
```

**خطاهای ممکن:**
- `InvalidArgumentException`: مبلغ نامعتبر
- `RuntimeException`: حساب یافت نشد یا موجودی کافی نیست

---

## یکپارچه‌سازی با Legacy

### LegacyNajmAdapter

این adapter برای همگام‌سازی خودکار با سیستم قدیمی Spring استفاده می‌شود.

**متدها:**
- `onSpringCreated(Spring $spring): void`

**نحوه کار:**
1. هنگام ایجاد Spring جدید، Event Listener در `AppServiceProvider` فعال می‌شود
2. Job `ProcessSpringCreatedNajm` در صف قرار می‌گیرد
3. Adapter حساب NajmBahar ایجاد می‌کند
4. واریز اولیه 10000 بهار انجام می‌شود
5. کارمزد عضویت 12 بهار کسر می‌شود

**مثال:**
```php
use App\Models\Spring;
use App\Modules\NajmBahar\Adapters\LegacyNajmAdapter;

$spring = new Spring([
    'user_id' => 123,
    'name' => 'Test',
    'amount' => 0,
    'status' => 0,
]);
$spring->save();

// Adapter به صورت خودکار فراخوانی می‌شود
// یا می‌توانید دستی فراخوانی کنید:
LegacyNajmAdapter::onSpringCreated($spring);
```

---

## توسعه ویژگی‌های جدید

### افزودن نوع تراکنش جدید

1. **افزودن به enum در Transaction:**
```php
// در TransactionService یا middleware
$allowedTypes = ['immediate', 'scheduled', 'fee', 'adjustment', 'your_new_type'];
```

2. **افزودن منطق کسب‌وکار:**
```php
// در TransactionService
if ($type === 'your_new_type') {
    // منطق خاص
}
```

### افزودن کارمزد

```php
use App\Modules\NajmBahar\Services\TransactionService;

$service = new TransactionService();

// انتقال با کارمزد
$amount = 100;
$fee = 5; // 5 بهار کارمزد

// کسر کارمزد از حساب مبدا
$service->transfer(
    $fromAccountNumber,
    $feeAccountNumber,
    $fee,
    'Transaction fee'
);

// انتقال اصلی
$service->transfer(
    $fromAccountNumber,
    $toAccountNumber,
    $amount,
    'Main transfer'
);
```

### افزودن محدودیت‌ها

```php
// در TransactionService::transfer()
if ($amount > $maxTransferLimit) {
    throw new \RuntimeException('Amount exceeds transfer limit');
}

if ($dailyTransferCount >= $maxDailyTransfers) {
    throw new \RuntimeException('Daily transfer limit reached');
}
```

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

### نوشتن تست جدید

**مثال تست Unit:**
```php
namespace Tests\Unit;

use Tests\TestCase;
use App\Modules\NajmBahar\Services\YourService;

class YourServiceTest extends TestCase
{
    public function test_your_method()
    {
        $service = new YourService();
        $result = $service->yourMethod();
        
        $this->assertNotNull($result);
    }
}
```

**مثال تست Feature:**
```php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class YourFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_your_endpoint()
    {
        $response = $this->getJson('/api/your-endpoint');
        
        $response->assertStatus(200);
    }
}
```

---

## بهینه‌سازی و Performance

### Indexes

تمام جداول دارای index‌های مناسب هستند:
- `account_number` (unique)
- `user_id`
- `from_account_id`, `to_account_id`
- `transaction_id`
- `execute_at` (برای scheduled transactions)

### Query Optimization

**استفاده از Eager Loading:**
```php
// بد
$transactions = Transaction::all();
foreach ($transactions as $tx) {
    $fromAccount = Account::find($tx->from_account_id);
}

// خوب
$transactions = Transaction::with('fromAccount', 'toAccount')->get();
```

**استفاده از Pagination:**
```php
// برای لیست‌های بزرگ
$transactions = Transaction::paginate(25);
```

### Caching

برای حساب‌های پراستفاده:
```php
use Illuminate\Support\Facades\Cache;

$balance = Cache::remember("account_balance_{$accountNumber}", 60, function () use ($accountNumber) {
    return Account::where('account_number', $accountNumber)->value('balance');
});
```

### Database Transactions

همیشه از DB transactions برای عملیات مالی استفاده کنید:
```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    // عملیات مالی
});
```

---

## نکات مهم

### 1. Thread Safety

برای جلوگیری از race condition، از account locking استفاده می‌شود:
```php
$account = Account::where('account_number', $number)
    ->lockForUpdate()
    ->first();
```

### 2. Idempotency

همیشه از idempotency key برای تراکنش‌های مهم استفاده کنید:
```php
$idempotencyKey = 'payment-' . $paymentId . '-' . time();
$transaction = $service->transfer(..., $idempotencyKey);
```

### 3. Error Handling

همیشه خطاها را به درستی handle کنید:
```php
try {
    $transaction = $service->transfer(...);
} catch (\RuntimeException $e) {
    if ($e->getMessage() === 'Insufficient funds') {
        // handle insufficient funds
    } else {
        // handle other errors
    }
}
```

### 4. Logging

برای تراکنش‌های مهم، لاگ کنید:
```php
use Illuminate\Support\Facades\Log;

Log::info('Transaction completed', [
    'transaction_id' => $transaction->id,
    'amount' => $transaction->amount,
]);
```

---

## منابع بیشتر

- [مستندات API](./API_DOCUMENTATION.md)
- [README ماژول](./README.md)
- [Laravel Documentation](https://laravel.com/docs)

---

**آخرین بروزرسانی:** 2025-11-22

