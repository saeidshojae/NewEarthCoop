<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>خانه - New Earth Coop</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gfonts.ir" crossorigin>
    <link href="https://fonts.gfonts.ir/css?family=Vazirmatn:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Vazirmatn', 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);
            min-height: 100vh;
        }

        /* Animated Logo Styles */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        @keyframes pulse-glow {
            0%, 100% { filter: drop-shadow(0 0 5px rgba(16, 185, 129, 0.5)); }
            50% { filter: drop-shadow(0 0 15px rgba(16, 185, 129, 0.8)); }
        }
        
        .logo-animated {
            animation: float 3s ease-in-out infinite;
        }
        
        .logo-animated circle {
            animation: pulse-glow 2s ease-in-out infinite;
        }
        
        .logo-animated .tree {
            transform-origin: center;
            animation: rotate 20s linear infinite;
        }

        /* Blinking Animation for Spring Account */
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        
        .blinking-item {
            animation: blink 2s ease-in-out infinite;
        }

        /* Ripple Effect */
        .ripple {
            position: relative;
            overflow: hidden;
        }
        
        .ripple::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .ripple:active::after {
            width: 300px;
            height: 300px;
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        /* Sidebar Transition */
        .sidebar-enter {
            animation: slideInRight 0.3s ease-out;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Badge Pulse */
        .badge-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .7;
            }
        }

        /* Smooth Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #10b981;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #059669;
        }

        /* Content Card Hover */
        .content-card {
            transition: all 0.3s ease;
        }
        
        .content-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        /* Slider Styles */
        swiper-container {
            width: 100%;
            height: auto;
        }
        
        swiper-slide {
            text-align: center;
            font-size: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        swiper-slide img {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 1rem;
        }
    </style>
</head>

<body x-data="{ 
    sidebarOpen: false, 
    mobileMenuOpen: false, 
    userDropdownOpen: false,
    currentPage: 'home'
}">
    
    <!-- Header -->
    <header class="glass-effect sticky top-0 z-50 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                
                <!-- Logo & Mobile Menu Button -->
                <div class="flex items-center gap-4">
                    <!-- Mobile Hamburger -->
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-gray-700 hover:text-green-600 transition-colors">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                    
                    <!-- Animated Logo -->
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <svg class="logo-animated w-12 h-12 sm:w-16 sm:h-16" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="earthGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                    <stop offset="100%" style="stop-color:#059669;stop-opacity:1" />
                                </linearGradient>
                            </defs>
                            <circle cx="50" cy="50" r="45" fill="url(#earthGradient)" opacity="0.2"/>
                            <circle cx="50" cy="50" r="35" fill="url(#earthGradient)"/>
                            <g class="tree">
                                <path d="M50 30 L45 40 L40 35 L35 45 L50 45 L65 45 L60 35 L55 40 Z" fill="#059669"/>
                                <rect x="48" y="45" width="4" height="10" fill="#065f46"/>
                                <circle cx="42" cy="38" r="3" fill="#34d399" opacity="0.6"/>
                                <circle cx="58" cy="38" r="3" fill="#34d399" opacity="0.6"/>
                                <circle cx="50" cy="33" r="3" fill="#34d399" opacity="0.6"/>
                            </g>
                        </svg>
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition-colors">New Earth Coop</h1>
                            <p class="text-xs text-gray-600">کوپراتیو زمین نو</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
                        <i class="fas fa-home ml-2"></i>خانه
                    </a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
                        <i class="fas fa-info-circle ml-2"></i>درباره EarthCoop
                    </a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
                        <i class="fas fa-question-circle ml-2"></i>راهنمای استفاده
                    </a>
                    <a href="#" class="text-gray-700 hover:text-green-600 font-medium transition-colors">
                        <i class="fas fa-handshake ml-2"></i>همکاری
                    </a>
                </nav>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 hover:bg-gray-100 rounded-full px-4 py-2 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->fullName() }}</p>
                            <p class="text-xs text-gray-600">حساب کاربری</p>
                        </div>
                        {!! auth()->user()->profile() !!}
                        <i class="fas fa-chevron-down text-gray-600 text-sm transition-transform" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         class="absolute left-0 mt-2 w-56 glass-effect rounded-xl shadow-2xl overflow-hidden"
                         style="display: none;">
                        <div class="py-2">
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                <i class="fas fa-user w-5"></i>
                                <span>پروفایل من</span>
                            </a>
                            <a href="{{ route('terms.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                                <i class="fas fa-file-contract w-5"></i>
                                <span>اساسنامه</span>
                            </a>
                            <hr class="my-2">
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-homenew-dropdown').submit();" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span>خروج از حساب</span>
                            </a>
                            <form id="logout-form-homenew-dropdown" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Layout -->
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside x-show="sidebarOpen" 
               x-transition:enter="transition ease-out duration-300"
               x-transition:enter-start="-translate-x-full"
               x-transition:enter-end="translate-x-0"
               x-transition:leave="transition ease-in duration-300"
               x-transition:leave-start="translate-x-0"
               x-transition:leave-end="-translate-x-full"
               @click.away="sidebarOpen = false"
               class="fixed lg:sticky top-0 right-0 h-screen w-72 glass-effect shadow-2xl z-40 overflow-y-auto"
               style="display: none;">
            
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center gap-3">
                    {!! auth()->user()->profile() !!}
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ Auth::user()->fullName() }}</h3>
                        <a href="{{ route('profile.show') }}" class="text-sm text-green-600 hover:text-green-700">حساب کاربری من</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="p-4 space-y-2">
                
                @if (auth()->user()->is_admin == 1)
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:text-purple-600 rounded-xl transition-all ripple">
                    <i class="fas fa-user-shield w-5"></i>
                    <span class="font-medium">پنل مدیریت</span>
                </a>
                @endif

                <!-- Notifications with Badge -->
                <a href="{{ route('notifications.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 hover:text-blue-600 rounded-xl transition-all ripple relative">
                    <i class="fas fa-bell w-5"></i>
                    <span class="font-medium">اعلان‌ها</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="absolute left-4 top-3 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center badge-pulse">
                            {{ auth()->user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>

                <!-- Groups -->
                <a href="{{ route('groups.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-green-100 hover:text-green-600 rounded-xl transition-all ripple relative">
                    <i class="fas fa-users w-5"></i>
                    <span class="font-medium">گروه‌های من</span>
                    @if($groups->count() > 0)
                        <span class="absolute left-4 bg-green-100 text-green-600 text-xs font-bold rounded-full px-2 py-1">
                            {{ $groups->count() }}
                        </span>
                    @endif
                </a>

                <!-- Collaborations -->
                <a href="{{ route('history.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 hover:text-blue-600 rounded-xl transition-all ripple">
                    <i class="fas fa-handshake w-5"></i>
                    <span class="font-medium">مشارکت‌های من</span>
                </a>

                <!-- Elections -->
                <a href="{{ route('history.election') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-purple-50 hover:to-purple-100 hover:text-purple-600 rounded-xl transition-all ripple">
                    <i class="fas fa-vote-yea w-5"></i>
                    <span class="font-medium">انتخابات جاری</span>
                </a>

                <!-- Polls -->
                <a href="{{ route('history.poll') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-indigo-50 hover:to-indigo-100 hover:text-indigo-600 rounded-xl transition-all ripple">
                    <i class="fas fa-poll w-5"></i>
                    <span class="font-medium">نظرسنجی‌های جاری</span>
                </a>

                <!-- Spring Account with Blinking -->
                @php
                    $checkAcceptSpringAccount = \App\Models\Spring::where('user_id', auth()->user()->id)->first();
                @endphp
                <a href="{{ route('spring-accounts') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-yellow-50 hover:to-yellow-100 hover:text-yellow-600 rounded-xl transition-all ripple {{ $checkAcceptSpringAccount && $checkAcceptSpringAccount->status == 0 ? 'blinking-item' : '' }}">
                    <i class="fas fa-piggy-bank w-5"></i>
                    <span class="font-medium">حساب مالی نجم بهار</span>
                    @if($checkAcceptSpringAccount && $checkAcceptSpringAccount->status == 0)
                        <span class="absolute left-4 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full w-2 h-2"></span>
                    @endif
                </a>

                <!-- Invite Friends -->
                <a href="{{ route('my-invation-code') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-pink-50 hover:to-pink-100 hover:text-pink-600 rounded-xl transition-all ripple">
                    <i class="fas fa-user-plus w-5"></i>
                    <span class="font-medium">دعوت از دوستان</span>
                </a>

                <!-- Edit Profile -->
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-teal-50 hover:to-teal-100 hover:text-teal-600 rounded-xl transition-all ripple">
                    <i class="fas fa-user-edit w-5"></i>
                    <span class="font-medium">ویرایش حساب کاربری</span>
                </a>

                <!-- Support -->
                <a href="{{ route('user.tickets.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-blue-100 hover:text-blue-600 rounded-xl transition-all ripple relative">
                    <i class="fas fa-headset w-5"></i>
                    <span class="font-medium">پشتیبانی</span>
                    @php
                        $openTicketsCount = \App\Models\Ticket::where(function($q) {
                            $q->where('user_id', auth()->id())
                              ->orWhere('email', auth()->user()->email);
                        })->whereIn('status', ['open', 'in-progress'])->count();
                    @endphp
                    @if($openTicketsCount > 0)
                        <span class="absolute left-4 top-3 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center badge-pulse">
                            {{ $openTicketsCount }}
                        </span>
                    @endif
                </a>

                <hr class="my-4">

                <!-- Logout -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-homenew-sidebar').submit();" class="flex items-center gap-3 px-4 py-3 text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-red-100 rounded-xl transition-all ripple">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="font-medium">خروج از حساب کاربری</span>
                </a>
                <form id="logout-form-homenew-sidebar" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-4 lg:p-8">
            <div class="max-w-6xl mx-auto">
                
                <!-- Welcome Section -->
                <div class="glass-effect rounded-2xl shadow-xl p-8 mb-8 content-card">
                    <div class="text-center">
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-4">
                            {{ \App\Models\Setting::find(1)->home_titre }}
                        </h1>
                        <p class="text-gray-600">به سیستم کوپراتیو زمین نو خوش آمدید</p>
                    </div>
                </div>

                <!-- Image Slider -->
                @if(\App\Models\Slider::where('position', 1)->count() > 0)
                <div class="glass-effect rounded-2xl shadow-xl p-6 mb-8 content-card">
                    <swiper-container class="mySwiper" pagination="true" loop="true" autoplay-delay="6000" style="--swiper-pagination-color: #10b981; --swiper-pagination-bullet-inactive-color: #d1d5db;">
                        @foreach(\App\Models\Slider::where('position', 1)->get() as $slider)
                        <swiper-slide>
                            <img src="{{ asset('images/sliders/' . $slider->src) }}" class="w-full h-auto rounded-xl" alt="اسلایدر {{ $loop->iteration }}">
                        </swiper-slide>
                        @endforeach
                    </swiper-container>
                </div>
                @endif

                <!-- Home Content -->
                <div class="glass-effect rounded-2xl shadow-xl p-8 mb-8 content-card">
                    <div class="prose prose-lg max-w-none text-gray-700">
                        {!! \App\Models\Setting::find(1)->home_content !!}
                    </div>
                </div>

                <!-- Groups Statistics -->
                @php
                    $generalGroups = $groups->filter(function($group) {
                        return strtolower(trim($group->group_type)) === 'general';
                    });
                    $specializedGroups = $groups->filter(function($group) {
                        return strtolower(trim($group->group_type)) === 'specialized';
                    });
                    $exclusiveGroups = $groups->filter(function($group) {
                        return strtolower(trim($group->group_type)) === 'exclusive';
                    });
                @endphp

                @if($groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    
                    <!-- General Groups Card -->
                    <div class="glass-effect rounded-2xl shadow-xl p-6 content-card">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">گروه‌های عمومی</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $generalGroups->count() }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Specialized Groups Card -->
                    <div class="glass-effect rounded-2xl shadow-xl p-6 content-card">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-briefcase text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">گروه‌های تخصصی</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $specializedGroups->count() }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Exclusive Groups Card -->
                    <div class="glass-effect rounded-2xl shadow-xl p-6 content-card">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-star text-white text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-600 text-sm">گروه‌های اختصاصی</p>
                                <h3 class="text-3xl font-bold text-gray-800">{{ $exclusiveGroups->count() }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Active Auctions (if available) -->
                @if(isset($activeAuctions) && $activeAuctions->count() > 0)
                <div class="glass-effect rounded-2xl shadow-xl p-8 content-card">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-3">
                        <i class="fas fa-gavel text-green-600"></i>
                        حراج‌های فعال
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($activeAuctions as $auction)
                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all">
                            <h3 class="font-semibold text-gray-800 mb-2">{{ $auction->stock->name ?? 'حراج' }}</h3>
                            <p class="text-sm text-gray-600 mb-4">پایان: {{ $auction->ends_at->diffForHumans() }}</p>
                            <a href="#" class="inline-block bg-gradient-to-r from-green-500 to-green-600 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-green-700 transition-all">
                                مشاهده جزئیات
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </main>
    </div>

    <!-- Success/Error Alerts -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-4 left-4 glass-effect rounded-xl shadow-2xl p-4 z-50 max-w-md"
         style="display: none;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800">موفقیت</p>
                <p class="text-sm text-gray-600">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         class="fixed bottom-4 left-4 glass-effect rounded-xl shadow-2xl p-4 z-50 max-w-md"
         style="display: none;">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation text-white"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800">خطا</p>
                <p class="text-sm text-gray-600">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

</body>
</html>
