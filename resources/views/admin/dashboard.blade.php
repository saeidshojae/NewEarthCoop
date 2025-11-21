@extends('layouts.admin')

@section('title', 'داشبورد مدیریت - ' . config('app.name', 'EarthCoop'))
@section('page-title', 'داشبورد مدیریت')
@section('page-description', 'مدیریت سیستم EarthCoop')

@push('styles')
<style>
    .dashboard-container {
        direction: rtl;
    }
    
    .dashboard-section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
    }
    
    @media (min-width: 768px) {
        .dashboard-section-title {
            font-size: 1.5rem;
        }
    }
    
    @media (prefers-color-scheme: dark) {
        .dashboard-section-title {
            color: #f1f5f9;
            border-bottom-color: #334155;
        }
        
        .dashboard-container {
            color: #f1f5f9;
        }
    }
</style>
@endpush

@section('content')
<div class="dashboard-container">
    <!-- Najm Hoda Card -->
    <div class="mb-8">
        <div class="najm-hoda-card">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="flex-1 text-center md:text-right">
                    <div class="flex items-center justify-center md:justify-start gap-4 mb-4">
                        <div class="najm-hoda-icon">🌟</div>
                        <div>
                            <h3 class="najm-hoda-title">نجم‌هدا - دستیار هوش مصنوعی</h3>
                            <p class="najm-hoda-subtitle">نرم افزار جامع مدیریت هوشمند دنیای ارثکوپ</p>
                        </div>
                    </div>
                    <p class="text-white mb-4 text-sm md:text-lg leading-relaxed">
                        دستیار هوشمند با 5 عامل تخصصی آماده کمک به شما است: 
                        <span class="block sm:inline">مهندس 🔧 | خلبان ✈️ | مهماندار 👨‍✈️ | راهنما 📖 | معمار 🏗️</span>
                    </p>
                    <div class="najm-hoda-features">
                        <span class="najm-hoda-badge">چت مستقیم</span>
                        <span class="najm-hoda-badge">تحلیل و آمار</span>
                        <span class="najm-hoda-badge">بازخورد کاربران</span>
                        <span class="najm-hoda-badge">ساخت عامل جدید</span>
                    </div>
                </div>
                <div class="flex-shrink-0 w-full md:w-auto">
                    <div class="najm-hoda-buttons">
                        <a href="{{ route('admin.najm-hoda.index') }}" class="najm-hoda-btn najm-hoda-btn-primary">
                            <i class="fas fa-tachometer-alt"></i>
                            داشبورد نجم‌هدا
                        </a>
                        <a href="{{ route('admin.najm-hoda.chat') }}" class="najm-hoda-btn najm-hoda-btn-outline">
                            <i class="fas fa-comment-dots"></i>
                            چت مستقیم
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @if(request()->has('general'))
            <!-- General Settings -->
            <a href="{{ route('admin.invitation_codes.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-success">
                    <div class="admin-card-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت کدهای دعوت</h3>
                    <p class="admin-card-description">ایجاد، مشاهده و مدیریت کدهای دعوت کاربران</p>
                </div>
            </a>
            
            <a href="{{ route('admin.invitation_codes.index', ['invation']) }}" class="admin-card-link">
                <div class="admin-card admin-card-success">
                    <div class="admin-card-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                    <h3 class="admin-card-title">درخواست‌های کد دعوت</h3>
                    <p class="admin-card-description">مشاهده و مدیریت درخواست‌های کد دعوت</p>
                </div>
            </a>
            
            <a href="{{ route('admin.category.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-success">
                    <div class="admin-card-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت دسته‌بندی</h3>
                    <p class="admin-card-description">ایجاد، مشاهده و مدیریت دسته‌بندی‌ها</p>
                </div>
            </a>
            
            <a href="{{ route('admin.activate.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-primary">
                    <div class="admin-card-icon">
                        <i class="fas fa-key"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت فعال‌سازی‌ها</h3>
                    <p class="admin-card-description">تنظیم دسترسی‌ها و کدهای فعال‌سازی</p>
                </div>
            </a>
            
            <a href="{{ route('admin.group.setting.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-vote-yea"></i>
                    </div>
                    <h3 class="admin-card-title">تنظیمات انتخابات</h3>
                    <p class="admin-card-description">مدیریت سطح، بازرسان و مدیران گروه‌ها</p>
                </div>
            </a>
            
        @elseif(request()->has('activate'))
            <!-- Activation Management -->
            <a href="{{ route('admin.active.address') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <h3 class="admin-card-title">تأیید آدرس‌های جدید</h3>
                    <p class="admin-card-description">بازبینی و تأیید آدرس‌های کاربران</p>
                </div>
            </a>
            
            <a href="{{ route('admin.active.experience') }}" class="admin-card-link">
                <div class="admin-card admin-card-warning">
                    <div class="admin-card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="admin-card-title">تأیید صنف و تجربیات</h3>
                    <p class="admin-card-description">مدیریت تخصص‌ها و صنف‌های تازه ثبت‌شده</p>
                </div>
            </a>
            
        @elseif(request()->has('content'))
            <!-- Content Management -->
            <a href="{{ route('admin.announcement.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-purple">
                    <div class="admin-card-icon">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت اطلاعیه‌ها</h3>
                    <p class="admin-card-description">تنظیم پیام و سرتیتر اطلاعیه</p>
                </div>
            </a>
            
            <a href="{{ route('admin.rule.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-warning">
                    <div class="admin-card-icon">
                        <i class="fas fa-scroll"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت اساسنامه</h3>
                    <p class="admin-card-description">تنظیم و مدیریت متن قوانین سامانه</p>
                </div>
            </a>
            
            <a href="{{ route('admin.pages.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت صفحات</h3>
                    <p class="admin-card-description">مدیریت صفحات سامانه</p>
                </div>
            </a>
            
            <a href="{{ route('admin.blog.dashboard') }}" class="admin-card-link">
                <div class="admin-card admin-card-info">
                    <div class="admin-card-icon">
                        <i class="fas fa-blog"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت وبلاگ</h3>
                    <p class="admin-card-description">مدیریت مقالات، دسته‌بندی‌ها و نظرات وبلاگ</p>
                </div>
            </a>
            
            <a href="{{ route('admin.welcome-page') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-hand-sparkles"></i>
                    </div>
                    <h3 class="admin-card-title">صفحه خوش آمدید</h3>
                    <p class="admin-card-description">مدیریت صفحه خوش آمدید</p>
                </div>
            </a>
            
            <a href="{{ route('admin.welcome-page', ['home']) }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="admin-card-title">صفحه هوم</h3>
                    <p class="admin-card-description">مدیریت صفحه هوم</p>
                </div>
            </a>
            
            <a href="{{ route('admin.najm-page') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3 class="admin-card-title">نجم بهار</h3>
                    <p class="admin-card-description">مدیریت نجم بهار</p>
                </div>
            </a>
            
        @else
            <!-- Main Dashboard -->
            <a href="{{ route('admin.dashboard', ['general']) }}" class="admin-card-link">
                <div class="admin-card admin-card-success">
                    <div class="admin-card-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <h3 class="admin-card-title">تنظیمات سیستمی</h3>
                    <p class="admin-card-description">مدیریت کدهای دعوت، مدیریت فعال‌سازی و تنظیمات گروه‌ها</p>
                </div>
            </a>
            
            <a href="{{ route('admin.dashboard', ['activate']) }}" class="admin-card-link">
                <div class="admin-card admin-card-info">
                    <div class="admin-card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت گزینه‌های جدید منوها</h3>
                    <p class="admin-card-description">فعال‌سازی آدرس‌ها و صنف‌های جدید</p>
                </div>
            </a>
            
            <a href="{{ route('admin.content.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-primary">
                    <div class="admin-card-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت محتوا</h3>
                    <p class="admin-card-description">مدیریت اطلاعیه‌ها، صفحات استاتیک، اساسنامه، صفحه خوش‌آمد، صفحه هوم و نجم بهار</p>
                </div>
            </a>
            
            <a href="{{ route('admin.users.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-danger">
                    <div class="admin-card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت کاربران</h3>
                    <p class="admin-card-description">مشاهده، حذف و بررسی کاربران سیستم</p>
                </div>
            </a>
            
            <a href="{{ route('admin.groups.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-warning">
                    <div class="admin-card-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت گروه‌ها</h3>
                    <p class="admin-card-description">مدیریت ساختار، اعضا و اطلاعات گروه‌ها</p>
                </div>
            </a>
            
            <a href="{{ route('admin.reports.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-secondary">
                    <div class="admin-card-icon">
                        <i class="fas fa-flag"></i>
                    </div>
                    <h3 class="admin-card-title">گزارشات پیام‌ها</h3>
                    <p class="admin-card-description">مدیریت گزارشات پیام‌ها</p>
                </div>
            </a>
            
            <a href="{{ route('admin.stock.index') }}" class="admin-card-link">
                <div class="admin-card admin-card-primary">
                    <div class="admin-card-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت سهام و حراج</h3>
                    <p class="admin-card-description">مدیریت سهام استارتاپ و حراج‌های سهام</p>
                </div>
            </a>
            
            <a href="{{ route('admin.blog.dashboard') }}" class="admin-card-link">
                <div class="admin-card admin-card-info">
                    <div class="admin-card-icon">
                        <i class="fas fa-blog"></i>
                    </div>
                    <h3 class="admin-card-title">مدیریت وبلاگ</h3>
                    <p class="admin-card-description">مدیریت مقالات، دسته‌بندی‌ها و نظرات وبلاگ</p>
                </div>
            </a>
        @endif
    </div>
</div>
@endsection
