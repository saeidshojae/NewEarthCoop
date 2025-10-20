<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'پنل مدیریت')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.rtl.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .admin-navbar { background: #459f96; }
        .admin-navbar .navbar-brand { color: #fff; font-weight: bold; }
        .admin-navbar .nav-link { color: #fff; }
        .admin-navbar .nav-link.active { font-weight: bold; }
    </style>
    @yield('head-tag')
</head>
<body dir="rtl">
<nav class="navbar navbar-expand-lg admin-navbar mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/admin/dashboard">EarthCoop مدیریت</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/admin/dashboard">داشبورد</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/stock">مدیریت سهام</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/auctions">حراج سهام</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/users">مدیریت کاربران</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/group">مدیریت گروه‌ها</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    @yield('content')
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@yield('scripts')
</body>
</html>