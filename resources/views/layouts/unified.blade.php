<!DOCTYPE html>
<!-- Layout: unified | Tailwind via Vite (no CDN) -->
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'New Earth Coop')</title>
    @if(config('app.env') === 'local')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Fonts - Same as Home Page -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark Mode (asset() lowercase for cPanel) -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <!-- Dark Mode Fix -->
    <link rel="stylesheet" href="{{ asset('css/dark-mode-fix.css') }}">
    
    <!-- User Dropdown Responsive -->
    <link rel="stylesheet" href="{{ asset('css/user-dropdown-responsive.css') }}">
    
    <!-- Unified Styles - بر اساس طراحی Home -->
    <link rel="stylesheet" href="{{ asset('css/unified-styles.css') }}">
    
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
        
        /* اطمینان از نمایش دکمه همبرگر در موبایل */
        @media (max-width: 768px) {
            .mobile-menu-button {
                display: flex !important;
                visibility: visible !important;
                opacity: 1 !important;
            }
            
            .mobile-menu-icon-bars:not(.hidden) {
                display: inline-block !important;
            }
            
            .mobile-menu-icon-times.hidden {
                display: none !important;
            }
            
            .mobile-menu-icon-times:not(.hidden) {
                display: inline-block !important;
            }
        }

        /* Edge Browser Compatibility Fixes - همان اصلاحات صفحه welcome */
        /* فوری: تنظیم اندازه پایه برای Edge */
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

        /* تنظیم اندازه فونت‌ها برای Edge */
        .edge-browser h1,
        .tailwind-fallback h1 {
            font-size: 2.25rem !important;
            line-height: 1.2 !important;
        }

        @media (min-width: 768px) {
            .edge-browser h1,
            .tailwind-fallback h1 {
                font-size: 3.75rem !important;
            }
        }

        @media (min-width: 1024px) {
            .edge-browser h1,
            .tailwind-fallback h1 {
                font-size: 4.5rem !important;
            }
        }

        .edge-browser p,
        .tailwind-fallback p {
            font-size: 1rem !important;
            line-height: 1.5 !important;
        }

        /* جلوگیری از بزرگنمایی در Edge */
        .edge-browser img,
        .tailwind-fallback img {
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
            
            // Tailwind loaded via Vite build
            if ((isEdge || isIE)) {
                document.documentElement.classList.add('tailwind-fallback');
            }
            
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
    </script>
</head>

<body class="font-vazirmatn leading-relaxed min-h-screen flex flex-col" 
      x-data="{ 
          mobileMenuOpen: false, 
          userDropdownOpen: false,
          sidebarOpen: false
      }">
    
    <!-- Unified Header - بر اساس طراحی Home -->
    @include('components.header-unified')
    
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
    
    <!-- Unified Footer -->
    @include('components.footer-unified')
    
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
    </script>
</body>
</html>



