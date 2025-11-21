@extends('layouts.unified')

@section('title', 'تست Layout یکپارچه')

@section('content')
<div class="container mx-auto px-4 py-8">
    
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl md:text-5xl font-bold text-gentle-black mb-4" style="color: var(--color-gentle-black);">
            🎨 تست Layout یکپارچه
        </h1>
        <p class="text-xl text-gray-600">
            این صفحه برای تست Layout یکپارچه جدید ساخته شده است
        </p>
    </div>

    <!-- Cards Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        <!-- Card 1: رنگ‌ها -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="background-color: var(--color-pure-white);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gentle-black" style="color: var(--color-gentle-black);">رنگ‌ها</h3>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(16, 185, 129, 0.15);">
                    <i class="fas fa-palette text-2xl" style="color: var(--color-earth-green);"></i>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-earth-green"></div>
                    <span class="text-sm">سبز زمین</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-ocean-blue"></div>
                    <span class="text-sm">آبی اقیانوس</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-digital-gold"></div>
                    <span class="text-sm">طلایی دیجیتال</span>
                </div>
            </div>
        </div>

        <!-- Card 2: فونت‌ها -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="background-color: var(--color-pure-white);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gentle-black" style="color: var(--color-gentle-black);">فونت‌ها</h3>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(59, 130, 246, 0.15);">
                    <i class="fas fa-font text-2xl" style="color: var(--color-ocean-blue);"></i>
                </div>
            </div>
            <div class="space-y-2">
                <p class="font-vazirmatn text-sm">فونت Vazirmatn (فارسی)</p>
                <p class="font-poppins text-sm">Poppins Font (English)</p>
                <p class="text-sm font-bold">Bold Text</p>
                <p class="text-sm font-semibold">Semibold Text</p>
            </div>
        </div>

        <!-- Card 3: انیمیشن‌ها -->
        <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-1" style="background-color: var(--color-pure-white);">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gentle-black" style="color: var(--color-gentle-black);">انیمیشن‌ها</h3>
                <div class="w-12 h-12 rounded-full flex items-center justify-center" style="background-color: rgba(245, 158, 11, 0.15);">
                    <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="logo-animated">
                        <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" fill="#10b981" opacity="0.8"/>
                        <path d="M12 2C10.5 4 8 6 8 9C8 12 12 14 12 14C12 14 16 12 16 9C16 6 13.5 4 12 2ZM12 14C12 14 10 16 10 18C10 20 12 22 12 22" fill="#047857"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-earth-green badge-pulse"></div>
                    <span class="text-sm">Pulse Animation</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-ocean-blue blinking-item"></div>
                    <span class="text-sm">Blink Animation</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 border border-gray-200" style="background-color: var(--color-pure-white);">
        <h2 class="text-2xl font-bold text-gentle-black mb-6 text-center" style="color: var(--color-gentle-black);">دکمه‌ها</h2>
        <div class="flex flex-wrap gap-4 justify-center">
            <button class="px-6 py-3 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105 ripple" 
                    style="background-color: var(--color-earth-green); color: var(--color-pure-white);">
                <i class="fas fa-check ml-2"></i> دکمه سبز
            </button>
            <button class="px-6 py-3 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105" 
                    style="background-color: var(--color-ocean-blue); color: var(--color-pure-white);">
                <i class="fas fa-info ml-2"></i> دکمه آبی
            </button>
            <button class="px-6 py-3 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105" 
                    style="background-color: var(--color-digital-gold); color: var(--color-pure-white);">
                <i class="fas fa-star ml-2"></i> دکمه طلایی
            </button>
        </div>
    </div>

    <!-- Typography Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 border border-gray-200" style="background-color: var(--color-pure-white);">
        <h2 class="text-2xl font-bold text-gentle-black mb-6" style="color: var(--color-gentle-black);">Typography</h2>
        <div class="space-y-4">
            <h1 class="text-4xl font-bold text-gentle-black" style="color: var(--color-gentle-black);">عنوان H1</h1>
            <h2 class="text-3xl font-bold text-gentle-black" style="color: var(--color-gentle-black);">عنوان H2</h2>
            <h3 class="text-2xl font-semibold text-gentle-black" style="color: var(--color-gentle-black);">عنوان H3</h3>
            <p class="text-lg text-gray-700">این یک پاراگراف با اندازه بزرگ است. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
            <p class="text-base text-gray-600">این یک پاراگراف با اندازه معمولی است. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
            <p class="text-sm text-gray-500">این یک پاراگراف با اندازه کوچک است. لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ.</p>
        </div>
    </div>

    <!-- Forms Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-8 border border-gray-200" style="background-color: var(--color-pure-white);">
        <h2 class="text-2xl font-bold text-gentle-black mb-6" style="color: var(--color-gentle-black);">فرم‌ها</h2>
        <form class="space-y-4 max-w-md">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">نام</label>
                <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent" 
                       style="focus:ring-color: var(--color-earth-green);" placeholder="نام خود را وارد کنید">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ایمیل</label>
                <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent" 
                       style="focus:ring-color: var(--color-earth-green);" placeholder="email@example.com">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">پیام</label>
                <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-earth-green focus:border-transparent" 
                          style="focus:ring-color: var(--color-earth-green);" placeholder="پیام خود را وارد کنید"></textarea>
            </div>
            <button type="submit" class="w-full px-6 py-3 rounded-full shadow-md transition duration-300 font-medium transform hover:scale-105 ripple" 
                    style="background-color: var(--color-earth-green); color: var(--color-pure-white);">
                <i class="fas fa-paper-plane ml-2"></i> ارسال
            </button>
        </form>
    </div>

    <!-- Alerts Section -->
    <div class="space-y-4 mb-8">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">موفقیت!</strong>
            <span class="block sm:inline">این یک پیام موفقیت است.</span>
        </div>
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">اطلاعات:</strong>
            <span class="block sm:inline">این یک پیام اطلاعات است.</span>
        </div>
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">هشدار:</strong>
            <span class="block sm:inline">این یک پیام هشدار است.</span>
        </div>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">خطا!</strong>
            <span class="block sm:inline">این یک پیام خطا است.</span>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-earth-green to-ocean-blue rounded-xl shadow-lg p-8 text-white text-center">
        <h3 class="text-2xl font-bold mb-4">✅ Layout یکپارچه آماده است!</h3>
        <p class="text-lg mb-4">
            این صفحه از Layout جدید (`layouts/unified`) استفاده می‌کند و شامل:
        </p>
        <ul class="list-none space-y-2 text-right inline-block">
            <li>✅ Header یکپارچه</li>
            <li>✅ Footer یکپارچه</li>
            <li>✅ رنگ‌ها و فونت‌های یکسان</li>
            <li>✅ Dark Mode Support</li>
            <li>✅ Responsive Design</li>
        </ul>
    </div>

</div>
@endsection

@push('styles')
<style>
    /* استایل‌های اضافی برای صفحه تست */
    .test-card {
        transition: all 0.3s ease;
    }
    
    .test-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush



