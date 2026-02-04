# جزئیات فنی پروژه EarthCoop

## 📋 فهرست مطالب
- [ساختار دیتابیس](#ساختار-دیتابیس)
- [معماری سرویس‌ها](#معماری-سرویسها)
- [سیستم روت‌ها](#سیستم-روتها)
- [جزئیات ماژول Stock](#جزئیات-ماژول-stock)

---

## 🗄️ ساختار دیتابیس

### روابط بین جداول

#### **User Model - مرکز سیستم**
```
User (کاربر)
├── hasOne: Address (آدرس)
├── hasMany: Messages (پیام‌ها)
├── hasMany: Blogs (پست‌های وبلاگ)
├── hasMany: Comments (نظرات)
├── hasMany: Votes (آرا)
├── hasMany: UserExperience (تجربیات)
├── belongsToMany: Groups (گروه‌ها)
├── belongsToMany: OccupationalFields (شغل‌ها)
├── belongsToMany: ExperienceFields (تخصص‌ها)
└── hasOne: Wallet (کیف پول - ماژول Stock)
```

#### **Group Model - گروه‌های اجتماعی**
```
Group (گروه)
├── belongsToMany: Users (کاربران)
├── hasMany: Messages (پیام‌ها)
├── hasMany: Polls (نظرسنجی‌ها)
├── hasMany: Elections (انتخابات)
├── hasMany: Blogs (وبلاگ‌ها)
├── belongsTo: Address (آدرس)
├── belongsTo: OccupationalField (حوزه شغلی)
├── belongsTo: ExperienceField (حوزه تجربی)
└── belongsTo: AgeGroup (گروه سنی)
```

#### **Address Model - سلسله مراتب مکانی**
```
Address (آدرس)
├── belongsTo: Continent (قاره)
├── belongsTo: Country (کشور)
├── belongsTo: Province (استان)
├── belongsTo: County (شهرستان)
├── belongsTo: District (بخش)
├── belongsTo: City (شهر)
├── belongsTo: Rural (دهستان)
├── belongsTo: Region (منطقه)
├── belongsTo: Village (روستا)
├── belongsTo: Neighborhood (محله)
├── belongsTo: Street (خیابان)
└── belongsTo: Alley (کوچه)
```

### انواع گروه‌ها و ساختار آن‌ها

| نوع (group_type) | عنوان | فیلدهای مرتبط |
|------------------|-------|---------------|
| 0 | مجمع عمومی | location_level, address_id |
| 1 | صنفی/شغلی | location_level, address_id, specialty_id |
| 2 | تخصصی/تجربی | location_level, address_id, experience_id |
| 3 | سنی | location_level, address_id, age_group_id |
| 4 | جنسیتی | location_level, address_id, gender |

### سطوح مکانی (Location Levels)

```
Global (جهانی)
└── Continent (قاره)
    └── Country (کشور)
        └── Province (استان)
            └── County (شهرستان)
                └── District (بخش)
                    ├── City (شهر)
                    │   └── Region (منطقه)
                    │       └── Neighborhood (محله)
                    │           └── Street (خیابان)
                    │               └── Alley (کوچه)
                    └── Rural (دهستان)
                        └── Village (روستا)
                            └── Neighborhood (محله)
                                └── Street (خیابان)
                                    └── Alley (کوچه)
```

---

## 🔧 معماری سرویس‌ها

### GroupService - الگوریتم گروه‌بندی

#### **فرآیند ایجاد گروه برای کاربر:**

```php
1. استخراج اطلاعات کاربر
   ├── آدرس و سطوح مکانی
   ├── گروه سنی (محاسبه از تاریخ تولد)
   ├── جنسیت
   ├── لیست شغل‌ها (specialties)
   └── لیست تجربیات (experiences)

2. ایجاد گروه جهانی
   ├── مجمع عمومی جهانی
   ├── گروه‌های صنفی جهانی
   ├── گروه‌های تخصصی جهانی
   ├── گروه سنی جهانی
   └── گروه جنسیتی جهانی

3. ایجاد گروه‌ها برای هر سطح مکانی
   ├── مجمع عمومی
   ├── گروه‌های صنفی (برای هر شغل + والدین)
   ├── گروه‌های تخصصی (برای هر تجربه + والدین)
   ├── گروه سنی
   └── گروه جنسیتی

4. تعیین نقش در گروه
   ├── Role = 1 (مدیر) برای سطوح: محله، خیابان، کوچه
   └── Role = 0 (عضو) برای سایر سطوح
```

#### **مثال عملی:**

کاربری با مشخصات:
- **سن:** 30 سال (گروه سنی: جوانان)
- **جنسیت:** مرد
- **شغل:** برنامه‌نویس وب (Web Developer) - زیرمجموعه برنامه‌نویسی - زیرمجموعه IT
- **تجربه:** یادگیری ماشین (Machine Learning) - زیرمجموعه هوش مصنوعی - زیرمجموعه علوم کامپیوتر
- **آدرس:** ایران، تهران، منطقه 3، محله ونک، خیابان ولیعصر، کوچه شماره 5

**گروه‌های ایجاد شده (تعداد تقریبی: 80+ گروه):**

```
گروه‌های جهانی (5 گروه):
1. مجمع عمومی جهانی
2. مجمع صنفی فعالان IT جهانی
3. مجمع متخصصان علوم کامپیوتر جهانی
4. مجمع جوانان جهانی
5. گروه آقایان جهانی

برای هر سطح مکانی (ایران، تهران، ... تا کوچه) - 7 سطح × 11 نوع = 77 گروه:
- مجمع عمومی
- مجمع صنفی Web Developer
- مجمع صنفی برنامه‌نویسی
- مجمع صنفی IT
- مجمع متخصصان Machine Learning
- مجمع متخصصان هوش مصنوعی
- مجمع متخصصان علوم کامپیوتر
- مجمع جوانان
- گروه آقایان
```

---

## 🛣️ سیستم روت‌ها

### Public Routes (مسیرهای عمومی)

```
GET  /                              - صفحه خوش‌آمدگویی
POST /register/accept               - پذیرش شرایط
GET  /register                      - فرم ثبت‌نام اولیه
POST /register                      - ثبت‌نام اولیه

GET  /auth/google                   - شروع ورود با گوگل
GET  /auth/google/callback          - برگشت از گوگل
```

### Authenticated Routes (نیاز به ورود)

#### **Profile Management**
```
GET    /profile                     - نمایش پروفایل
GET    /profile/edit                - ویرایش پروفایل
GET    /profile/edit-oc             - ویرایش شغل و تخصص
PUT    /profile/update/general      - بروزرسانی اطلاعات عمومی
PUT    /profile/update/password     - تغییر رمز عبور
PUT    /profile/update/address      - بروزرسانی آدرس
DELETE /profile/document/{index}    - حذف مدرک
```

#### **Groups & Chat**
```
GET    /groups                      - لیست گروه‌ها
GET    /groups/{group}              - نمایش گروه
GET    /groups/{group}/chat         - چت گروه
GET    /groups/{group}/open         - باز کردن گروه
PUT    /groups/{group}              - بروزرسانی گروه
POST   /add-users-to-group          - افزودن کاربر به گروه
```

#### **Messages**
```
POST   /messages/send                    - ارسال پیام
POST   /messages/{message}/edit          - ویرایش پیام
GET    /messages/{message}/delete        - حذف پیام
POST   /messages/{message}/pin           - پین کردن پیام
POST   /messages/{message}/unpin         - حذف پین
POST   /messages/{message}/report        - گزارش پیام
GET    /groups/{group}/search            - جستجو در پیام‌ها
```

#### **Polls (نظرسنجی)**
```
POST   /groups/{group}/polls             - ایجاد نظرسنجی
POST   /polls/{poll}/vote                - رای دادن
GET    /polls/{poll}/results             - نتایج نظرسنجی
```

#### **Elections (انتخابات)**
```
POST   /groups/{group}/elections         - ایجاد انتخابات
POST   /elections/{election}/vote        - رای دادن
GET    /elections/{election}/results     - نتایج انتخابات
POST   /elections/{election}/delegate    - نمایندگی
```

#### **Blog & Comments**
```
POST   /groups/{group}/blogs             - ایجاد پست
POST   /blogs/{blog}/comments            - ثبت نظر
GET    /blogs/{blog}                     - نمایش پست
```

### Admin Routes (پنل مدیریت)

```
GET    /admin                            - داشبورد مدیریت
GET    /admin/users                      - مدیریت کاربران
GET    /admin/groups                     - مدیریت گروه‌ها
GET    /admin/announcements              - مدیریت اعلانات
GET    /admin/pages                      - مدیریت صفحات
GET    /admin/reports                    - گزارش‌های کاربران
POST   /admin/users/{user}/activate      - فعال‌سازی کاربر
DELETE /admin/users/{user}               - حذف کاربر
```

### Stock Module Routes

```
GET    /stock                            - داشبورد سهام
GET    /stock/wallet                     - کیف پول
POST   /stock/wallet/deposit             - واریز
POST   /stock/wallet/withdraw            - برداشت

GET    /stock/holdings                   - سهام‌های من
POST   /stock/holdings/buy               - خرید سهام
POST   /stock/holdings/sell              - فروش سهام

GET    /stock/auctions                   - لیست حراج‌ها
POST   /stock/auctions                   - ایجاد حراج
POST   /stock/auctions/{auction}/bid     - ثبت پیشنهاد
GET    /stock/auctions/{auction}/status  - وضعیت حراج
```

---

## 💰 جزئیات ماژول Stock

### معماری ماژول

```
Stock Module
├── Controllers
│   ├── AuctionController     - مدیریت حراج‌ها
│   ├── BidController         - مدیریت پیشنهادات
│   ├── HoldingController     - مدیریت سهام
│   ├── StockController       - مدیریت سهام
│   └── WalletController      - مدیریت کیف پول
├── Models
│   ├── Auction               - حراج‌ها
│   ├── Bid                   - پیشنهادات قیمت
│   ├── Holding               - سهام نگهداری شده
│   ├── HoldingTransaction    - تراکنش‌های سهام
│   ├── Stock                 - سهام
│   ├── StockTransaction      - تراکنش‌های سهام
│   ├── Wallet                - کیف پول
│   └── WalletTransaction     - تراکنش‌های مالی
├── Services
│   ├── AuctionService        - لجیک تجاری حراج
│   ├── HoldingService        - لجیک مدیریت سهام
│   └── WalletService         - لجیک کیف پول
├── Migrations
│   └── [Migration files]
└── Views
    └── [Not yet implemented]
```

### AuctionService - جزئیات پیاده‌سازی

#### **انواع حراج:**

**1. Single Winner Auction (حراج تک برنده)**
```php
- بالاترین پیشنهاد برنده می‌شود
- قیمت نهایی = پیشنهاد برنده
- مابقی پیشنهادات رد می‌شوند
```

**2. Uniform Price Auction (حراج قیمت یکسان)**
```php
- همه برندگان با یک قیمت خرید می‌کنند
- قیمت = آخرین پیشنهاد پذیرفته شده
- تعداد برندگان = lot_size
```

**3. Dutch Auction (حراج هلندی)**
```php
- قیمت از بالا شروع و کاهش می‌یابد
- اولین پیشنهاد‌دهنده برنده می‌شود
```

#### **فرآیند ثبت پیشنهاد:**

```php
function placeBid(user, auction, price, quantity) {
    1. بررسی وضعیت حراج (فعال باشد)
    2. بررسی محدوده قیمت (min_bid <= price <= max_bid)
    3. بررسی تعداد (quantity <= lot_size)
    4. بررسی موجودی کیف پول
    5. مسدودسازی موقت مبلغ (hold amount)
    6. ثبت پیشنهاد با وضعیت 'active'
    7. بازگشت اطلاعات bid
}
```

#### **فرآیند بستن حراج:**

```php
function closeAuction(auction) {
    1. تغییر وضعیت به 'settling'
    2. مرتب‌سازی پیشنهادات بر اساس قیمت (نزولی)
    3. تعیین برنده/برندگان بر اساس نوع حراج
    4. برای هر برنده:
       - کسر مبلغ از کیف پول
       - اضافه کردن سهام به Holding
       - تغییر وضعیت bid به 'won'
    5. برای بازندگان:
       - آزادسازی مبلغ مسدود شده
       - تغییر وضعیت bid به 'lost'
    6. تغییر وضعیت حراج به 'closed'
    7. بازگشت نتایج
}
```

### WalletService - مدیریت کیف پول

#### **عملیات اصلی:**

**1. واریز (Deposit)**
```php
function deposit(wallet, amount, description) {
    - افزایش موجودی
    - ثبت تراکنش با نوع 'deposit'
    - ثبت توضیحات
}
```

**2. برداشت (Withdraw)**
```php
function withdraw(wallet, amount, description) {
    - بررسی موجودی کافی
    - کاهش موجودی
    - ثبت تراکنش با نوع 'withdraw'
}
```

**3. مسدودسازی موقت (Hold)**
```php
function hold(wallet, amount, description, reference) {
    - بررسی موجودی قابل دسترس
    - افزایش held_balance
    - کاهش available_balance
    - ثبت تراکنش با نوع 'hold'
}
```

**4. آزادسازی (Release)**
```php
function release(wallet, amount, description) {
    - کاهش held_balance
    - افزایش available_balance
    - ثبت تراکنش با نوع 'release'
}
```

**5. نهایی‌سازی (Commit)**
```php
function commit(wallet, amount, description) {
    - کاهش held_balance
    - کاهش total_balance
    - ثبت تراکنش با نوع 'commit'
}
```

### HoldingService - مدیریت سهام

#### **خرید سهام:**
```php
function buyStock(user, stock, quantity, price) {
    1. بررسی موجودی کیف پول
    2. کسر مبلغ (quantity × price)
    3. ایجاد یا بروزرسانی Holding
    4. ثبت HoldingTransaction با نوع 'buy'
}
```

#### **فروش سهام:**
```php
function sellStock(user, stock, quantity, price) {
    1. بررسی موجودی سهام
    2. کاهش تعداد سهام
    3. افزایش موجودی کیف پول
    4. ثبت HoldingTransaction با نوع 'sell'
}
```

---

## 🔄 فرآیندهای کاربری

### ثبت‌نام کاربر جدید

```
1. صفحه خوش‌آمدگویی
   └─> پذیرش شرایط و قوانین

2. ثبت‌نام اولیه
   ├─> نام
   ├─> ایمیل
   ├─> رمز عبور
   └─> کد دعوت (اختیاری)

3. تایید ایمیل
   └─> ارسال لینک تایید به ایمیل

4. مرحله 1: اطلاعات هویتی
   ├─> نام خانوادگی
   ├─> تاریخ تولد
   ├─> جنسیت
   ├─> کد ملی
   └─> آپلود مدارک

5. مرحله 2: تخصص‌ها
   ├─> انتخاب شغل (چند انتخابی، سلسله مراتبی)
   └─> انتخاب تجربیات (چند انتخابی، سلسله مراتبی)

6. مرحله 3: آدرس
   ├─> انتخاب کشور
   ├─> انتخاب استان
   ├─> انتخاب شهر/روستا
   └─> تکمیل جزئیات آدرس

7. فعال‌سازی توسط مدیر
   └─> تایید مستندات و فعال‌سازی حساب

8. ورود به سیستم
   └─> ایجاد خودکار گروه‌ها
```

### فرآیند چت گروهی

```
1. کاربر وارد صفحه گروه می‌شود
   └─> بارگذاری آخرین پیام‌ها

2. ارسال پیام
   ├─> ذخیره در دیتابیس
   ├─> Broadcast از طریق Pusher
   └─> ارسال اعلان به اعضای آنلاین

3. دریافت پیام (Real-time)
   ├─> Laravel Echo دریافت می‌کند
   └─> بروزرسانی UI با Vue.js

4. قابلیت‌های پیام
   ├─> ویرایش (تا 15 دقیقه بعد از ارسال)
   ├─> حذف (تا 15 دقیقه بعد از ارسال)
   ├─> پین کردن (فقط مدیران)
   ├─> گزارش (همه کاربران)
   └─> واکنش (لایک، عشق، خنده، ...)
```

---

## 📊 مدل‌های داده - جزئیات

### User Model
```php
Fields:
- id, name, family, email, password
- national_code, birth_date, gender
- phone_number, avatar, last_seen
- is_active, activation_date
- email_verified_at

Relations:
- groups() - belongsToMany
- messages() - hasMany
- specialties() - belongsToMany (OccupationalField)
- experiences() - belongsToMany (ExperienceField)
- address() - hasOne
```

### Group Model
```php
Fields:
- id, name, description, avatar
- group_type (0-4)
- location_level (global, continent, country, ...)
- address_id
- specialty_id, experience_id, age_group_id
- gender
- is_active, last_activity

Relations:
- users() - belongsToMany
- messages() - hasMany
- polls() - hasMany
- elections() - hasMany
```

### Message Model
```php
Fields:
- id, group_id, user_id, parent_id
- content, is_edited, edited_at
- created_at, updated_at

Relations:
- user() - belongsTo
- group() - belongsTo
- parent() - belongsTo (Message)
- replies() - hasMany (Message)
- reactions() - hasMany
```

---

## 🎨 Frontend Architecture

### Vue.js Components (تخمینی)
```
- ChatComponent       - چت گروهی
- MessageComponent    - پیام واحد
- PollComponent       - نظرسنجی
- ElectionComponent   - انتخابات
- NotificationDropdown - اعلانات
- UserSearch          - جستجوی کاربر
- FileUpload          - آپلود فایل
```

### JavaScript Libraries
```javascript
// Real-time
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// UI Components
import bootstrap from 'bootstrap';
import Swiper from 'swiper';

// Editor
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

// Persian Date
import persianDate from 'persian-date';
import persianDatepicker from 'persian-datepicker';
```

---

## 🔒 Security Considerations

### احراز هویت
- Laravel Sanctum برای API
- Session-based برای Web
- Google OAuth 2.0
- Email Verification

### امنیت داده
- CSRF Protection
- XSS Prevention (با Blade escaping)
- SQL Injection Prevention (با Eloquent ORM)
- Password Hashing (bcrypt)

### دسترسی‌ها
- Middleware: AdminMiddleware
- Middleware: EnsureEmailIsVerified
- Policy-based Authorization (احتمالی)

---

**تاریخ:** 1404/08/05
**نسخه:** 1.0
