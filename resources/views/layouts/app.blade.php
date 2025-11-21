<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- عنوان صفحه -->
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    
    <!-- Fonts - یکسان با طراحی جدید -->
    <link rel="stylesheet" href="{{ asset('Css/fonts.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- استفاده از Vite برای فایل‌های CSS و JS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Design System - سیستم طراحی یکپارچه -->
    <link rel="stylesheet" href="{{ asset('Css/design-system.css') }}">
    
    <!-- Dark Mode Styles -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @yield('head-tag')</script>

    <style>
        /* تنظیم فونت برای کل صفحه - یکسان با طراحی جدید */
        body {
            font-family: 'Vazirmatn', 'Poppins', sans-serif;
            background: var(--bg-gradient-light);
            transition: background-color 0.3s ease;
        }
        
        body.dark-mode {
            background: var(--bg-gradient-dark);
        }
        
        .modal-content{
            direction: rtl;
        }
                .modal-content .btn-close{
            margin: 0;
        }
        
        .modal-content .btn{
            width: 100%;
        }
        
        /* رنگ‌های اصلی - استفاده از متغیرهای سیستم طراحی */
        .bg-primary{
            background: var(--color-primary) !important;
        }

        .alert-info{
            border: 1px solid var(--color-primary-light);
            color: var(--color-primary-light);
            background-color: transparent
        }

        .btn {
            background: var(--color-primary-light);

                        color: #ffffff !important;
            border: none;
                        width: 100%;
                        margin: .2rem;

        }

        .remove-selection{
            padding: 0 .4rem !important
        }
        
        /* Navbar - با رنگ‌های جدید */
        .navbar{ 
            background-color: var(--navbar-light) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease;
        }
        
        body.dark-mode .navbar {
            background-color: var(--navbar-dark) !important;
        }
        
        .navbar-nav{
            direction: rtl;
            padding: 0;
        }

        .table-dark th{
            background: #46c77a19;
            color: #333
        }
        #box-widget-icon{
            width: 45px !important;
            height: 45px !important;
        }
        
        @media only screen and (max-width: 990px) {
            .main-section { 
                padding: .5rem;
                margin-top: .5rem;
            }
            .col-md-10{
                padding: 0;
            }
            .col-md-8{
                                padding: 0;

            }
        }

        .swal2-html-container{
            direction: rtl;
        }

        #navbarDropdown{
            text-align: right
        }

        .dropdown-item{
            text-align: right
        }
        
    </style>
</head>
<body class="bg-light font-vazirmatn">
    <div id="app2">
        <!-- نوار ناوبری -->
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <div style='    display: flex;
    flex-direction: column;'>
                    <a href="{{ url()->previous() == url()->current() ? route('home') : url()->previous() }}" style="margin-right: .5rem; text-decoration: none">
                        <i class="fa fa-arrow-left" style="color: #fff; font-size: 1rem"></i>
                    </a>
                    <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                        <img src="{{ asset('images/logo.png') }}" style="width: 8rem">
                    </a>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- منوی سمت چپ -->
                    <ul class="navbar-nav me-auto">
                        {{-- لینک‌های اضافی در صورت نیاز --}}
                    </ul>

                    <!-- منوی سمت راست -->
                    <ul class="navbar-nav ms-auto">
                        
                        <!-- Theme Toggle Button - بهبود یافته -->
                        <li class="nav-item d-flex align-items-center">
                            <div class="theme-toggle me-3" onclick="toggleTheme()" title="{{ __('navigation.theme_toggle') }}" style="cursor: pointer;">
                                <span class="theme-toggle-icon sun">☀️</span>
                                <span class="theme-toggle-icon moon">🌙</span>
                                <div class="theme-toggle-slider"></div>
                            </div>
                        </li>

                        @php
                            $locales = [
                                'fa' => ['label' => 'فارسی', 'flag' => '🇮🇷'],
                                'en' => ['label' => 'English', 'flag' => '🇬🇧'],
                                'ar' => ['label' => 'العربية', 'flag' => '🇸🇦'],
                            ];
                        @endphp
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-white fw-semibold d-flex align-items-center gap-1" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="{{ __('navigation.language_picker') }}">
                                <span>{{ $locales[app()->getLocale()]['flag'] ?? '🌐' }}</span>
                                <span class="d-none d-lg-inline">{{ $locales[app()->getLocale()]['label'] ?? strtoupper(app()->getLocale()) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                                @foreach($locales as $code => $meta)
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center @if(app()->getLocale() === $code) active fw-bold @endif" href="{{ route('locale.change', $code) }}">
                                            <span class="me-2">{{ $meta['flag'] }}</span>
                                            <span>{{ $meta['label'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white fw-semibold" href="{{ route('login') }}">
                                        {{ __('navigation.login') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"> 
                                    <a class="nav-link text-white fw-semibold" href="{{ route('register') }}">
                                        {{ __('navigation.register') }}
                                    </a>
                                </li>
                            @endif
                            
                        @else
<li class="nav-item {{ request()->is('groups/chat/*') ? '' : 'dropdown' }}">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle fw-bold text-white" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    
                                    {{ Auth::user()->fullName() }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item fw-semibold" href="{{ route('profile.show') }}">
                                        {{ __('navigation.profile') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header text-primary">{{ __('navigation.stock_office_section') }}</h6>
                                    <a class="dropdown-item fw-semibold" href="{{ route('auction.index') }}">
                                        <i class="fas fa-gavel me-2"></i>{{ __('navigation.auctions') }}
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('stock.book') }}">
                                        <i class="fas fa-book me-2"></i>دفتر سهام
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('wallet.index') }}">
                                        <i class="fas fa-wallet me-2"></i>{{ __('navigation.wallet') }}
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('holding.index') }}">
                                        <i class="fas fa-chart-line me-2"></i>{{ __('navigation.holdings') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item fw-semibold" href="{{ route('terms') }}">
                                        {{ __('navigation.charter') }}
                                    </a>
                                      <a class="dropdown-item fw-semibold" href="{{ route('spring-accounts') }}">
                                        {{ __('navigation.financial_agreement') }}
                                    </a>
                                    
                                    @if (auth()->check() && auth()->user()->is_admin == 1)
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header text-primary">{{ __('navigation.admin_section') }}</h6>
                                    <a class="dropdown-item fw-semibold" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-house-door me-2"></i>{{ __('navigation.admin_dashboard') }}
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('admin.blog.dashboard') }}">
                                        <i class="fas fa-blog me-2"></i>{{ __('navigation.admin_blog') }}
                                    </a>
                                    @endif
                                    
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger fw-semibold" href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('navigation.logout') }}
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        
                        
                            @if(auth()->check())
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('blog.index') }}">
                                    <i class="fas fa-blog me-1"></i>{{ __('navigation.blog') }}
                                </a></li>
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('auction.index') }}">
                                    <i class="fas fa-gavel me-1"></i>{{ __('navigation.auctions') }}
                                </a></li>
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('stock.book') }}">
                                    <i class="fas fa-book me-1"></i>دفتر سهام
                                </a></li>
                            @else
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('blog.index') }}">
                                    <i class="fas fa-blog me-1"></i>{{ __('navigation.blog') }}
                                </a></li>
                            @endif
                            @foreach(\App\Models\Page::where('is_published', 1)->get() as $page)
                                <li class="nav-item"><a class="nav-link text-white fw-semibold"  href='{{ url('/pages/' . $page->slug) }}'>{{ $page->title }}</a></li>
                            @endforeach
                            @if (auth()->check() && auth()->user()->is_admin == 1)
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house-door"></i>{{ __('navigation.admin_portal') }}</a>
                                </li>
                            @endif
                    </ul>
                </div>
            </div>
        </nav>

        <!-- محتوای اصلی -->
        <main>
            <div class="container main-section">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- اضافه کردن جاوااسکریپت‌های اختصاصی -->
    @yield('scripts')
    @stack('scripts')
@if(session()->has('clearLocalStorage'))
    <script>
        localStorage.clear(); // پاک کردن localStorage
    </script>
@endif

<script type="text/javascript">
    !function(){var i="yT1vfw",a=window,d=document;function g(){var g=d.createElement("script"),s="https://www.goftino.com/widget/"+i,l=localStorage.getItem("goftino_"+i);g.async=!0,g.src=l?s+"?o="+l:s;d.getElementsByTagName("head")[0].appendChild(g);}"complete"===d.readyState?g():a.attachEvent?a.attachEvent("onload",g):a.addEventListener("load",g,!1);}();
  </script>

    <script>
        if(window.innerWidth<769){
            window.addEventListener('goftino_ready', function () {
                Goftino.setWidget({
                    marginRight: (window.innerWidth - 70),
                    marginBottom: 10
                });
            });
        }

        function showAlert(message, type = 'info') {
    Swal.fire({
        text: message,
        icon: type,
        confirmButtonText: 'باشه',
        customClass: {
            confirmButton: 'btn btn-primary'
        }
    });
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

    </script>
    
    {{-- نجم‌هدا - دستیار هوشمند --}}
    @if(config('najm-hoda.widget.enabled', true))
        @include('components.najm-hoda-widget')
    @endif
</body>
</html>
