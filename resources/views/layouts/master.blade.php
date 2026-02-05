<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- Page Title -->
    <title>@yield('title', config('app.name', 'New Earth Coop'))</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="@yield('meta_description', 'New Earth Coop - A Cooperative Community Platform')">
    <meta name="keywords" content="@yield('meta_keywords', 'cooperative, community, earth, sustainability')">

    <!-- ========== Core CSS Files ========== -->
    @vite(['resources/css/app.css', 'resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Custom Fonts -->
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Design System - متغیرها و استایل‌های مشترک -->
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">

    <!-- Dark Mode (asset() for cPanel - lowercase paths) -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">

    <!-- Language Direction -->
    <link rel="stylesheet" href="{{ asset('css/lang-direction.css') }}">

    <!-- Edge Browser Compatibility -->
    <link rel="stylesheet" href="{{ asset('css/edge-compatibility.css') }}">

    <!-- Dark Mode Script (Load Early - asset() lowercase for cPanel) -->
    <script src="{{ asset('js/dark-mode.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Page Specific Styles -->
    @stack('styles')
    @yield('head-tag')

    <style>
    /* Override Styles for Compatibility */
    body {
        background: var(--bg-gradient-light);
        min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    body.dark-mode {
        background: var(--bg-gradient-dark);
    }

    /* Bootstrap Button Compatibility with New Design */
    .btn-primary {
        background: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
    }

    .btn-primary:hover {
        background: var(--color-primary-dark) !important;
        border-color: var(--color-primary-dark) !important;
    }

    /* Main Content Area */
    .main-content {
        min-height: calc(100vh - 200px);
        padding: 2rem 0;
    }

    @media only screen and (max-width: 768px) {
        .main-content {
            padding: 1rem 0;
        }
    }

    /* Edge Browser Compatibility Fixes - همان اصلاحات صفحه welcome */
    .edge-browser body {
        font-size: 16px !important;
        line-height: 1.5 !important;
    }

    .edge-browser .container {
        max-width: 1280px !important;
        margin-left: auto !important;
        margin-right: auto !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .edge-browser img {
        max-width: 100% !important;
        height: auto !important;
    }
    </style>

    <!-- Polyfill for CSS Variables and Edge Detection -->
    <script>
    // تشخیص Edge و اعمال فوری fallback
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
    })();
    </script>
</head>

<body class="font-vazirmatn">
    <div id="app">
        <!-- Navigation -->
        @include('components.navbar')

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('warning'))
        <div class="container mt-3">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="container mt-3">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        @include('components.footer-universal')
    </div>

    <!-- ========== Core JavaScript Files ========== -->

    <!-- Page Specific Scripts -->
    @stack('scripts')
    @yield('scripts')

    <!-- Clear LocalStorage if Session Flag Set -->
    @if(session()->has('clearLocalStorage'))
    <script>
    localStorage.clear();
    </script>
    @endif

    <!-- Goftino Chat Widget -->
    <script type="text/javascript">
    ! function() {
        var i = "yT1vfw",
            a = window,
            d = document;

        function g() {
            var g = d.createElement("script"),
                s = "https://www.goftino.com/widget/" + i,
                l = localStorage.getItem("goftino_" + i);
            g.async = !0, g.src = l ? s + "?o=" + l : s;
            d.getElementsByTagName("head")[0].appendChild(g);
        }
        "complete" === d.readyState ? g() : a.attachEvent ? a.attachEvent("onload", g) : a.addEventListener("load", g, !
            1);
    }();
    </script>

    <!-- Goftino Mobile Position -->
    <script>
    if (window.innerWidth < 769) {
        window.addEventListener('goftino_ready', function() {
            Goftino.setWidget({
                marginRight: (window.innerWidth - 70),
                marginBottom: 10
            });
        });
    }
    </script>

    <!-- SweetAlert Helper Functions -->
    <script>
    function showAlert(message, type = 'info') {
        Swal.fire({
            text: message,
            icon: type,
            confirmButtonText: '{{ __("common.ok") ?? "باشه" }}',
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

    <!-- Najm-Hoda AI Assistant Widget -->
    @if(config('najm-hoda.widget.enabled', true))
    @include('components.najm-hoda-widget')
    @endif
</body>

</html>