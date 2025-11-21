<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EarthCoop - خانه‌ای برای همه؛ همکاری برای آینده</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;500;700;800;900&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Custom Tailwind Configuration - پیکربندی رنگ‌ها و فونت‌ها */
        :root {
            --color-earth-green: #10b981;
            --color-ocean-blue: #3b82f6;
            --color-digital-gold: #f59e0b;
            --color-pure-white: #ffffff;
            --color-light-gray: #f8fafc;
            --color-gentle-black: #1e293b;
            --color-dark-green: #047857;
            --color-dark-blue: #1d4ed8;
            --color-accent-peach: #ff7e5f;
            --color-accent-sky: #6dd5ed;
            --color-purple-700: #6B46C1;
        }

        /* Utility classes for custom colors */
        .bg-earth-green { background-color: var(--color-earth-green); }
        .text-earth-green { color: var(--color-earth-green); }
        .bg-ocean-blue { background-color: var(--color-ocean-blue); }
        .text-ocean-blue { color: var(--color-ocean-blue); }
        .bg-digital-gold { background-color: var(--color-digital-gold); }
        .text-digital-gold { color: var(--color-digital-gold); }
        .bg-pure-white { background-color: var(--color-pure-white); }
        .text-pure-white { color: var(--color-pure-white); }
        .bg-light-gray { background-color: var(--color-light-gray); }
        .text-light-gray { color: var(--color-light-gray); }
        .bg-gentle-black { background-color: var(--color-gentle-black); }
        .text-gentle-black { color: var(--color-gentle-black); }
        .bg-dark-green { background-color: var(--color-dark-green); }
        .bg-dark-blue { background-color: var(--color-dark-blue); }
        .bg-accent-peach { background-color: var(--color-accent-peach); }
        .text-accent-peach { color: var(--color-accent-peach); }
        .bg-accent-sky { background-color: var(--color-accent-sky); }
        .text-accent-sky { color: var(--color-accent-sky); }
        .text-purple-700 { color: var(--color-purple-700); }

        /* Font Families */
        .font-vazirmatn { font-family: 'Vazirmatn', sans-serif; }
        .font-poppins { font-family: 'Poppins', sans-serif; }

        /* Custom animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        @keyframes glow {
            0% { box-shadow: 0 0 15px rgba(245, 158, 11, 0.4), 0 0 25px rgba(245, 158, 11, 0.2); }
            50% { box-shadow: 0 0 30px rgba(245, 158, 11, 0.7), 0 0 40px rgba(245, 158, 11, 0.4); }
            100% { box-shadow: 0 0 15px rgba(245, 158, 11, 0.4), 0 0 25px rgba(245, 158, 11, 0.2); }
        }

        @keyframes pulse-light {
            0% { opacity: 1; }
            50% { opacity: 0.8; }
            100% { opacity: 1; }
        }

        @keyframes rotate {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) translateZ(75px) scale(1); opacity: 1; }
            100% { transform: translate(-50%, -50%) translateZ(75px) scale(1.1); opacity: 0.8; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        @keyframes bounce-custom {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }

        /* Apply animations */
        .animate-glow { animation: glow 3s infinite ease-in-out; }
        .animate-float { animation: float 6s infinite ease-in-out; }
        .animate-pulse-light { animation: pulse-light 2s infinite ease-in-out; }
        .animate-bounce-custom { animation: bounce-custom 3s infinite ease-in-out; }

        /* Coin flip animation */
        @keyframes flip-coin {
            0% { transform: rotateY(0deg); }
            100% { transform: rotateY(360deg); }
        }

        .animate-flip-coin {
            animation: flip-coin 4s infinite ease-in-out;
        }

        .coin-flip-wrapper {
            position: relative;
            transform-style: preserve-3d;
        }

        .coin-face {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            backface-visibility: hidden;
            box-shadow: 0 15px 40px rgba(245, 158, 11, 0.4), inset 0 0 15px rgba(255, 255, 255, 0.5);
            border: 4px solid var(--color-digital-gold);
        }

        .coin-back {
            transform: rotateY(180deg);
        }

        /* Smooth scroll animations */
        .fade-in-section {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Hamburger menu styling */
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
            background-color: var(--color-gentle-black);
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }

        .hamburger-menu.open span:nth-child(1) { transform: translateY(11px) rotate(45deg); }
        .hamburger-menu.open span:nth-child(2) { opacity: 0; }
        .hamburger-menu.open span:nth-child(3) { transform: translateY(-11px) rotate(-45deg); }

        .mobile-nav-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 100%;
            background-color: var(--color-pure-white);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            padding: 1rem 0;
            z-index: 40;
        }

        /* Gradient backgrounds */
        .hero-gradient {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
        }

        .feature-card {
            background: linear-gradient(145deg, #ffffff 0%, #f0f4f7 100%);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            transition: all 0.4s ease;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            border: 1px solid rgba(220, 220, 220, 0.3);
        }

        .feature-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue), var(--color-digital-gold));
        }

        .testimonial-card {
            background: linear-gradient(145deg, #ffffff 0%, #f0f4f7 100%);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
            border-radius: 18px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(220, 220, 220, 0.3);
            transition: all 0.4s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .testimonial-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue));
        }

        /* How it works steps */
        .how-it-works-step {
            position: relative;
            padding-bottom: 40px;
        }

        .how-it-works-step:not(:last-child)::after {
            content: none;
        }

        @media (max-width: 768px) {
            .how-it-works-step {
                padding-bottom: 60px;
            }
            .how-it-works-step:not(:last-child)::after {
                content: none;
            }
        }

        /* Globe and its elements */
        .globe-container {
            position: relative;
            width: 100%;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
        }

        .globe {
            width: 150px;
            height: 150px;
            background: url('https://images.unsplash.com/photo-1614730321146-b6fa89a875eb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80') center/cover;
            border-radius: 50%;
            box-shadow: 0 0 60px rgba(59, 130, 246, 0.4), inset 0 0 20px rgba(255, 255, 255, 0.3);
            position: relative;
            animation: rotate 60s linear infinite;
            transform-style: preserve-3d;
        }

        .globe::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 30% 30%, transparent 50%, rgba(16, 185, 129, 0.15) 100%);
            border-radius: 50%;
            z-index: 1;
        }

        .globe-dots {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            transform-style: preserve-3d;
            z-index: 2;
        }

        .globe-dot {
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--color-earth-green);
            border-radius: 50%;
            transform: translate(-50%, -50%) translateZ(75px);
            box-shadow: 0 0 10px var(--color-earth-green), 0 0 15px rgba(255, 255, 255, 0.5);
            animation: pulse 1.5s infinite alternate ease-in-out;
        }

        .globe-dot:nth-child(1) { top: 20%; left: 30%; background-color: var(--color-earth-green); }
        .globe-dot:nth-child(2) { top: 40%; left: 70%; background-color: var(--color-ocean-blue); animation-delay: 0.3s; }
        .globe-dot:nth-child(3) { top: 60%; left: 20%; background-color: var(--color-digital-gold); animation-delay: 0.6s; }
        .globe-dot:nth-child(4) { top: 75%; left: 60%; background-color: var(--color-earth-green); animation-delay: 0.9s; }
        .globe-dot:nth-child(5) { top: 30%; left: 50%; background-color: var(--color-ocean-blue); animation-delay: 1.2s; }
        .globe-dot:nth-child(6) { top: 55%; left: 90%; background-color: var(--color-digital-gold); animation-delay: 1.5s; }
        .globe-dot:nth-child(7) { top: 10%; left: 60%; background-color: var(--color-earth-green); animation-delay: 1.8s; }

        /* Stats item styling for RTL */
        .stats-item {
            position: relative;
            padding-right: 35px;
            text-align: right;
        }

        .stats-item::before {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--color-earth-green);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            color: var(--color-pure-white);
            font-size: 0.8rem;
        }

        .stats-item:nth-child(1)::before { content: '\f007'; background-color: var(--color-earth-green); }
        .stats-item:nth-child(2)::before { content: '\f0ae'; background-color: var(--color-ocean-blue); }
        .stats-item:nth-child(3)::before { content: '\f0ac'; background-color: var(--color-digital-gold); }

        /* RTL specific adjustments for floating cards */
        .hero-image-card-right {
            position: absolute;
            bottom: -8px;
            left: -8px;
            background-color: white;
            padding: 1.25rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transform: rotate(-6deg);
            transition: transform 0.5s;
        }

        .hero-image-card-right:hover {
            transform: rotate(0deg);
        }

        .hero-image-card-left {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: white;
            padding: 1.25rem;
            border-radius: 1.5rem;
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            transform: rotate(6deg);
            transition: transform 0.5s;
        }

        .hero-image-card-left:hover {
            transform: rotate(0deg);
        }

        /* Custom styling for new sections */
        .section-separator {
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue), var(--color-digital-gold));
            border-radius: 5px;
            margin: 0 auto 2.5rem auto;
        }
    </style>
</head>
<body class="font-poppins text-gentle-black leading-relaxed bg-light-gray">

<header class="bg-pure-white shadow-md py-4 px-6 md:px-8 sticky top-0 z-50">
    <div class="container mx-auto flex justify-between items-center">
        <div class="flex items-center space-x-3 md:space-x-reverse md:space-x-3 rtl:space-x-reverse rtl:space-x-3">
            <svg width="45" height="45" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="animate-bounce-custom">
                <path d="M12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2Z" fill="#10b981" opacity="0.8"/>
                <path d="M12 2C10.5 4 8 6 8 9C8 12 12 14 12 14C12 14 16 12 16 9C16 6 13.5 4 12 2ZM12 14C12 14 10 16 10 18C10 20 12 22 12 22" fill="#047857"/>
            </svg>
            <span class="text-2xl md:text-3xl font-extrabold text-gentle-black font-vazirmatn">EarthCoop</span>
            <span class="text-sm text-gray-500 hidden md:block border-r-2 border-gray-200 pr-4 mr-4 font-vazirmatn">"خانه‌ای برای همه؛ همکاری برای آینده"</span>
        </div>

        <nav class="hidden md:flex items-center space-x-8 rtl:space-x-reverse rtl:space-x-8 font-vazirmatn text-gentle-black">
            <a href="{{ route('welcome') }}" class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group">
                خانه
                <span class="absolute bottom-0 right-0 w-0 h-0.5 bg-earth-green group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#about" class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group">
                درباره ارث کوپ
                <span class="absolute bottom-0 right-0 w-0 h-0.5 bg-earth-green group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#how-it-works" class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group">
                راهنما
                <span class="absolute bottom-0 right-0 w-0 h-0.5 bg-earth-green group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#projects" class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group">
                پروژه‌ها
                <span class="absolute bottom-0 right-0 w-0 h-0.5 bg-earth-green group-hover:w-full transition-all duration-300"></span>
            </a>
            <a href="#testimonials" class="relative hover:text-earth-green transition duration-300 font-medium pb-1 group">
                داستان‌ها
                <span class="absolute bottom-0 right-0 w-0 h-0.5 bg-earth-green group-hover:w-full transition-all duration-300"></span>
            </a>
        </nav>

        <div class="hidden md:flex items-center space-x-4 rtl:space-x-reverse rtl:space-x-4">
            <a href="{{ route('register.start') }}" class="bg-earth-green text-pure-white px-6 py-2 rounded-full shadow-md hover:bg-dark-green transition duration-300 font-vazirmatn font-medium transform hover:scale-105">عضویت</a>
            <a href="{{ route('login') }}" class="bg-ocean-blue text-pure-white px-6 py-2 rounded-full shadow-md hover:bg-dark-blue transition duration-300 font-vazirmatn font-medium transform hover:scale-105">ورود</a>
            <a href="{{ route('invite') }}" class="bg-digital-gold text-pure-white px-6 py-2 rounded-full shadow-md hover:bg-opacity-90 transition duration-300 font-vazirmatn font-medium transform hover:scale-105">دعوت</a>
        </div>

        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="hamburger-menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
    <div id="mobile-menu" class="mobile-nav-menu hidden md:hidden">
        <nav class="flex flex-col items-center space-y-4 font-vazirmatn text-gentle-black py-4">
            <a href="{{ route('welcome') }}" class="hover:text-earth-green transition duration-300 font-medium">خانه</a>
            <a href="#about" class="hover:text-earth-green transition duration-300 font-medium">درباره ما</a>
            <a href="#how-it-works" class="hover:text-earth-green transition duration-300 font-medium">راهنما</a>
            <a href="#projects" class="hover:text-earth-green transition duration-300 font-medium">پروژه‌ها</a>
            <a href="#testimonials" class="hover:text-earth-green transition duration-300 font-medium">داستان‌ها</a>
            <hr class="w-full border-t border-light-gray my-2">
            <a href="{{ route('register.start') }}" class="bg-earth-green text-pure-white px-5 py-2 rounded-full shadow-md w-3/4 text-center hover:bg-dark-green transition duration-300 font-vazirmatn font-medium">عضویت</a>
            <a href="{{ route('invite') }}" class="bg-digital-gold text-pure-white px-5 py-2 rounded-full shadow-md w-3/4 text-center hover:bg-opacity-90 transition duration-300 font-vazirmatn font-medium">دعوت</a>
            <a href="{{ route('login') }}" class="bg-ocean-blue text-pure-white px-5 py-2 rounded-full shadow-md w-3/4 text-center hover:bg-dark-blue transition duration-300 font-vazirmatn font-medium">ورود</a>
        </nav>
    </div>
</header>

<main>
    @include('partials.hero-section')
    @include('partials.mission-section')
    @include('partials.features-section')
    @include('partials.governance-section')
    @include('partials.network-section')
    @include('partials.how-it-works-section')
    @include('partials.bahar-economy-section')
    @include('partials.projects-section')
    @include('partials.invite-section')
    @include('partials.testimonials-section')
    @include('partials.cta-section')
</main>

@include('partials.footer')

<script>
    // Smooth scroll animation logic
    document.addEventListener('DOMContentLoaded', () => {
        const sections = document.querySelectorAll('.fade-in-section');

        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            observer.observe(section);
        });

        // Mobile menu toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenuButton.classList.toggle('open');
            });
        }
    });
</script>
</body>
</html>
