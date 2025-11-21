# راهنمای تست سیستم RBAC

## 1. اختصاص نقش به کاربر

### روش 1: از طریق Tinker (سریع)

```bash
php artisan tinker
```

سپس در tinker:

```php
// پیدا کردن کاربر
$user = \App\Models\User::find(1); // یا هر ID دیگری

// پیدا کردن نقش
$role = \App\Models\Role::where('slug', 'user-manager')->first();

// اختصاص نقش
$user->assignRole($role);

// یا به صورت مستقیم با slug
$user->assignRole('user-manager');

// بررسی نقش‌های کاربر
$user->roles;

// بررسی دسترسی
$user->hasPermission('users.view'); // باید true برگرداند
$user->hasPermission('blog.create'); // باید false برگرداند (چون user-manager نیست)
```

### روش 2: از طریق کد (برای تست)

می‌توانید یک route موقت ایجاد کنید:

```php
Route::get('/test-assign-role', function() {
    $user = \App\Models\User::find(1);
    $user->assignRole('user-manager');
    return 'Role assigned!';
});
```

---

## 2. تست در Route ها

### استفاده از PermissionMiddleware

```php
// در routes/web.php

// فقط کاربرانی که users.view دارند می‌توانند وارد شوند
Route::middleware(['admin', 'permission:users.view'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index']);
});

// یا برای چند دسترسی (یکی از آن‌ها کافی است)
Route::middleware(['admin', 'permission:users.view|users.create'])->group(function () {
    // ...
});
```

### تست کنید:

1. یک کاربر بدون نقش را تست کنید → باید خطای 403 بدهد
2. یک کاربر با نقش `user-manager` را تست کنید → باید اجازه دسترسی داشته باشد
3. یک کاربر با `is_admin = true` را تست کنید → باید اجازه دسترسی داشته باشد

---

## 3. تست در Views (Blade)

### استفاده از Blade Directives

```blade
{{-- فقط اگر دسترسی دارد، نمایش بده --}}
@hasPermission('users.create')
    <a href="{{ route('admin.users.create') }}">ایجاد کاربر</a>
@endhasPermission

{{-- چک کردن نقش --}}
@hasRole('user-manager')
    <div>شما مدیر کاربران هستید</div>
@endhasRole

{{-- چک کردن Super Admin --}}
@isSuperAdmin
    <div>شما Super Admin هستید</div>
@endisSuperAdmin
```

### تست کنید:

1. صفحه را با کاربر بدون نقش باز کنید → دکمه نمایش داده نمی‌شود
2. صفحه را با کاربر با نقش باز کنید → دکمه نمایش داده می‌شود

---

## 4. تست در Controller

```php
public function index()
{
    $user = auth()->user();
    
    // چک کردن دسترسی
    if (!$user->hasPermission('users.view')) {
        abort(403, 'شما دسترسی به این بخش را ندارید');
    }
    
    // یا
    if (!$user->hasRole('user-manager')) {
        abort(403);
    }
    
    // ...
}
```

---

## 5. تست کامل - سناریو

### مرحله 1: ایجاد یک کاربر تست

```php
// در tinker
$testUser = \App\Models\User::create([
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'first_name' => 'تست',
    'last_name' => 'کاربر',
    'phone' => '09123456789',
    'national_id' => '1234567890',
    'gender' => 'male',
    'birth_date' => '1990-01-01',
    'is_admin' => false
]);
```

### مرحله 2: اختصاص نقش

```php
$testUser->assignRole('user-manager');
```

### مرحله 3: لاگین با این کاربر

1. از سایت خارج شوید
2. با `test@example.com` و رمز `password` وارد شوید
3. به `/admin/users` بروید → باید اجازه دسترسی داشته باشد
4. به `/admin/blog/posts` بروید → باید خطای 403 بدهد (اگر route با permission محافظت شده باشد)

---

## 6. بررسی نقش‌ها و دسترسی‌ها

```php
// در tinker

// لیست همه نقش‌ها
\App\Models\Role::all();

// لیست همه دسترسی‌ها
\App\Models\Permission::all();

// دسترسی‌های یک نقش
$role = \App\Models\Role::where('slug', 'user-manager')->first();
$role->permissions;

// نقش‌های یک کاربر
$user = \App\Models\User::find(1);
$user->roles;

// همه دسترسی‌های یک کاربر (از طریق نقش‌ها)
$user->getAllPermissions();
```

---

## 7. تست سریع با یک Route

می‌توانید این route موقت را اضافه کنید:

```php
Route::get('/test-rbac', function() {
    $user = auth()->user();
    
    if (!$user) {
        return 'لطفا ابتدا وارد شوید';
    }
    
    return [
        'user' => $user->email,
        'is_admin' => $user->is_admin,
        'roles' => $user->roles->pluck('name'),
        'permissions' => $user->getAllPermissions()->pluck('name'),
        'has_users_view' => $user->hasPermission('users.view'),
        'has_blog_create' => $user->hasPermission('blog.create'),
    ];
})->middleware('auth');
```

سپس به `/test-rbac` بروید و نتیجه را ببینید.

---

## نکات مهم

1. **Super Admin**: کاربرانی که `is_admin = true` دارند یا نقش `super-admin` دارند، به همه دسترسی‌ها دسترسی دارند.

2. **Cache**: اگر تغییراتی دادید و کار نکرد، ممکن است نیاز به پاک کردن cache باشد:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

3. **Eager Loading**: برای بهتر شدن performance، در controller ها eager load کنید:
   ```php
   $user = User::with('roles.permissions')->find(1);
   ```

