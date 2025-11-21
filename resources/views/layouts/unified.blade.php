<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ get_direction() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'New Earth Coop')</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <!-- Fonts - Same as Home Page -->
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Dark Mode -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode.css') }}">
    <script src="{{ asset('js/dark-mode.js') }}"></script>
    
    <!-- Dark Mode Fix - پشتیبانی کامل از کلاس dark-mode -->
    <link rel="stylesheet" href="{{ asset('Css/dark-mode-fix.css') }}">
    
    <!-- User Dropdown Responsive -->
    <link rel="stylesheet" href="{{ asset('Css/user-dropdown-responsive.css') }}">
    
    <!-- Unified Styles - بر اساس طراحی Home -->
    <link rel="stylesheet" href="{{ asset('Css/unified-styles.css') }}">
    
    <!-- Page Specific Styles -->
    @stack('styles')
    @yield('head-tag')
    
    <style>
        /* Alpine.js cloaking */
        [x-cloak] {
            display: none !important;
        }
    </style>
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



