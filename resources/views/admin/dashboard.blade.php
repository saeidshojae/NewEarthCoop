@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <h2 class="text-center fw-bold mb-4"><a href='{{ route('admin.dashboard') }}' style='text-decoration: none'>پنل مدیریت</a></h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        
       @if(isset($_GET['general']))

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.invitation_codes.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-success hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success fw-bold">🎟️ مدیریت کدهای دعوت</h5>
                        <p class="card-text text-muted">ایجاد، مشاهده و مدیریت کدهای دعوت کاربران</p>
                    </div>
                </div>
            </a>
        </div>
        
                <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.invitation_codes.index', ['invation']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-success hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success fw-bold">️ درخواست های کد دعوت</h5>
                        <p class="card-text text-muted">ایجاد، مشاهده و مدیریت کدهای دعوت کاربران</p>
                    </div>
                </div>
            </a>
        </div>
      <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.category.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-success hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success fw-bold">️ مدیریت دسته بندی</h5>
                        <p class="card-text text-muted">ایجاد، مشاهده و مدیریت کدهای دعوت کاربران</p>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.activate.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-primary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">🔑 مدیریت فعال‌سازی‌ها</h5>
                        <p class="card-text text-muted">تنظیم دسترسی‌ها و کدهای فعال‌سازی</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.group.setting.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">تنظیمات انتخابات</h5>
                        <p class="card-text text-muted">مدیریت سطح، بازرسان و مدیران گروه‌ها</p>
                    </div>
                </div>
            </a>
        </div>
        
        @elseif(isset($_GET['activate']))
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.active.address') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">📍 تأیید آدرس‌های جدید</h5>
                        <p class="card-text text-muted">بازبینی و تأیید آدرس‌های کاربران</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.active.experience') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-dark hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-dark fw-bold">🛠️ تأیید صنف و تجربیات</h5>
                        <p class="card-text text-muted">مدیریت تخصص‌ها و صنف‌های تازه ثبت‌شده</p>
                    </div>
                </div>
            </a>
        </div>

        @elseif(isset($_GET['content']))
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.announcement.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm hover-shadow" style="border: 1px solid rgb(97, 64, 174)">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold" style="color: rgb(97, 64, 174)">📆 مدیریت اطلاعیه ها</h5>
                        <p class="card-text text-muted">تنظیم پیام و سرتیتر اطلاعیه</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.rule.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-warning hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-warning fw-bold">📜 مدیریت اساسنامه</h5>
                        <p class="card-text text-muted">تنظیم و مدیریت متن قوانین سامانه</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.pages.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">💬 مدیریت صفحات</h5>
                        <p class="card-text text-muted">مدیریت صفحات سامانه</p>
                    </div>
                </div>
            </a>
        </div>  
        
          <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.welcome-page') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">💬 صفحه خوش آمدید</h5>
                        <p class="card-text text-muted">مدیریت صفحه خوش آمدید</p>
                    </div>
                </div>
            </a>
        </div>  
        
          <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.welcome-page', ['home']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">💬 صفحه هوم</h5>
                        <p class="card-text text-muted">مدیریت صفحه هوم</p>
                    </div>
                </div>
            </a>
        </div>  
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.najm-page') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">نجم بهار</h5>
                        <p class="card-text text-muted">مدیریت نجم بهار</p>
                    </div>
                </div>
            </a>
        </div>  
        
        
        @else
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.dashboard', ['general']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-success hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success fw-bold">⚙️ تنظیمات سیستمی</h5>
                        <p class="card-text text-muted">مدیریت کد های دعوت، مدیریت فعالسازی و تنظیمات گروه ها </p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.dashboard', ['activate']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-info hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info fw-bold">➕️ مدیریت گزینه های جدید منوها</h5>
                        <p class="card-text text-muted">فعالسازی آدرس ها و صنف های جدید</p>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.dashboard', ['content']) }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-primary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">📄 مدیریت محتوای صفحات</h5>
                        <p class="card-text text-muted">تغییرات مربوط به محتوای صفحات</p>
                    </div>
                </div>
            </a>
        </div>
        


        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-danger hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-danger fw-bold">👥 مدیریت کاربران</h5>
                        <p class="card-text text-muted">مشاهده، حذف و بررسی کاربران سیستم</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.group.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-warning hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-warning fw-bold">📂 مدیریت گروه‌ها</h5>
                        <p class="card-text text-muted">مدیریت ساختار، اعضا و اطلاعات گروه‌ها</p>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.reports.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-secondary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-secondary fw-bold">🚨 گزارشات پیام‌ها</h5>
                        <p class="card-text text-muted">مدیریت گزارشات پیام‌ها</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-6 col-lg-4">
            <a href="{{ route('admin.stock.index') }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-primary hover-shadow">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary fw-bold">📈 مدیریت سهام و حراج</h5>
                        <p class="card-text text-muted">مدیریت سهام استارتاپ و حراج‌های سهام</p>
                    </div>
                </div>
            </a>
        </div>

        @endif
        
        

    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-4px);
        transition: 0.2s ease-in-out;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
</style>
@endsection
