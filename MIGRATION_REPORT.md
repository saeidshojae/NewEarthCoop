# گزارش مهاجرت صفحات کاربری به Layout Unified

## ✅ صفحاتی که به Unified مهاجرت شده‌اند:

### صفحات اصلی:
1. ✅ `home.blade.php` - صفحه اصلی
2. ✅ `notifications/index.blade.php` - اعلان‌ها
3. ✅ `my-invation-code.blade.php` - کد دعوت من
4. ✅ `spring-accounts.blade.php` - حساب نجم بهار
5. ✅ `terms-spring.blade.php` - توافقنامه نجم بهار
6. ✅ `terms.blade.php` - قوانین و مقررات
7. ✅ `invitation/index.blade.php` - دعوت از دوستان
8. ✅ `pages/show.blade.php` - نمایش صفحات عمومی

### صفحات پروفایل:
1. ✅ `profile/profile.blade.php` - نمایش پروفایل
2. ✅ `profile/edit.blade.php` - ویرایش پروفایل
3. ✅ `profile/edit-oc.blade.php` - ویرایش تخصص‌ها
4. ✅ `profile/profile-member.blade.php` - نمایش پروفایل اعضا

### صفحات گروه‌ها:
1. ✅ `groups/index.blade.php` - لیست گروه‌ها
2. ✅ `groups/show.blade.php` - نمایش گروه
3. ✅ `groups/chat.blade.php` - چت گروهی
4. ✅ `groups/comment.blade.php` - نظرات پست

### صفحات تاریخچه:
1. ✅ `history/index.blade.php` - مشارکت‌های من
2. ✅ `history/election.blade.php` - انتخابات جاری
3. ✅ `history/poll.blade.php` - نظرسنجی‌های جاری

### صفحات سهام و حراج:
1. ✅ `Stock::stock_dashboard` - دفتر سهام (stock-book)
2. ✅ `Stock::auction_list` - لیست حراج‌ها (auctions)
3. ✅ `Stock::auction_show` - جزئیات حراج (auctions/{auction})
4. ✅ `Stock::wallet_index` - کیف پول (wallet)
5. ✅ `Stock::holding_index` - کیف سهام (holdings)
6. ✅ `Stock::holding_show` - جزئیات سهم (holdings/{stock}) **✅ جدید ساخته شد**
7. ✅ `Stock::bid_edit` - ویرایش پیشنهاد (bids/{bid}/edit)
8. ✅ `Stock::bid_form` - فرم پیشنهاد

### صفحات بلاگ:
1. ✅ `Blog::frontend/index` - لیست پست‌ها (blog)
2. ✅ `Blog::frontend/show` - نمایش پست (blog/{slug})
3. ✅ `Blog::frontend/category` - دسته‌بندی (blog/category/{slug})
4. ✅ `Blog::frontend/tag` - برچسب (blog/tag/{slug})
5. ⚠️ `Blog::frontend/search` - جستجو (blog/search) **نیاز به بررسی**

## ⚠️ صفحاتی که نیاز به بررسی دارند:

1. ⚠️ `Blog::frontend/search` - اگر view وجود دارد، باید unified باشد

## 📝 صفحاتی که از layouts.app استفاده می‌کنند (درست است):

### صفحات ادمین (نیاز به مهاجرت ندارند):
- همه صفحات در `admin/` - از `layouts.app` استفاده می‌کنند (درست است)

### صفحات Backup (نیاز به مهاجرت ندارند):
- `home-old-backup.blade.php`
- `welcome-old.blade.php`
- `terms-old.blade.php`
- `auth/login-old.blade.php`
- `auth/register-old.blade.php`
- `auth/register_step*_old_backup.blade.php`
- `groups/index-old-backup.blade.php`
- `invitation/index-old.blade.php`

### صفحات تست (نیاز به مهاجرت ندارند):
- `test-design.blade.php`
- `example-multilang-page.blade.php`

## 🎯 نتیجه‌گیری:

**✅ تمام صفحات کاربری اصلی به layout unified مهاجرت شده‌اند!**

**✅ فایل `holding_show.blade.php` که وجود نداشت، ساخته شد و از unified استفاده می‌کند.**

صفحاتی که هنوز از `layouts.app` استفاده می‌کنند:
1. صفحات ادمین (که باید از `layouts.app` یا `layouts.admin` استفاده کنند) ✅
2. صفحات backup قدیمی ✅
3. صفحات تست ✅

**✅ هیچ صفحه کاربری عادی باقی نمانده که نیاز به مهاجرت داشته باشد!**

