{{-- 
    Universal Navbar Component
    نوار ناوبری یکپارچه برای تمام صفحات
    این navbar با هر دو فریمورک Tailwind و Bootstrap سازگار است
--}}

@php
    $locales = [
        'fa' => ['label' => 'فارسی', 'abbr' => 'FA', 'flag' => '🇮🇷'],
        'en' => ['label' => 'English', 'abbr' => 'EN', 'flag' => '🇬🇧'],
        'ar' => ['label' => 'العربية', 'abbr' => 'AR', 'flag' => '🇸🇦'],
    ];
    
    $currentLocale = app()->getLocale();
    $isAuth = auth()->check();
@endphp

<style>
    /* Navbar Specific Styles */
    .navbar-universal {
        background-color: var(--navbar-light);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
    }
    
    body.dark-mode .navbar-universal {
        background-color: var(--navbar-dark);
    }
    
    .navbar-universal .nav-link {
        color: var(--color-pure-white);
        transition: color 0.3s ease;
    }
    
    .navbar-universal .nav-link:hover {
        color: var(--color-digital-gold);
    }
    
    /* Hamburger Menu Styling */
    .hamburger-menu {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
        width: 30px;
        height: 25px;
        cursor: pointer;
        z-index: 50;
    }

    .hamburger-menu span {
        display: block;
        width: 100%;
        height: 3px;
        background-color: var(--color-pure-white);
        border-radius: 5px;
        transition: all 0.3s ease-in-out;
    }

    .hamburger-menu.open span:nth-child(1) { transform: translateY(11px) rotate(45deg); }
    .hamburger-menu.open span:nth-child(2) { opacity: 0; }
    .hamburger-menu.open span:nth-child(3) { transform: translateY(-11px) rotate(-45deg); }

    .mobile-nav-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: var(--card-light);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        padding: 1rem 0;
        z-index: 40;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }
    
    .mobile-nav-menu.show {
        max-height: 600px;
    }
    
    body.dark-mode .mobile-nav-menu {
        background-color: var(--card-dark);
    }
    
    /* Theme Toggle in Navbar */
    .theme-toggle-navbar {
        position: relative;
        width: 50px;
        height: 26px;
        background-color: rgba(255, 255, 255, 0.3);
        border-radius: 34px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .theme-toggle-navbar.dark {
        background-color: var(--color-digital-gold);
    }

    .theme-toggle-navbar .theme-toggle-slider {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 20px;
        height: 20px;
        background-color: white;
        border-radius: 50%;
        transition: transform 0.3s;
    }

    .theme-toggle-navbar.dark .theme-toggle-slider {
        transform: translateX(24px);
    }

    .theme-toggle-navbar .theme-toggle-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
    }

    .theme-toggle-navbar .theme-toggle-icon.sun {
        left: 6px;
    }

    .theme-toggle-navbar .theme-toggle-icon.moon {
        right: 6px;
    }
</style>

<header class="navbar-universal sticky top-0 z-50 py-3 px-4 md:px-6">
    <div class="container mx-auto">
        <div class="flex justify-between items-center">
            {{-- Logo Section --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 md:h-12" style="filter: brightness(0) invert(1);">
                    <span class="text-white text-xl md:text-2xl font-bold hidden sm:inline">EarthCoop</span>
                </a>
            </div>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center gap-6">
                @if($isAuth)
                    <a href="{{ route('home') }}" class="nav-link font-medium">
                        <i class="fas fa-home me-1"></i>{{ __('navigation.home') ?? 'خانه' }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="nav-link font-medium">
                        <i class="fas fa-blog me-1"></i>{{ __('navigation.blog') ?? 'بلاگ' }}
                    </a>
                    <a href="{{ route('stock.book') }}" class="nav-link font-medium">
                        <i class="fas fa-chart-line me-1"></i>{{ __('navigation.stock_office') ?? 'دفتر سهام' }}
                    </a>
                @else
                    <a href="{{ route('welcome') }}" class="nav-link font-medium">
                        {{ __('langWelcome.nav_home') ?? 'خانه' }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="nav-link font-medium">
                        <i class="fas fa-blog me-1"></i>{{ __('navigation.blog') ?? 'بلاگ' }}
                    </a>
                @endif
            </nav>

            {{-- Desktop Actions --}}
            <div class="hidden lg:flex items-center gap-3">
                {{-- Theme Toggle --}}
                <div class="theme-toggle-navbar" onclick="toggleTheme()" title="{{ __('navigation.theme_toggle') ?? 'تغییر تم' }}">
                    <span class="theme-toggle-icon sun">☀️</span>
                    <span class="theme-toggle-icon moon">🌙</span>
                    <div class="theme-toggle-slider"></div>
                </div>

                {{-- Language Switcher --}}
                <div class="relative">
                    <button type="button" id="lang-dropdown-btn" class="flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-2 rounded-lg transition">
                        <span class="text-white font-semibold">{{ $locales[$currentLocale]['flag'] ?? '🌐' }}</span>
                        <span class="text-white text-sm">{{ $locales[$currentLocale]['abbr'] ?? strtoupper($currentLocale) }}</span>
                        <i class="fas fa-chevron-down text-white text-xs"></i>
                    </button>
                    <div id="lang-dropdown" class="absolute left-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden">
                        @foreach($locales as $code => $meta)
                            <a href="{{ route('locale.change', $code) }}" 
                               class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition {{ $currentLocale === $code ? 'bg-gray-50 dark:bg-gray-700' : '' }}">
                                <span>{{ $meta['flag'] }}</span>
                                <span class="text-sm">{{ $meta['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Auth Links --}}
                @if($isAuth)
                    {{-- User Dropdown --}}
                    <div class="relative">
                        <button type="button" id="user-dropdown-btn" class="flex items-center gap-2 bg-white bg-opacity-20 hover:bg-opacity-30 px-4 py-2 rounded-lg transition">
                            <i class="fas fa-user-circle text-white text-xl"></i>
                            <span class="text-white font-medium">{{ Auth::user()->fullName() }}</span>
                            <i class="fas fa-chevron-down text-white text-xs"></i>
                        </button>
                        <div id="user-dropdown" class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg hidden">
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i class="fas fa-user"></i>
                                <span>{{ __('navigation.profile') ?? 'پروفایل' }}</span>
                            </a>
                            <a href="{{ route('wallet.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i class="fas fa-wallet"></i>
                                <span>{{ __('navigation.wallet') ?? 'کیف پول' }}</span>
                            </a>
                            <a href="{{ route('holding.index') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <i class="fas fa-chart-pie"></i>
                                <span>{{ __('navigation.holdings') ?? 'دارایی‌ها' }}</span>
                            </a>
                            <hr class="my-1">
                            @if(auth()->user()->is_admin == 1)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition text-blue-600">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>{{ __('navigation.admin_dashboard') ?? 'پنل مدیریت' }}</span>
                                </a>
                                <hr class="my-1">
                            @endif
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-navbar-component').submit();" class="flex items-center gap-2 px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900 transition text-red-600">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>{{ __('navigation.logout') ?? 'خروج' }}</span>
                            </a>
                            <form id="logout-form-navbar-component" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="bg-white text-earth-green px-6 py-2 rounded-full font-medium hover:bg-opacity-90 transition">
                        {{ __('langWelcome.btn_login') ?? 'ورود' }}
                    </a>
                    <a href="{{ route('register') }}" class="bg-digital-gold text-white px-6 py-2 rounded-full font-medium hover:bg-opacity-90 transition">
                        {{ __('langWelcome.btn_join') ?? 'عضویت' }}
                    </a>
                @endif
            </div>

            {{-- Mobile Menu Button --}}
            <button id="mobile-menu-toggle" class="lg:hidden hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="mobile-nav-menu">
            <nav class="flex flex-col gap-3 px-4">
                @if($isAuth)
                    <a href="{{ route('home') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        <i class="fas fa-home me-2"></i>{{ __('navigation.home') ?? 'خانه' }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        <i class="fas fa-blog me-2"></i>{{ __('navigation.blog') ?? 'بلاگ' }}
                    </a>
                    <a href="{{ route('stock.book') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        <i class="fas fa-chart-line me-2"></i>{{ __('navigation.stock_office') ?? 'دفتر سهام' }}
                    </a>
                    <a href="{{ route('profile.show') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        <i class="fas fa-user me-2"></i>{{ __('navigation.profile') ?? 'پروفایل' }}
                    </a>
                    <hr>
                    @if(auth()->user()->is_admin == 1)
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-700 font-medium py-2">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('navigation.admin_dashboard') ?? 'پنل مدیریت' }}
                        </a>
                    @endif
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-navbar-mobile').submit();" class="text-red-600 hover:text-red-700 font-medium py-2">
                        <i class="fas fa-sign-out-alt me-2"></i>{{ __('navigation.logout') ?? 'خروج' }}
                    </a>
                    <form id="logout-form-navbar-mobile" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('welcome') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        {{ __('langWelcome.nav_home') ?? 'خانه' }}
                    </a>
                    <a href="{{ route('blog.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-earth-green font-medium py-2">
                        <i class="fas fa-blog me-2"></i>{{ __('navigation.blog') ?? 'بلاگ' }}
                    </a>
                    <hr>
                    <a href="{{ route('login') }}" class="bg-earth-green text-white text-center px-6 py-2 rounded-full font-medium">
                        {{ __('langWelcome.btn_login') ?? 'ورود' }}
                    </a>
                    <a href="{{ route('register') }}" class="bg-digital-gold text-white text-center px-6 py-2 rounded-full font-medium">
                        {{ __('langWelcome.btn_join') ?? 'عضویت' }}
                    </a>
                @endif
                
                <hr>
                
                {{-- Theme Toggle Mobile --}}
                <div class="flex items-center justify-between py-2">
                    <span class="text-gray-700 dark:text-gray-300 font-medium">{{ __('navigation.theme_toggle') ?? 'تم' }}</span>
                    <div class="theme-toggle-navbar" onclick="toggleTheme()">
                        <span class="theme-toggle-icon sun">☀️</span>
                        <span class="theme-toggle-icon moon">🌙</span>
                        <div class="theme-toggle-slider"></div>
                    </div>
                </div>
                
                {{-- Language Switcher Mobile --}}
                <div class="flex gap-2 py-2">
                    @foreach($locales as $code => $meta)
                        <a href="{{ route('locale.change', $code) }}" 
                           class="flex-1 text-center px-3 py-2 rounded-lg transition {{ $currentLocale === $code ? 'bg-earth-green text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                            {{ $meta['flag'] }} {{ $meta['abbr'] }}
                        </a>
                    @endforeach
                </div>
            </nav>
        </div>
    </div>
</header>

<script>
    // Mobile Menu Toggle
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');
        
        if (mobileMenuToggle && mobileMenu) {
            mobileMenuToggle.addEventListener('click', function() {
                this.classList.toggle('open');
                mobileMenu.classList.toggle('show');
            });
        }
        
        // Language Dropdown
        const langBtn = document.getElementById('lang-dropdown-btn');
        const langDropdown = document.getElementById('lang-dropdown');
        
        if (langBtn && langDropdown) {
            langBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                langDropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                langDropdown.classList.add('hidden');
            });
        }
        
        // User Dropdown
        const userBtn = document.getElementById('user-dropdown-btn');
        const userDropdown = document.getElementById('user-dropdown');
        
        if (userBtn && userDropdown) {
            userBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdown.classList.toggle('hidden');
            });
            
            document.addEventListener('click', function() {
                userDropdown.classList.add('hidden');
            });
        }
    });
</script>
