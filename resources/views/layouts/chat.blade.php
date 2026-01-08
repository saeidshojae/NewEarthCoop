<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'New Earth Coop')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Suppress warnings که از کد ما نیستند
        (function() {
            if (window.console && window.console.warn) {
                var originalWarn = console.warn;
                console.warn = function() {
                    var message = arguments[0];
                    
                    // Suppress Tailwind CDN warning
                    if (message && typeof message === 'string' && 
                        message.includes('cdn.tailwindcss.com') && 
                        message.includes('should not be used in production')) {
                        return;
                    }
                    
                    // Suppress Tracking Prevention warnings (از Edge)
                    if (message && typeof message === 'string' && 
                        message.includes('Tracking Prevention blocked')) {
                        return;
                    }
                    
                    // سایر warnings را نمایش بده
                    originalWarn.apply(console, arguments);
                };
            }
            
            // Suppress errors از افزونه‌های مرورگر
            if (window.console && window.console.error) {
                var originalError = console.error;
                console.error = function() {
                    var message = arguments[0];
                    var source = arguments[1] || '';
                    var stack = arguments[2] || '';
                    
                    // بررسی message
                    var messageStr = typeof message === 'string' ? message : (message?.toString() || '');
                    
                    // بررسی source (ممکن است در arguments مختلف باشد)
                    var sourceStr = typeof source === 'string' ? source : '';
                    if (!sourceStr && message?.stack) {
                        sourceStr = message.stack;
                    }
                    
                    // Suppress errors از content.js و content-all.js (افزونه‌های مرورگر)
                    if (sourceStr && (sourceStr.includes('content.js') || sourceStr.includes('content-all.js'))) {
                        return;
                    }
                    
                    // Suppress errors که شامل "Cannot read properties of null" و از افزونه‌ها هستند
                    if (messageStr && messageStr.includes('Cannot read properties of null') && 
                        (sourceStr.includes('content') || sourceStr.includes('extension'))) {
                        return;
                    }
                    
                    // Suppress forEach errors از افزونه‌ها
                    if (messageStr && messageStr.includes('forEach is not a function') &&
                        (sourceStr.includes('content') || sourceStr.includes('extension'))) {
                        return;
                    }
                    
                    // سایر errors را نمایش بده
                    originalError.apply(console, arguments);
                };
            }
        })();
    </script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark Mode -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <!-- Dark Mode Fix -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode-fix.css') }}">
    
    <!-- User Dropdown Responsive -->
    <link rel="stylesheet" href="{{ asset('Css/user-dropdown-responsive.css') }}">
    
    <!-- Unified Styles -->
    <link rel="stylesheet" href="{{ asset('Css/unified-styles.css') }}">
    
    <!-- Edge Browser Compatibility -->
    <link rel="stylesheet" href="{{ asset('css/edge-compatibility.css') }}">
    
    <!-- Page Specific Styles -->
    @stack('styles')
    @yield('head-tag')
    
    <style>
        /* Alpine.js cloaking */
        [x-cloak] {
            display: none !important;
        }

        /* Edge Browser Compatibility Fixes - همان اصلاحات صفحه welcome */
        .edge-browser body,
        .tailwind-fallback body {
            font-size: 16px !important;
            line-height: 1.5 !important;
        }

        .edge-browser .container,
        .tailwind-fallback .container {
            max-width: 1280px !important;
            margin-left: auto !important;
            margin-right: auto !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }

        .edge-browser img,
        .tailwind-fallback img {
            max-width: 100% !important;
            height: auto !important;
        }
        
        /* Chat Layout Specific Styles */
        body.chat-layout {
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e9 100%);
        }
        
        .chat-mini-header {
            background-color: var(--color-pure-white);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s ease;
        }
        
        .chat-menu-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .chat-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        .chat-menu-sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: 320px;
            max-width: 85vw;
            height: 100vh;
            background: var(--color-pure-white);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            overflow-y: auto;
            direction: rtl;
        }
        
        .chat-menu-sidebar.active {
            transform: translateX(0);
        }
        
        @media (max-width: 768px) {
            .chat-menu-sidebar {
                width: 280px;
            }
        }
        
        /* User Dropdown در صفحه Chat - همیشه به سمت راست - با specificity بالا */
        body.chat-layout .relative > div.chat-user-dropdown,
        body.chat-layout .relative[x-data*="userDropdownOpen"] > div[x-show],
        body.chat-layout .chat-user-dropdown,
        .chat-layout .relative .chat-user-dropdown,
        .chat-layout .chat-user-dropdown {
            right: 0 !important;
            left: auto !important;
            transform-origin: top right !important;
        }
        
        /* در موبایل: اطمینان از اینکه dropdown از صفحه بیرون نزند */
        @media (max-width: 768px) {
            body.chat-layout .relative > div.chat-user-dropdown,
            body.chat-layout .relative[x-data*="userDropdownOpen"] > div[x-show],
            body.chat-layout .chat-user-dropdown,
            .chat-layout .relative .chat-user-dropdown,
            .chat-layout .chat-user-dropdown {
                max-width: calc(100vw - 1rem) !important;
                width: 18rem !important; /* w-72 */
                min-width: 16rem !important;
                right: 0.5rem !important;
                left: auto !important;
            }
        }
        
        /* استایل دکمه کاربر - جلوگیری از تغییر رنگ */
        body.chat-layout .user-dropdown-btn,
        body.chat-layout .relative button.user-dropdown-btn {
            background-color: var(--color-earth-green) !important;
            color: var(--color-pure-white) !important;
        }
        
        body.chat-layout .user-dropdown-btn:hover,
        body.chat-layout .relative button.user-dropdown-btn:hover {
            background-color: var(--color-dark-green) !important;
        }
        
        body.chat-layout .user-dropdown-btn:active,
        body.chat-layout .user-dropdown-btn:focus,
        body.chat-layout .relative button.user-dropdown-btn:active,
        body.chat-layout .relative button.user-dropdown-btn:focus {
            background-color: var(--color-earth-green) !important;
            outline: none !important;
        }
        
        /* جلوگیری از تغییر رنگ در حالت active */
        body.chat-layout .user-dropdown-btn[style*="background-color"] {
            background-color: var(--color-earth-green) !important;
        }
        
        /* در دسکتاپ: فاصله مناسب از لبه */
        @media (min-width: 768px) {
            body.chat-layout .relative > div.chat-user-dropdown,
            body.chat-layout .relative[x-data*="userDropdownOpen"] > div[x-show],
            body.chat-layout .chat-user-dropdown,
            .chat-layout .relative .chat-user-dropdown,
            .chat-layout .chat-user-dropdown {
                right: 0 !important;
                left: auto !important;
            }
        }
    </style>
</head>

<body class="font-vazirmatn leading-relaxed min-h-screen flex flex-col chat-layout" 
      x-data="{ 
          mobileMenuOpen: false,
          chatMenuOpen: false
      }">
    
    <!-- Mini Header for Chat Pages -->
    <header class="chat-mini-header py-3 px-4 md:px-6">
        <div class="container mx-auto flex items-center justify-between gap-4">
            <!-- Left: Back Button & Logo -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" 
                   class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors text-gray-600 hover:text-green-600"
                   title="بازگشت">
                    <i class="fa fa-arrow-right text-xl"></i>
                </a>
                
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" fill="#10b981" opacity="0.8"/>
                        <path d="M12 2C10.5 4 8 6 8 9C8 12 12 14 12 14C12 14 16 12 16 9C16 6 13.5 4 12 2ZM12 14C12 14 10 16 10 18C10 20 12 22 12 22" fill="#047857"/>
                    </svg>
                    <span class="text-lg font-extrabold text-gentle-black hidden sm:inline" style="color: var(--color-gentle-black);">
                        EarthCoop
                    </span>
                </a>
            </div>
            
            <!-- Right: Menu Button & User Info -->
            <div class="flex items-center gap-3 flex-shrink-0">
                <!-- Menu Button -->
                <button @click="chatMenuOpen = !chatMenuOpen" 
                        class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors text-gray-600 hover:text-green-600"
                        title="منو">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
                <!-- User Dropdown (Mini Version) -->
                @auth
                    @include('components.user-dropdown-unified')
                @else
                    <a href="{{ route('login') }}" 
                       class="bg-earth-green text-pure-white px-4 py-2 rounded-full shadow-md hover:bg-dark-green transition duration-300 font-medium text-sm">
                        {{ __('navigation.login') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>
    
    <!-- Menu Overlay -->
    <div class="chat-menu-overlay" 
         :class="{ 'active': chatMenuOpen }"
         @click="chatMenuOpen = false"
         x-cloak></div>
    
    <!-- Menu Sidebar -->
    <aside class="chat-menu-sidebar" 
           :class="{ 'active': chatMenuOpen }"
           x-cloak>
        <div class="p-6">
            <!-- Close Button -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gentle-black" style="color: var(--color-gentle-black);">
                    منو
                </h2>
                <button @click="chatMenuOpen = false" 
                        class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Navigation Links -->
            <nav class="space-y-2">
                <a href="{{ route('home') }}" 
                   @click="chatMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                   style="color: var(--color-gentle-black);">
                    <i class="fas fa-home" style="color: var(--color-earth-green);"></i>
                    <span>خانه</span>
                </a>
                
                @auth
                    <a href="{{ route('blog.index') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-blog" style="color: var(--color-ocean-blue);"></i>
                        <span>{{ __('navigation.blog') }}</span>
                    </a>
                    
                    <a href="{{ route('stock.book') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-chart-line" style="color: var(--color-earth-green);"></i>
                        <span>{{ __('navigation.stock_office') }}</span>
                    </a>
                    
                    <a href="{{ route('groups.index') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-users" style="color: var(--color-ocean-blue);"></i>
                        <span>{{ __('navigation.footer_my_groups') }}</span>
                    </a>
                    
                    <a href="{{ route('notifications.index') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray relative"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-bell" style="color: var(--color-ocean-blue);"></i>
                        <span>اعلان‌ها</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute right-2 top-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                    
                    <a href="{{ route('profile.edit') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-cog" style="color: var(--color-ocean-blue);"></i>
                        <span>ویرایش حساب کاربری</span>
                    </a>
                    
                    @if (auth()->user()->is_admin == 1)
                        <a href="{{ route('admin.dashboard') }}" 
                           @click="chatMenuOpen = false"
                           class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                           style="color: var(--color-gentle-black);">
                            <i class="fas fa-user-shield" style="color: #9333ea;"></i>
                            <span>{{ __('navigation.admin_dashboard') }}</span>
                        </a>
                    @endif
                    
                    <hr class="my-4 border-gray-200">
                    
                    <a href="#" 
                       onclick="event.preventDefault(); document.getElementById('logout-form-chat').submit();"
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 transition duration-200 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>{{ __('navigation.logout') }}</span>
                    </a>
                    <form id="logout-form-chat" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" 
                       @click="chatMenuOpen = false"
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-gentle-black transition duration-200 hover:bg-light-gray"
                       style="color: var(--color-gentle-black);">
                        <i class="fas fa-sign-in-alt" style="color: var(--color-earth-green);"></i>
                        <span>{{ __('navigation.login') }}</span>
                    </a>
                @endauth
            </nav>
        </div>
    </aside>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto mt-3 px-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('error'))
        <div class="container mx-auto mt-3 px-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="container mx-auto mt-3 px-4">
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('warning') }}</span>
            </div>
        </div>
    @endif
    
    @if(session('info'))
        <div class="container mx-auto mt-3 px-4">
            <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('info') }}</span>
            </div>
        </div>
    @endif
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>
    
    <!-- Scripts -->
    @stack('scripts')
    @yield('scripts')
    
    <!-- Najm Hoda Widget -->
    @if(config('najm-hoda.widget.enabled', true))
        @include('components.najm-hoda-widget')
    @endif
    
    <!-- SweetAlert Helper Functions -->
    <script>
        function showAlert(message, type = 'info') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    text: message,
                    icon: type,
                    confirmButtonText: 'باشه',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            } else {
                alert(message);
            }
        }

        function showSuccessAlert(message) {
            showAlert(message, 'success');
        }

        function showErrorAlert(message) {
            showAlert(message, 'error');
        }

        function showWarningAlert(message) {
            showAlert(message, 'warning');
        }

        function showInfoAlert(message) {
            showAlert(message, 'info');
        }
        
        // Polyfill for CSS Variables and Edge Detection
        (function() {
            var isEdge = /Edg/.test(navigator.userAgent) || /Edge/.test(navigator.userAgent);
            var isIE = /Trident/.test(navigator.userAgent);
            
            // Simple CSS Variables polyfill for Edge Legacy
            if (!window.CSS || !CSS.supports || !CSS.supports('color', 'var(--fake-var)')) {
                var style = document.createElement('style');
                var cssVariables = {
                    '--color-earth-green': '#10b981',
                    '--color-ocean-blue': '#3b82f6',
                    '--color-digital-gold': '#f59e0b',
                    '--color-pure-white': '#ffffff',
                    '--color-light-gray': '#f8fafc',
                    '--color-gentle-black': '#1e293b',
                    '--color-dark-green': '#047857',
                    '--color-dark-blue': '#1d4ed8',
                    '--color-accent-peach': '#ff7e5f',
                    '--color-accent-sky': '#6dd5ed',
                    '--color-purple-700': '#6B46C1'
                };
                var css = '';
                for (var prop in cssVariables) {
                    css += prop.replace('--', '') + ': ' + cssVariables[prop] + '; ';
                }
                style.textContent = ':root { ' + css + ' }';
                document.head.appendChild(style);
            }
            
            // برای Edge فوراً base styles را اعمال کن
            if (isEdge || isIE) {
                var baseStyle = document.createElement('style');
                baseStyle.id = 'edge-base-styles';
                baseStyle.textContent = `
                    * { box-sizing: border-box; }
                    html { font-size: 16px; -webkit-text-size-adjust: 100%; }
                    body { 
                        margin: 0; 
                        padding: 0; 
                        font-size: 1rem; 
                        line-height: 1.5;
                        -webkit-font-smoothing: antialiased;
                        -moz-osx-font-smoothing: grayscale;
                    }
                    .container { 
                        width: 100%; 
                        margin-left: auto; 
                        margin-right: auto; 
                        padding-left: 1rem; 
                        padding-right: 1rem; 
                    }
                    @media (min-width: 640px) { .container { max-width: 640px; } }
                    @media (min-width: 768px) { .container { max-width: 768px; } }
                    @media (min-width: 1024px) { .container { max-width: 1024px; } }
                    @media (min-width: 1280px) { .container { max-width: 1280px; } }
                `;
                document.head.insertBefore(baseStyle, document.head.firstChild);
                document.documentElement.classList.add('edge-browser');
            }
            
            // بررسی لود شدن Tailwind
            var tailwindLoaded = false;
            var checkCount = 0;
            var maxChecks = 50;
            
            var checkTailwind = setInterval(function() {
                checkCount++;
                
                if (window.tailwind || (document.querySelector('style[data-tailwind]') !== null)) {
                    tailwindLoaded = true;
                    clearInterval(checkTailwind);
                    return;
                }
                
                if ((isEdge || isIE) && checkCount >= 10) {
                    clearInterval(checkTailwind);
                    document.documentElement.classList.add('tailwind-fallback');
                }
                
                if (checkCount >= maxChecks) {
                    clearInterval(checkTailwind);
                    if (!tailwindLoaded) {
                        document.documentElement.classList.add('tailwind-fallback');
                    }
                }
            }, 100);
            
            // بررسی و اصلاح اندازه‌ها برای Edge بعد از لود صفحه
            if (isEdge) {
                setTimeout(function() {
                    var testElement = document.createElement('div');
                    testElement.className = 'hidden';
                    testElement.style.display = 'none';
                    document.body.appendChild(testElement);
                    
                    var computedStyle = window.getComputedStyle(testElement);
                    var tailwindWorks = computedStyle.display === 'none';
                    
                    document.body.removeChild(testElement);
                    
                    if (!tailwindWorks || document.documentElement.classList.contains('tailwind-fallback')) {
                        var style = document.createElement('style');
                        style.id = 'edge-emergency-styles';
                        style.textContent = `
                            body { font-size: 16px !important; }
                            .container { max-width: 1280px !important; margin: 0 auto !important; padding: 0 1rem !important; }
                            h1 { font-size: 2.25rem !important; }
                            @media (min-width: 768px) { h1 { font-size: 3.75rem !important; } }
                            @media (min-width: 1024px) { h1 { font-size: 4.5rem !important; } }
                            img { max-width: 100% !important; height: auto !important; }
                        `;
                        document.head.appendChild(style);
                    }
                }, 500);
            }
        })();
        
        // بستن منو با کلید Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (typeof Alpine !== 'undefined' && Alpine.store) {
                    // اگر Alpine.js در دسترس است
                } else {
                    // Fallback
                    const sidebar = document.querySelector('.chat-menu-sidebar');
                    const overlay = document.querySelector('.chat-menu-overlay');
                    if (sidebar && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                }
            }
        });
        
        // Force User Dropdown به سمت راست در صفحه Chat
        function fixUserDropdownPosition() {
            const dropdowns = document.querySelectorAll('.chat-user-dropdown');
            dropdowns.forEach(dropdown => {
                dropdown.style.right = '0';
                dropdown.style.left = 'auto';
                dropdown.style.transformOrigin = 'top right';
                
                // در موبایل
                if (window.innerWidth <= 768) {
                    dropdown.style.maxWidth = 'calc(100vw - 1rem)';
                    dropdown.style.right = '0.5rem';
                } else {
                    dropdown.style.right = '0';
                }
            });
        }
        
        // اجرا هنگام لود صفحه
        document.addEventListener('DOMContentLoaded', fixUserDropdownPosition);
        
        // اجرا هنگام تغییر سایز صفحه
        window.addEventListener('resize', fixUserDropdownPosition);
        
        // اجرا هنگام باز شدن dropdown (با استفاده از MutationObserver)
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const target = mutation.target;
                    if (target.classList.contains('chat-user-dropdown')) {
                        setTimeout(fixUserDropdownPosition, 10);
                    }
                }
            });
        });
        
        // مشاهده تغییرات در dropdown
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.chat-user-dropdown');
            dropdowns.forEach(dropdown => {
                observer.observe(dropdown, {
                    attributes: true,
                    attributeFilter: ['style', 'class']
                });
            });
        });
    </script>
</body>
</html>

