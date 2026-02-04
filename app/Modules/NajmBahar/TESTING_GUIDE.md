# 🧪 راهنمای تست - ماژول نجم بهار

## فهرست مطالب

1. [اجرای تست‌ها](#اجرای-تست‌ها)
2. [ساختار تست‌ها](#ساختار-تست‌ها)
3. [نوشتن تست جدید](#نوشتن-تست-جدید)
4. [Coverage](#coverage)

---

## اجرای تست‌ها

### اجرای تمام تست‌های NajmBahar

```bash
php artisan test --filter NajmBahar
```

### اجرای تست‌های Unit

```bash
# AccountNumberService
php artisan test tests/Unit/AccountNumberServiceTest.php

# AccountService
php artisan test tests/Unit/AccountServiceTest.php
```

### اجرای تست‌های Feature

```bash
# TransactionService
php artisan test tests/Feature/TransactionServiceTest.php

# LegacyNajmAdapter
php artisan test tests/Feature/LegacyNajmAdapterTest.php

# API Controllers
php artisan test tests/Feature/NajmBaharApiTest.php

# Scheduled Transactions
php artisan test tests/Feature/ScheduledTransactionTest.php
```

### اجرای تست خاص

```bash
php artisan test --filter test_atomic_transfer_and_ledger_and_idempotency
```

---

## ساختار تست‌ها

### تست‌های Unit

#### AccountNumberServiceTest
- ✅ `test_make_main_account_number_for_user`
- ✅ `test_system_account_number`
- ✅ `test_make_sub_account_code`

#### AccountServiceTest
- ✅ `test_create_main_account_for_user`
- ✅ `test_create_main_account_with_default_name`
- ✅ `test_create_account_idempotency`
- ✅ `test_create_transaction`

### تست‌های Feature

#### TransactionServiceTest
- ✅ `test_atomic_transfer_and_ledger_and_idempotency`

#### LegacyNajmAdapterTest
- ✅ `test_adapter_creates_accounts_and_funds_idempotent`

#### NajmBaharApiTest
- ✅ `test_create_account_endpoint`
- ✅ `test_get_balance_endpoint`
- ✅ `test_get_balance_not_found`
- ✅ `test_transfer_endpoint_requires_authentication`
- ✅ `test_transfer_endpoint`
- ✅ `test_transfer_insufficient_funds`
- ✅ `test_transfer_idempotency`
- ✅ `test_schedule_transaction`
- ✅ `test_list_transactions`
- ✅ `test_get_ledger`

#### ScheduledTransactionTest
- ✅ `test_process_scheduled_transactions_command`
- ✅ `test_scheduled_transaction_not_due_yet`
- ✅ `test_scheduled_transaction_failure_retry`
- ✅ `test_scheduled_transaction_max_attempts`

---

## نوشتن تست جدید

### تست Unit

```php
<?php

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
        $this->assertEquals('expected', $result);
    }
}
```

### تست Feature

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class YourFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Run migrations if needed
        $paths = [
            'database/migrations/2025_11_22_000001_create_najm_accounts_table.php',
        ];

        foreach ($paths as $path) {
            Artisan::call('migrate', [
                '--path' => $path,
                '--env' => 'testing',
                '--force' => true,
            ]);
        }
    }

    public function test_your_endpoint()
    {
        $response = $this->getJson('/api/your-endpoint');
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => []
        ]);
    }
}
```

### نکات مهم

1. **استفاده از RefreshDatabase**: برای تست‌های Feature که با دیتابیس کار می‌کنند
2. **Migration در setUp**: برای تست‌هایی که نیاز به جداول خاص دارند
3. **Assertions مناسب**: از assertions مناسب برای هر تست استفاده کنید

---

## Coverage

### Coverage فعلی

| بخش | Coverage |
|-----|----------|
| AccountNumberService | 100% |
| AccountService | 100% |
| TransactionService | 100% |
| LegacyNajmAdapter | 100% |
| API Controllers | 100% |
| Scheduled Transactions | 100% |

### بررسی Coverage

```bash
# با PHPUnit
php artisan test --coverage

# با Xdebug
php artisan test --coverage --min=80
```

---

## تست‌های Integration

### تست یکپارچه‌سازی با Legacy

```php
public function test_spring_creation_triggers_najm_adapter()
{
    // Create Spring
    $spring = Spring::create([...]);
    
    // Verify Najm account created
    $this->assertDatabaseHas('najm_accounts', [
        'user_id' => $spring->user_id
    ]);
    
    // Verify initial funding
    $account = Account::where('user_id', $spring->user_id)->first();
    $this->assertEquals(9988, $account->balance); // 10000 - 12
}
```

---

## تست‌های Performance

### تست سرعت تراکنش

```php
public function test_transfer_performance()
{
    $start = microtime(true);
    
    for ($i = 0; $i < 100; $i++) {
        $service->transfer(...);
    }
    
    $duration = microtime(true) - $start;
    $this->assertLessThan(5, $duration); // Should complete in less than 5 seconds
}
```

---

## نکات مهم

### 1. Isolation

هر تست باید مستقل باشد و به تست‌های دیگر وابسته نباشد.

### 2. Cleanup

پس از هر تست، دیتابیس باید پاک شود (با RefreshDatabase).

### 3. Realistic Data

از داده‌های واقعی و معتبر استفاده کنید.

### 4. Error Cases

تست‌های خطا را هم بنویسید:
- موجودی ناکافی
- حساب یافت نشد
- داده‌های نامعتبر

---

## اجرای تست‌ها در CI/CD

### GitHub Actions

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
          
      - name: Install Dependencies
        run: composer install
        
      - name: Run Tests
        run: php artisan test --filter NajmBahar
```

---

## منابع

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Developer Guide](./DEVELOPER_GUIDE.md)

---

**آخرین بروزرسانی:** 2025-11-22

