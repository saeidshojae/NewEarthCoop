# راهنمای بازگشت به Layout قبلی (Rollback Guide)

## تغییرات اعمال شده

در صفحات **چت گروه** (`groups/chat.blade.php`) و **کامنت/گزارش** (`groups/comment.blade.php`):
- Header و Footer اصلی سایت حذف شده‌اند
- یک Header مینی با دکمه Back و منو اضافه شده است
- Layout جدید `layouts.chat` استفاده می‌شود

## نحوه بازگشت به حالت قبلی

اگر می‌خواهید به حالت قبلی (با Header و Footer اصلی) برگردید:

### 1. تغییر Layout در صفحه Chat

فایل: `resources/views/groups/chat.blade.php`

**خط 1 را تغییر دهید از:**
```blade
@extends('layouts.chat')
```

**به:**
```blade
@extends('layouts.unified')
```

### 2. تغییر Layout در صفحه Comment

فایل: `resources/views/groups/comment.blade.php`

**خط 1 را تغییر دهید از:**
```blade
@extends('layouts.chat')
```

**به:**
```blade
@extends('layouts.unified')
```

### 3. (اختیاری) حذف Layout جدید

اگر می‌خواهید فایل layout جدید را هم حذف کنید:
```bash
rm resources/views/layouts/chat.blade.php
```

## فایل‌های تغییر یافته

1. ✅ `resources/views/layouts/chat.blade.php` - **فایل جدید**
2. ✅ `resources/views/groups/chat.blade.php` - خط 1: `@extends('layouts.chat')`
3. ✅ `resources/views/groups/comment.blade.php` - خط 1: `@extends('layouts.chat')`

## مزایای Layout جدید

- ✅ فضای بیشتر برای محتوا
- ✅ UX بهتر برای صفحات چت
- ✅ Header مینی با navigation کامل
- ✅ منوی slide-in برای دسترسی به منوها

## نکات مهم

- Layout جدید (`layouts.chat`) شامل Header مینی و منوی slide-in است
- تمام قابلیت‌های navigation در منوی slide-in موجود است
- دکمه Back برای برگشت به صفحه قبل یا خانه
- دکمه Home (لوگو) برای رفتن به صفحه اصلی

