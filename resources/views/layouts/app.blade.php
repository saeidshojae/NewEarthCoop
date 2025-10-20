<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- عنوان صفحه -->
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <link rel="stylesheet" href="{{ asset('Css/fonts.css') }}">
    <!-- استفاده از Vite برای فایل‌های CSS و JS -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @yield('head-tag')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .modal-content{
            direction: rtl;
        }
                .modal-content .btn-close{
            margin: 0;
        }
        
        .modal-content .btn{
            width: 100%;
        }
        .bg-primary{
            background: #459f96 !important;
        }

        .alert-info{
            border: 1px solid #37c4b4;
            color: #37c4b4;
            background-color: transparent
        }
        
        /*.btn:hover{*/
        /*    background-color: #459f96 !important;*/
        /*}*/

        .btn {
    background: #37c4b4;
                        color: #ffffff !important;
            border: none;
                        width: 100%;
                        margin: .2rem;

        }

        .remove-selection{
            padding: 0 .4rem !important
        }
        .navbar{ 
            background-color: #459f96 !important;

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
<body class="bg-light">
    <div id="app2">
        <!-- نوار ناوبری -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
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
                        
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link text-white fw-semibold" href="{{ route('login') }}">
                                        {{ __('ورود') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"> 
                                    <a class="nav-link text-white fw-semibold" href="{{ route('register') }}">
                                        {{ __('ثبت‌نام') }}
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
                                        {{ __('پروفایل') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header text-primary">دفتر سهام</h6>
                                    <a class="dropdown-item fw-semibold" href="{{ route('auction.index') }}">
                                        <i class="fas fa-gavel me-2"></i>حراج‌های سهام
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('wallet.index') }}">
                                        <i class="fas fa-wallet me-2"></i>کیف‌پول
                                    </a>
                                    <a class="dropdown-item fw-semibold" href="{{ route('holding.index') }}">
                                        <i class="fas fa-chart-line me-2"></i>کیف‌سهام
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item fw-semibold" href="{{ route('terms') }}">
                                        {{ __('اساسنامه') }}
                                    </a>
                                      <a class="dropdown-item fw-semibold" href="{{ route('spring-accounts') }}">
                                        {{ __('توافقنامه مالی') }}
                                    </a>
                                    <a class="dropdown-item text-danger fw-semibold" href="{{ route('logout') }}">
                                        {{ __('خروج') }}
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                        
                        
                            @if(auth()->check())
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('auction.index') }}">
                                    <i class="fas fa-chart-line me-1"></i>دفتر سهام
                                </a></li>
                            @endif
                            @foreach(\App\Models\Page::where('is_published', 1)->get() as $page)
                                <li class="nav-item"><a class="nav-link text-white fw-semibold"  href='{{ url('/pages/' . $page->slug) }}'>{{ $page->title }}</a></li>
                            @endforeach
                            @if (auth()->check() && auth()->user()->is_admin == 1)
                                <li class="nav-item"><a class="nav-link text-white fw-semibold" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-house-door"></i>ورود به پنل مدیریت</a>
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
</body>
</html>
