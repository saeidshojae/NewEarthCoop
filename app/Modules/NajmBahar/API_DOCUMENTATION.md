# 📚 مستندات API ماژول نجم بهار

## فهرست مطالب

1. [مقدمه](#مقدمه)
2. [احراز هویت](#احراز-هویت)
3. [Endpoints](#endpoints)
4. [کدهای خطا](#کدهای-خطا)
5. [مثال‌های استفاده](#مثال‌های-استفاده)

---

## مقدمه

API ماژول نجم بهار برای مدیریت حساب‌های مالی و تراکنش‌ها استفاده می‌شود. تمام endpoint‌ها در مسیر `/api/najm-bahar` قرار دارند.

### Base URL
```
https://your-domain.com/api/najm-bahar
```

### فرمت پاسخ
تمام پاسخ‌ها به صورت JSON هستند.

---

## احراز هویت

برای استفاده از endpoint‌های مربوط به تراکنش‌ها، نیاز به احراز هویت با Laravel Sanctum دارید.

### دریافت Token

```http
POST /api/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password"
}
```

### استفاده از Token

```http
Authorization: Bearer {your-token}
```

---

## Endpoints

### 1. ایجاد حساب جدید

ایجاد حساب اصلی برای کاربر.

**Endpoint:** `POST /api/najm-bahar/accounts`

**Authentication:** ❌ نیاز ندارد

**Request Body:**
```json
{
    "user_id": 123
}
```

**Response (201 Created):**
```json
{
    "account": {
        "id": 1,
        "account_number": "1000000123",
        "user_id": 123,
        "name": "NajmBahar Account",
        "type": "user",
        "balance": 0,
        "status": 1,
        "created_at": "2025-11-22T10:00:00.000000Z",
        "updated_at": "2025-11-22T10:00:00.000000Z"
    }
}
```

**Validation Errors (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "user_id": ["The user id field is required."]
    }
}
```

---

### 2. دریافت موجودی حساب

دریافت موجودی یک حساب با شماره حساب.

**Endpoint:** `GET /api/najm-bahar/accounts/{accountNumber}/balance`

**Authentication:** ❌ نیاز ندارد

**Parameters:**
- `accountNumber` (path): شماره حساب (10 رقم)

**Response (200 OK):**
```json
{
    "balance": 5000
}
```

**Response (404 Not Found):**
```json
{
    "message": "not found"
}
```

---

### 3. انتقال فوری

انجام تراکنش فوری بین دو حساب.

**Endpoint:** `POST /api/najm-bahar/transactions/transfer`

**Authentication:** ✅ نیاز دارد (Sanctum)

**Request Body:**
```json
{
    "to_account_number": "1000000002",
    "amount": 100,
    "description": "انتقال وجه",
    "idempotency_key": "unique-key-123" // اختیاری
}
```

**Response (201 Created):**
```json
{
    "transaction": {
        "id": 1,
        "from_account_id": 1,
        "to_account_id": 2,
        "amount": 100,
        "type": "immediate",
        "status": "completed",
        "description": "انتقال وجه",
        "metadata": {
            "idempotency_key": "unique-key-123"
        },
        "created_at": "2025-11-22T10:00:00.000000Z",
        "updated_at": "2025-11-22T10:00:00.000000Z"
    }
}
```

**Response (200 OK) - در صورت استفاده از idempotency key تکراری:**
```json
{
    "transaction": {
        "id": 1,
        // ... همان تراکنش قبلی
    }
}
```

**Validation Errors (422):**
```json
{
    "message": "Insufficient funds"
}
```

یا

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "to_account_number": ["The to account number field is required."],
        "amount": ["The amount must be at least 1."]
    }
}
```

**Response (403 Forbidden):**
```json
{
    "message": "source account not found or unauthorized"
}
```

---

### 4. زمان‌بندی تراکنش

ایجاد تراکنش زمان‌بندی شده برای اجرا در آینده.

**Endpoint:** `POST /api/najm-bahar/transactions/schedule`

**Authentication:** ✅ نیاز دارد (Sanctum)

**Request Body:**
```json
{
    "to_account_number": "1000000002",
    "amount": 100,
    "execute_at": "2025-12-01T10:00:00Z",
    "description": "تراکنش زمان‌بندی شده"
}
```

**Response (201 Created):**
```json
{
    "scheduled": true,
    "transaction": {
        "id": 2,
        "from_account_id": 1,
        "to_account_id": null,
        "amount": 100,
        "type": "scheduled",
        "status": "pending",
        "scheduled_at": "2025-12-01T10:00:00.000000Z",
        "description": "تراکنش زمان‌بندی شده",
        "created_at": "2025-11-22T10:00:00.000000Z",
        "updated_at": "2025-11-22T10:00:00.000000Z"
    }
}
```

**Validation Errors (422):**
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "execute_at": ["The execute at must be a valid date."]
    }
}
```

---

### 5. لیست تراکنش‌ها

دریافت لیست تراکنش‌های حساب کاربر.

**Endpoint:** `GET /api/najm-bahar/transactions`

**Authentication:** ✅ نیاز دارد (Sanctum)

**Query Parameters:**
- `page` (optional): شماره صفحه (پیش‌فرض: 1)
- `per_page` (optional): تعداد در هر صفحه (پیش‌فرض: 25)

**Response (200 OK):**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "from_account_id": 1,
            "to_account_id": 2,
            "amount": 100,
            "type": "immediate",
            "status": "completed",
            "description": "انتقال وجه",
            "created_at": "2025-11-22T10:00:00.000000Z",
            "updated_at": "2025-11-22T10:00:00.000000Z"
        }
    ],
    "first_page_url": "http://localhost/api/najm-bahar/transactions?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost/api/najm-bahar/transactions?page=1",
    "links": [...],
    "next_page_url": null,
    "path": "http://localhost/api/najm-bahar/transactions",
    "per_page": 25,
    "prev_page_url": null,
    "to": 1,
    "total": 1
}
```

---

### 6. دریافت دفتر کل (Ledger)

دریافت تمام ردیف‌های دفتر کل یک حساب.

**Endpoint:** `GET /api/najm-bahar/ledger/{accountNumber}`

**Authentication:** ✅ نیاز دارد (Sanctum)

**Parameters:**
- `accountNumber` (path): شماره حساب

**Query Parameters:**
- `page` (optional): شماره صفحه
- `per_page` (optional): تعداد در هر صفحه (پیش‌فرض: 50)

**Response (200 OK):**
```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "transaction_id": 1,
            "account_id": 1,
            "amount": -100,
            "entry_type": "debit",
            "meta": null,
            "created_at": "2025-11-22T10:00:00.000000Z",
            "updated_at": "2025-11-22T10:00:00.000000Z"
        },
        {
            "id": 2,
            "transaction_id": 1,
            "account_id": 2,
            "amount": 100,
            "entry_type": "credit",
            "meta": null,
            "created_at": "2025-11-22T10:00:00.000000Z",
            "updated_at": "2025-11-22T10:00:00.000000Z"
        }
    ],
    "first_page_url": "...",
    "from": 1,
    "last_page": 1,
    "per_page": 50,
    "to": 2,
    "total": 2
}
```

**Response (404 Not Found):**
```json
{
    "message": "not found"
}
```

---

## کدهای خطا

| کد | معنی | توضیحات |
|----|------|---------|
| 200 | OK | درخواست موفق |
| 201 | Created | منبع جدید ایجاد شد |
| 401 | Unauthorized | نیاز به احراز هویت |
| 403 | Forbidden | دسترسی غیرمجاز |
| 404 | Not Found | منبع یافت نشد |
| 422 | Unprocessable Entity | خطای اعتبارسنجی یا منطق کسب‌وکار |
| 500 | Internal Server Error | خطای سرور |

---

## مثال‌های استفاده

### مثال 1: ایجاد حساب و انتقال وجه

```bash
# 1. دریافت Token
TOKEN=$(curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}' \
  | jq -r '.token')

# 2. ایجاد حساب
curl -X POST http://localhost:8000/api/najm-bahar/accounts \
  -H "Content-Type: application/json" \
  -d '{"user_id": 123}'

# 3. دریافت موجودی
curl -X GET http://localhost:8000/api/najm-bahar/accounts/1000000123/balance

# 4. انتقال وجه
curl -X POST http://localhost:8000/api/najm-bahar/transactions/transfer \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "to_account_number": "1000000002",
    "amount": 100,
    "description": "انتقال وجه تست"
  }'
```

### مثال 2: استفاده از Idempotency Key

```bash
IDEMPOTENCY_KEY="transfer-$(date +%s)"

# اولین درخواست
curl -X POST http://localhost:8000/api/najm-bahar/transactions/transfer \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d "{
    \"to_account_number\": \"1000000002\",
    \"amount\": 100,
    \"idempotency_key\": \"$IDEMPOTENCY_KEY\"
  }"

# درخواست تکراری (بازگشت همان تراکنش)
curl -X POST http://localhost:8000/api/najm-bahar/transactions/transfer \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d "{
    \"to_account_number\": \"1000000002\",
    \"amount\": 100,
    \"idempotency_key\": \"$IDEMPOTENCY_KEY\"
  }"
```

### مثال 3: زمان‌بندی تراکنش

```bash
curl -X POST http://localhost:8000/api/najm-bahar/transactions/schedule \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "to_account_number": "1000000002",
    "amount": 500,
    "execute_at": "2025-12-01T10:00:00Z",
    "description": "پرداخت ماهانه"
  }'
```

### مثال 4: دریافت لیست تراکنش‌ها

```bash
curl -X GET "http://localhost:8000/api/najm-bahar/transactions?page=1&per_page=10" \
  -H "Authorization: Bearer $TOKEN"
```

---

## نکات مهم

### 1. شماره حساب‌ها
- سیستم: `0000000000`
- کاربران: `1000000001` تا `9999999999` (فرمت: `1000000{userId}`)

### 2. واحد پول
تمام مبالغ به کوچک‌ترین واحد (بهار) هستند. برای مثال:
- 1.00 بهار = `1`
- 100.50 بهار = `10050`

### 3. Idempotency
برای جلوگیری از تراکنش‌های تکراری، می‌توانید از `idempotency_key` استفاده کنید. اگر تراکنشی با همان کلید قبلاً انجام شده باشد، همان تراکنش قبلی برگردانده می‌شود.

### 4. تراکنش‌های زمان‌بندی شده
تراکنش‌های زمان‌بندی شده به صورت خودکار توسط command زیر پردازش می‌شوند:
```bash
php artisan najm-bahar:process-scheduled
```
این command باید در cron job تنظیم شود.

### 5. Double-Entry Accounting
تمام تراکنش‌ها به صورت Double-Entry ثبت می‌شوند. برای هر تراکنش، دو ردیف در دفتر کل ایجاد می‌شود:
- یک `debit` برای حساب مبدا
- یک `credit` برای حساب مقصد

---

## پشتیبانی

برای سوالات و مشکلات، لطفاً با تیم توسعه تماس بگیرید.

**آخرین بروزرسانی:** 2025-11-22

