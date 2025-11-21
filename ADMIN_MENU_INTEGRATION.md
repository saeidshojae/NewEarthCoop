# افزودن نجم‌هدا به منوی پنل ادمین

## روش 1: اضافه کردن به منوی کناری (Sidebar)

اگر در پنل ادمین یک منوی کناری دارید، این کد را اضافه کنید:

```blade
<!-- در فایل sidebar.blade.php یا layout ادمین -->

<li class="nav-item">
    <a class="nav-link {{ request()->is('admin/najm-hoda*') ? 'active' : '' }}" 
       href="#najmHodaMenu" 
       data-bs-toggle="collapse" 
       aria-expanded="{{ request()->is('admin/najm-hoda*') ? 'true' : 'false' }}">
        <i class="fas fa-robot"></i>
        <span>نجم‌هدا</span>
        <i class="fas fa-angle-left ms-auto"></i>
    </a>
    
    <ul class="collapse {{ request()->is('admin/najm-hoda*') ? 'show' : '' }}" 
        id="najmHodaMenu">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.index') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.index') }}">
                <i class="fas fa-tachometer-alt"></i> داشبورد
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.chat') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.chat') }}">
                <i class="fas fa-comment"></i> چت مستقیم
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.conversations*') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.conversations') }}">
                <i class="fas fa-comments"></i> مکالمات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.analytics') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.analytics') }}">
                <i class="fas fa-chart-line"></i> تحلیل‌ها
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.feedbacks') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.feedbacks') }}">
                <i class="fas fa-star"></i> بازخوردها
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.create-agent') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.create-agent') }}">
                <i class="fas fa-plus-circle"></i> ساخت عامل جدید
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.settings') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.settings') }}">
                <i class="fas fa-cog"></i> تنظیمات
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.najm-hoda.logs') ? 'active' : '' }}" 
               href="{{ route('admin.najm-hoda.logs') }}">
                <i class="fas fa-file-alt"></i> لاگ‌ها
            </a>
        </li>
    </ul>
</li>
```

## روش 2: منوی افقی (Top Navigation)

```blade
<!-- در header ادمین -->

<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" 
       href="#" 
       id="najmHodaDropdown" 
       role="button" 
       data-bs-toggle="dropdown" 
       aria-expanded="false">
        <i class="fas fa-robot"></i> نجم‌هدا
    </a>
    <ul class="dropdown-menu" aria-labelledby="najmHodaDropdown">
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.index') }}">
            <i class="fas fa-tachometer-alt"></i> داشبورد
        </a></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.chat') }}">
            <i class="fas fa-comment"></i> چت مستقیم
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.conversations') }}">
            <i class="fas fa-comments"></i> مکالمات
        </a></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.analytics') }}">
            <i class="fas fa-chart-line"></i> تحلیل‌ها
        </a></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.feedbacks') }}">
            <i class="fas fa-star"></i> بازخوردها
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.create-agent') }}">
            <i class="fas fa-plus-circle"></i> ساخت عامل جدید
        </a></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.settings') }}">
            <i class="fas fa-cog"></i> تنظیمات
        </a></li>
        <li><a class="dropdown-item" href="{{ route('admin.najm-hoda.logs') }}">
            <i class="fas fa-file-alt"></i> لاگ‌ها
        </a></li>
    </ul>
</li>
```

## روش 3: دکمه دسترسی سریع در داشبورد

```blade
<!-- در داشبورد اصلی ادمین -->

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card border-primary">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="fas fa-robot"></i> نجم‌هدا - دستیار هوش مصنوعی
                </h5>
            </div>
            <div class="card-body">
                <p class="card-text">
                    دستیار هوشمند نجم‌هدا آماده کمک به شماست. 
                    می‌توانید مستقیماً چت کنید، مکالمات کاربران را بررسی کنید، 
                    یا حتی عوامل جدید بسازید!
                </p>
                <div class="btn-group" role="group">
                    <a href="{{ route('admin.najm-hoda.index') }}" class="btn btn-primary">
                        <i class="fas fa-tachometer-alt"></i> داشبورد
                    </a>
                    <a href="{{ route('admin.najm-hoda.chat') }}" class="btn btn-success">
                        <i class="fas fa-comment"></i> چت مستقیم
                    </a>
                    <a href="{{ route('admin.najm-hoda.conversations') }}" class="btn btn-info">
                        <i class="fas fa-comments"></i> مکالمات
                    </a>
                    <a href="{{ route('admin.najm-hoda.analytics') }}" class="btn btn-warning">
                        <i class="fas fa-chart-line"></i> تحلیل‌ها
                    </a>
                    <a href="{{ route('admin.najm-hoda.settings') }}" class="btn btn-secondary">
                        <i class="fas fa-cog"></i> تنظیمات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

## روش 4: دکمه شناور در پنل ادمین

```blade
<!-- در layout اصلی ادمین - بعد از محتوا -->

<div class="floating-najm-hoda">
    <button class="btn btn-primary btn-lg rounded-circle" 
            data-bs-toggle="modal" 
            data-bs-target="#najmHodaQuickAccess"
            title="دسترسی سریع نجم‌هدا">
        <i class="fas fa-robot fa-2x"></i>
    </button>
</div>

<!-- Modal دسترسی سریع -->
<div class="modal fade" id="najmHodaQuickAccess" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-robot"></i> نجم‌هدا - دسترسی سریع
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="list-group">
                    <a href="{{ route('admin.najm-hoda.chat') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-comment text-success"></i> چت مستقیم با نجم‌هدا
                    </a>
                    <a href="{{ route('admin.najm-hoda.index') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt text-primary"></i> داشبورد نجم‌هدا
                    </a>
                    <a href="{{ route('admin.najm-hoda.conversations') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-comments text-info"></i> مکالمات کاربران
                    </a>
                    <a href="{{ route('admin.najm-hoda.analytics') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-chart-line text-warning"></i> تحلیل‌ها و آمار
                    </a>
                    <a href="{{ route('admin.najm-hoda.create-agent') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-plus-circle text-success"></i> ساخت عامل جدید
                    </a>
                    <a href="{{ route('admin.najm-hoda.settings') }}" class="list-group-item list-group-item-action">
                        <i class="fas fa-cog text-secondary"></i> تنظیمات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.floating-najm-hoda {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.floating-najm-hoda button {
    width: 70px;
    height: 70px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
    50% { box-shadow: 0 4px 25px rgba(13, 110, 253, 0.6); }
    100% { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); }
}
</style>
```

## نکات مهم:

1. **آیکون‌ها:** از Font Awesome استفاده شده (مطمئن شوید که لود شده باشد)

2. **روت‌ها:** همه روت‌ها در `routes/web.php` تعریف شده‌اند

3. **دسترسی:** فقط ادمین‌ها می‌توانند به این بخش دسترسی داشته باشند

4. **سفارشی‌سازی:** می‌توانید رنگ‌ها و استایل‌ها را با توجه به تِم پنل ادمین خود تغییر دهید

## پیشنهاد من:

برای بهترین تجربه کاربری، **روش 1 + روش 4** را با هم استفاده کنید:
- منوی کناری برای دسترسی کامل
- دکمه شناور برای دسترسی سریع
