# 🧹 لیست فایل‌ها و پوشه‌های پیشنهادی برای حذف

این سند شامل لیست کامل فایل‌ها و پوشه‌هایی است که پس از بررسی دقیق پروژه، برای حذف پیشنهاد می‌شوند.

**⚠️ هشدار:** قبل از حذف هر فایل، لطفاً مطمئن شوید که دیگر استفاده نمی‌شود.

---

## 📋 دسته‌بندی فایل‌ها

### 🔴 دسته 1: فایل‌های Backup و Old (حذف با اطمینان بالا)

این فایل‌ها نسخه‌های قدیمی یا backup هستند که دیگر استفاده نمی‌شوند:

#### فایل‌های Backup:
- ✅ `resources/views/home.blade.php.backup`
- ✅ `resources/views/welcome.blade.php.backup`

#### فایل‌های Old:
- ✅ `resources/views/home-old-backup.blade.php`
- ✅ `resources/views/welcome-old.blade.php`
- ✅ `resources/views/terms-old.blade.php`
- ✅ `resources/views/groups/index-old-backup.blade.php`
- ✅ `resources/views/invitation/index-old.blade.php`
- ✅ `resources/views/auth/login-old.blade.php`
- ✅ `resources/views/auth/register-old.blade.php`
- ✅ `resources/views/auth/register_step1_old_backup.blade.php`
- ✅ `resources/views/auth/register_step2_old_backup.blade.php`
- ✅ `resources/views/auth/register_step3_old_backup.blade.php`

**تعداد:** 11 فایل

---

### 🟠 دسته 2: فایل‌های موقت (Temp Files)

این فایل‌ها برای تست یا کار موقت ایجاد شده‌اند:

- ✅ `temp_old_chat.blade.php`
- ✅ `temp_location_original.blade.php`
- ✅ `f.blade.php` (فایل تست با محتوای فرم وام)
- ✅ `dummy` (فایل خالی Jupyter Notebook)

**تعداد:** 4 فایل

---

### 🟡 دسته 3: فایل‌های تست (Test Files)

این فایل‌ها برای تست ایجاد شده‌اند و در production استفاده نمی‌شوند:

#### فایل‌های تست در public:
- ✅ `public/test-dark-mode.html`
- ✅ `public/test-encoding.php`

#### فایل‌های تست در resources/views:
- ✅ `resources/views/test-design.blade.php`
- ✅ `resources/views/test-unified-layout.blade.php` (⚠️ در routes/web.php استفاده می‌شود - بررسی کنید)

#### اسکریپت‌های تست:
- ✅ `test_api_regions.php`
- ✅ `test_location_hierarchy.php`

**تعداد:** 6 فایل (5 فایل قابل حذف، 1 فایل نیاز به بررسی)

---

### 🔵 دسته 4: فایل‌های SQL Backup

این فایل‌ها backup دیتابیس هستند و نباید در repository باشند:

- ✅ `ybwztpvr_earth (7).sql` (فایل backup دیتابیس - حتماً حذف شود)
- ⚠️ `import_locations_only.sql` (اگر دیگر استفاده نمی‌شود، حذف شود)

**تعداد:** 2 فایل

---

### 🟢 دسته 5: پوشه‌های بلااستفاده

#### پوشه __MACOSX:
- ✅ `__MACOSX/` (تمام محتوا) - این پوشه توسط macOS ایجاد می‌شود و کاملاً بلااستفاده است

#### پوشه New ui:
- ✅ `New ui/` (تمام محتوا) - این پوشه شامل فایل‌های HTML استاتیک است که در کد استفاده نمی‌شوند:
  - `about.html`
  - `blog.html`
  - `chat.blade.php`
  - `contact.html`
  - `cooperation.html`
  - `davate doostan.html`
  - `entekhabat.html`
  - `form.html`
  - `hamkari.html`
  - `help.html`
  - `index.html`
  - `login.html`
  - `main.html`
  - `mosharekat.html`
  - `my group.html`
  - `najm bahar.html`
  - `nazarsanjiha.html`
  - `profile.html`
  - `signup.html`
  - `terms-and-conditions.html`

**تعداد:** 2 پوشه کامل

---

### 🟣 دسته 6: فایل‌های Duplicate یا بلااستفاده

#### فایل‌های duplicate در resources/views:
- ✅ `resources/views/home-new.blade.php` (اگر `home.blade.php` استفاده می‌شود)
- ✅ `resources/views/home-complete.blade.php` (اگر `home.blade.php` استفاده می‌شود)
- ✅ `resources/views/welcome-new.blade.php` (اگر `welcome.blade.php` استفاده می‌شود)
- ✅ `resources/views/welcome-old.blade.php` (تکراری با دسته 1)

#### فایل‌های دیگر:
- ✅ `idex.js` (احتمالاً تایپو - بررسی کنید)
- ✅ `public/error_log` (فایل لاگ خطا - نباید در repository باشد)

**تعداد:** 6 فایل

---

### 🔴 دسته 7: Migration Files با پسوند .skip

این فایل‌ها migration‌های skip شده هستند:

- ⚠️ `database/migrations/2024_04_22_000001_create_reported_messages_table.php.skip`
- ⚠️ `database/migrations/2025_03_14_212321_add_description_to_groups_table.php.skip`

**نکته:** این فایل‌ها ممکن است برای مرجع نگه داشته شده‌باشند. اگر دیگر نیاز ندارید، حذف کنید.

**تعداد:** 2 فایل

---

### 🟠 دسته 8: اسکریپت‌های Check و Artisan موقت

این اسکریپت‌ها برای بررسی و تست ایجاد شده‌اند:

#### اسکریپت‌های Check:
- ⚠️ `check-user.php`
- ⚠️ `check_addresses_structure.php`
- ⚠️ `check_groups_encoding.php`
- ⚠️ `check_ids.php`
- ⚠️ `check_tehran_regions.php`

#### اسکریپت‌های Artisan موقت:
- ⚠️ `artisan-check-users-ids.php`
- ⚠️ `artisan-inspect-users.php`
- ⚠️ `artisan-scan-stock.php`

#### اسکریپت‌های Import:
- ⚠️ `import_locations.php`
- ⚠️ `extract_location_data.php`

**نکته:** اگر این اسکریپت‌ها دیگر استفاده نمی‌شوند، می‌توانید حذف کنید. اما اگر برای maintenance استفاده می‌شوند، نگه دارید.

**تعداد:** 11 فایل

---

### 🟡 دسته 9: پوشه group-chat-redesign

- ⚠️ `group-chat-redesign/` - این پوشه شامل یک پروژه TypeScript/Vite جداگانه است. اگر دیگر استفاده نمی‌شود، حذف کنید.

**تعداد:** 1 پوشه کامل

---

## 📊 خلاصه آمار

| دسته | تعداد فایل/پوشه | اولویت حذف |
|------|----------------|------------|
| Backup و Old | 11 فایل | 🔴 بالا |
| فایل‌های موقت | 4 فایل | 🔴 بالا |
| فایل‌های تست | 6 فایل | 🟠 متوسط |
| فایل‌های SQL Backup | 2 فایل | 🔴 بالا |
| پوشه __MACOSX | 1 پوشه کامل | 🔴 بالا |
| پوشه New ui | 1 پوشه (20 فایل) | 🟠 متوسط |
| فایل‌های Duplicate | 6 فایل | 🟠 متوسط |
| Migration .skip | 2 فایل | 🟡 پایین |
| اسکریپت‌های Check | 11 فایل | 🟡 پایین |
| group-chat-redesign | 1 پوشه | 🟡 پایین |

**جمع کل:** حدود **63+ فایل و 3 پوشه** برای بررسی و احتمالاً حذف

---

## ✅ توصیه‌های نهایی

### حذف فوری (بدون نیاز به بررسی بیشتر):
1. ✅ پوشه `__MACOSX/` - کاملاً بلااستفاده
2. ✅ فایل `ybwztpvr_earth (7).sql` - backup دیتابیس
3. ✅ فایل `public/error_log` - فایل لاگ
4. ✅ تمام فایل‌های `.backup` و `-old` در `resources/views/`
5. ✅ فایل‌های `temp_*.blade.php`

### بررسی قبل از حذف:
1. ⚠️ پوشه `New ui/` - بررسی کنید که آیا در جایی استفاده می‌شود
2. ⚠️ فایل‌های `home-new.blade.php` و `home-complete.blade.php` - بررسی کنید کدام استفاده می‌شود
3. ⚠️ فایل `test-unified-layout.blade.php` - در routes استفاده می‌شود
4. ⚠️ اسکریپت‌های `check-*.php` و `artisan-*.php` - اگر برای maintenance استفاده می‌شوند، نگه دارید

### نگه داشتن (برای مرجع):
1. 📚 فایل‌های `.skip` در migrations - اگر برای مرجع نگه داشته شده‌اند
2. 📚 فایل `LEGACY_FILES_REFERENCE.md` - این فایل خودش راهنمای فایل‌های قدیمی است

---

## 🛠️ دستورات پیشنهادی برای حذف

### Windows (PowerShell):
```powershell
# حذف پوشه __MACOSX
Remove-Item -Recurse -Force "__MACOSX"

# حذف فایل‌های backup
Remove-Item "resources\views\*.backup"
Remove-Item "resources\views\**\*-old*.blade.php"
Remove-Item "resources\views\**\*_old*.blade.php"

# حذف فایل‌های موقت
Remove-Item "temp_*.blade.php"
Remove-Item "f.blade.php"
Remove-Item "dummy"

# حذف فایل‌های SQL
Remove-Item "ybwztpvr_earth (7).sql"

# حذف فایل‌های تست
Remove-Item "public\test-*.html"
Remove-Item "public\test-*.php"
Remove-Item "test_*.php"

# حذف error_log
Remove-Item "public\error_log"
```

### Linux/Mac:
```bash
# حذف پوشه __MACOSX
rm -rf __MACOSX

# حذف فایل‌های backup
find resources/views -name "*.backup" -delete
find resources/views -name "*-old*.blade.php" -delete
find resources/views -name "*_old*.blade.php" -delete

# حذف فایل‌های موقت
rm -f temp_*.blade.php f.blade.php dummy

# حذف فایل‌های SQL
rm -f "ybwztpvr_earth (7).sql"

# حذف فایل‌های تست
rm -f public/test-*.html public/test-*.php test_*.php

# حذف error_log
rm -f public/error_log
```

---

## 📝 یادداشت‌های مهم

1. **قبل از حذف:** همیشه یک backup از پروژه بگیرید
2. **بررسی Git:** قبل از حذف، بررسی کنید که آیا فایل‌ها در Git commit شده‌اند یا نه
3. **بررسی Routes:** فایل‌هایی که در `routes/web.php` یا `routes/api.php` استفاده می‌شوند را حذف نکنید
4. **بررسی Controllers:** فایل‌های view که در controllers استفاده می‌شوند را حذف نکنید

---

**تاریخ بررسی:** 2025-01-XX
**نسخه:** 1.0.0



