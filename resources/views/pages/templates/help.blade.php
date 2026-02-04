@extends('layouts.unified')

@section('title', $page->translated_title)

@push('styles')
<style>
    /* Custom Tailwind Configuration - Color and font configuration */
    :root {
        --color-earth-green: #10b981; /* سبز زمینی */
        --color-ocean-blue: #3b82f6; /* آبی اقیانوسی */
        --color-digital-gold: #f59e0b; /* طلایی دیجیتال */
        --color-pure-white: #ffffff; /* سفید خالص */
        --color-light-gray: #f8fafc; /* خاکستری روشن */
        --color-gentle-black: #1e293b; /* مشکی ملایم */
        --color-dark-green: #047857; /* سبز تیره */
        --color-dark-blue: #1d4ed8; /* آبی تیره */
        --color-accent-peach: #ff7e5f; /* هلویی تاکیدی */
        --color-accent-sky: #6dd5ed; /* آبی آسمانی تاکیدی */
        --color-purple-700: #6B46C1; /* بنفش تیره برای هاور */
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
    @keyframes bounce-custom {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }
    .animate-bounce-custom { animation: bounce-custom 3s infinite ease-in-out; }

    .fade-in-section {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease-out, transform 0.8s ease-out;
    }

    .fade-in-section.is-visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Gradient backgrounds */
    .hero-gradient {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
    }

    .section-separator {
        width: 100px;
        height: 5px;
        background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue), var(--color-digital-gold));
        border-radius: 5px;
        margin: 0 auto 2.5rem auto;
    }

    /* Help Guide Card Styles */
    .help-guide-card {
        background: linear-gradient(145deg, #ffffff 0%, #f0f4f7 100%);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border-radius: 18px;
        overflow: hidden;
        position: relative;
        border: 1px solid rgba(220, 220, 220, 0.3);
        margin-bottom: 1.5rem;
    }

    .help-guide-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, var(--color-earth-green), var(--color-ocean-blue), var(--color-digital-gold));
    }

    .help-guide-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.15);
    }

    .help-guide-header {
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(59, 130, 246, 0.05) 100%);
        transition: all 0.3s ease;
    }

    .help-guide-header:hover {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(59, 130, 246, 0.1) 100%);
    }

    .help-guide-header.expanded {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.15) 0%, rgba(59, 130, 246, 0.15) 100%);
    }

    .help-guide-header-content {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .help-guide-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
    }

    .help-guide-icon.earth-green {
        background: rgba(16, 185, 129, 0.15);
        color: var(--color-earth-green);
    }

    .help-guide-icon.ocean-blue {
        background: rgba(59, 130, 246, 0.15);
        color: var(--color-ocean-blue);
    }

    .help-guide-icon.digital-gold {
        background: rgba(245, 158, 11, 0.15);
        color: var(--color-digital-gold);
    }

    .help-guide-icon.accent-peach {
        background: rgba(255, 126, 95, 0.15);
        color: var(--color-accent-peach);
    }

    .help-guide-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-gentle-black);
        font-family: 'Vazirmatn', sans-serif;
    }

    .help-guide-chevron {
        font-size: 1.25rem;
        color: var(--color-earth-green);
        transition: transform 0.3s ease;
    }

    .help-guide-header.expanded .help-guide-chevron {
        transform: rotate(180deg);
    }

    .help-guide-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease-out, padding 0.5s ease-out, opacity 0.5s ease-out;
        opacity: 0;
        padding: 0 2rem;
    }

    .help-guide-content.active {
        max-height: 5000px;
        padding: 2rem;
        opacity: 1;
    }

    .help-guide-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--color-ocean-blue);
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(59, 130, 246, 0.2);
        font-family: 'Vazirmatn', sans-serif;
    }

    .help-guide-content h3:first-child {
        margin-top: 0;
    }

    .help-guide-content h3 i {
        margin-left: 0.75rem;
        color: var(--color-earth-green);
    }

    .help-guide-content p {
        font-size: 1.05rem;
        line-height: 1.8;
        color: var(--color-gentle-black);
        text-align: justify;
        margin-bottom: 1rem;
        font-family: 'Vazirmatn', sans-serif;
    }

    .help-guide-content ul {
        list-style: none;
        padding-right: 1.5rem;
        margin-bottom: 1rem;
    }

    .help-guide-content ul li {
        position: relative;
        margin-bottom: 0.75rem;
        font-size: 1rem;
        color: var(--color-gentle-black);
        padding-right: 1.5rem;
        line-height: 1.7;
        font-family: 'Vazirmatn', sans-serif;
    }

    .help-guide-content ul li::before {
        content: "\f00c";
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        color: var(--color-earth-green);
        position: absolute;
        right: 0;
        top: 0.25rem;
    }

    .help-guide-content .highlight {
        background: linear-gradient(135deg, var(--color-earth-green), var(--color-ocean-blue));
        color: var(--color-pure-white);
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .help-guide-content .note {
        background: linear-gradient(135deg, var(--color-digital-gold), var(--color-accent-peach));
        border-right: 5px solid var(--color-accent-peach);
        padding: 1.25rem 1.5rem;
        margin-top: 1.5rem;
        border-radius: 0.75rem;
        color: var(--color-pure-white);
        font-size: 1rem;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
        text-align: justify;
        font-family: 'Vazirmatn', sans-serif;
    }

    .help-guide-content .note i {
        color: var(--color-pure-white);
        margin-left: 0.75rem;
    }

    /* Page content styling */
    .page-content-help {
        text-align: justify;
        font-family: 'Vazirmatn', sans-serif;
    }

    .page-content-help h1,
    .page-content-help h2,
    .page-content-help h3 {
        color: var(--color-gentle-black);
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }

    .page-content-help h2 {
        color: var(--color-earth-green);
    }

    .page-content-help h3 {
        color: var(--color-ocean-blue);
    }

    .page-content-help p {
        margin-bottom: 1.5rem;
        line-height: 1.8;
    }

    .page-content-help ul,
    .page-content-help ol {
        margin-bottom: 1.5rem;
        padding-right: 2rem;
    }

    .page-content-help li {
        margin-bottom: 0.75rem;
    }

    .page-content-help a {
        color: var(--color-earth-green);
        text-decoration: underline;
    }

    .page-content-help a:hover {
        color: var(--color-dark-green);
    }

    .page-content-help img {
        max-width: 100%;
        height: auto;
        border-radius: 0.5rem;
        margin: 1.5rem 0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .help-guide-header {
            padding: 1.25rem 1.5rem;
        }

        .help-guide-icon {
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
        }

        .help-guide-title {
            font-size: 1.25rem;
        }

        .help-guide-content {
            padding: 0 1.5rem;
        }

        .help-guide-content.active {
            padding: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<main>
    <!-- Hero Section for Help Page -->
    <section class="relative hero-gradient py-20 md:py-32 overflow-hidden fade-in-section text-center">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-0 right-0 w-full h-full bg-pure-white/5 to-transparent z-0"></div>
            <div class="absolute inset-0 bg-pure-white/10 backdrop-blur-sm z-0"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-gentle-black font-vazirmatn mb-6 leading-tight">
                {{ $page->translated_title }}
            </h1>
            <div class="section-separator"></div>
            @if($page->meta_description)
                <p class="text-lg md:text-xl text-gray-700 mb-8 max-w-3xl font-vazirmatn mx-auto">
                    {{ $page->translated_meta_description ?? $page->meta_description }}
                </p>
            @endif
        </div>
    </section>

    <!-- Dynamic Content Section (Introduction) -->
    @if($page->translated_content)
    <section class="py-12 md:py-16 bg-pure-white fade-in-section">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto">
                <div class="page-content-help text-lg md:text-xl text-gray-700 leading-relaxed">
                    {!! $page->translated_content !!}
                </div>
            </div>
        </div>
    </section>
    @endif

    <!-- Help Guide Cards Section -->
    <section class="py-16 md:py-24 bg-light-gray fade-in-section">
        <div class="container mx-auto px-6">
            <div class="max-w-5xl mx-auto">
                <!-- Card 1: Registration -->
                <div class="help-guide-card">
                    <div class="help-guide-header" data-card="1">
                        <div class="help-guide-header-content">
                            <div class="help-guide-icon earth-green">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <h2 class="help-guide-title">پذیرش شرایط و آغاز ثبت‌نام</h2>
                        </div>
                        <i class="fas fa-chevron-down help-guide-chevron"></i>
                    </div>
                    <div class="help-guide-content" id="content-1">
                        <p>اولین گام برای پیوستن به EarthCoop، مطالعه و پذیرش <span class="highlight">اساسنامه و شرایط استفاده</span> است. با وارد کردن کد دعوت و تأیید شرایط، وارد مرحله‌ی ثبت‌نام اولیه می‌شوید.</p>
                        <h3><i class="fas fa-check-circle"></i> مراحل ثبت‌نام اولیه</h3>
                        <ul>
                            <li>آدرس ایمیل معتبر وارد کنید</li>
                            <li>یک رمز عبور تعیین کنید</li>
                            <li>ایمیل خود را تأیید نمایید</li>
                        </ul>
                        <p>همچنین می‌توانید از طریق حساب گوگل نیز ثبت‌نام کنید.</p>
                    </div>
                </div>

                <!-- Card 2: Registration Steps -->
                <div class="help-guide-card">
                    <div class="help-guide-header" data-card="2">
                        <div class="help-guide-header-content">
                            <div class="help-guide-icon ocean-blue">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h2 class="help-guide-title">مراحل ثبت‌نام در EarthCoop</h2>
                        </div>
                        <i class="fas fa-chevron-down help-guide-chevron"></i>
                    </div>
                    <div class="help-guide-content" id="content-2">
                        <h3><i class="fas fa-id-card"></i> ۱. اطلاعات هویتی</h3>
                        <p>در این مرحله اطلاعات زیر از شما دریافت می‌شود:</p>
                        <ul>
                            <li>نام و نام خانوادگی</li>
                            <li>تاریخ تولد</li>
                            <li>جنسیت</li>
                            <li>ملیت</li>
                            <li>کد ملی</li>
                            <li>شماره تلفن همراه</li>
                        </ul>
                        <p>ما برای حفظ شفافیت و هویت واقعی سهامداران، اطمینان حاصل می‌کنیم که هیچ‌کس نتواند بیش از یک حساب داشته باشد. همچنین، از اطلاعات سن و جنسیت شما برای تشکیل <span class="highlight">گروه‌های اختصاصی سنی و جنسیتی</span> استفاده می‌شود.</p>

                        <h3><i class="fas fa-briefcase"></i> ۲. اطلاعات شغلی و تخصصی</h3>
                        <p>در این بخش، باید:</p>
                        <ul>
                            <li>زمینه‌های فعالیت شغلی و صنفی</li>
                            <li>زمینه‌های علمی و تجربی</li>
                        </ul>
                        <p>خود را از فهرست موجود انتخاب کنید. انتخاب‌ها در سه سطح انجام می‌شود (مثلاً: "آموزش > معلمان > دبیر ریاضی"). امکان انتخاب چندگانه در هر بخش وجود دارد. این اطلاعات مبنای عضویت شما در <span class="highlight">گروه‌های تخصصی (صنفی و علمی)</span> خواهد بود.</p>

                        <h3><i class="fas fa-map-marker-alt"></i> ۳. اطلاعات مکانی</h3>
                        <p>در این مرحله، باید موقعیت مکانی خود را به‌صورت دقیق انتخاب کنید:</p>
                        <ul>
                            <li>قاره ← کشور ← استان ← شهرستان ← بخش ← شهر یا دهستان ← منطقه شهری یا روستا ← محله</li>
                        </ul>
                        <p>مشخص‌کردن محل سکونت تا سطح <span class="highlight">محله</span> الزامی است. به‌صورت اختیاری، می‌توانید خیابان و کوچه را هم تعیین کنید تا به گروه‌های دقیق‌تر بپیوندید. اگر مکان شما در فهرست موجود نیست، می‌توانید مکان جدیدی را پیشنهاد دهید.</p>
                        <p>با تکمیل این مرحله، با زدن دکمه <span class="highlight">«تکمیل ثبت‌نام»</span>، ثبت‌نام شما به پایان می‌رسد.</p>
                    </div>
                </div>

                <!-- Card 3: Your Home in EarthCoop -->
                <div class="help-guide-card">
                    <div class="help-guide-header" data-card="3">
                        <div class="help-guide-header-content">
                            <div class="help-guide-icon digital-gold">
                                <i class="fas fa-home"></i>
                            </div>
                            <h2 class="help-guide-title">خانه شما در EarthCoop</h2>
                        </div>
                        <i class="fas fa-chevron-down help-guide-chevron"></i>
                    </div>
                    <div class="help-guide-content" id="content-3">
                        <h3><i class="fas fa-user"></i> حساب کاربری</h3>
                        <p>در این بخش می‌توانید:</p>
                        <ul>
                            <li>اطلاعات خود را مشاهده و مدیریت کنید</li>
                            <li>نمایش یا مخفی بودن آن‌ها را برای دیگران تنظیم کنید</li>
                            <li>بیوگرافی، مدارک، تصویر و لینک‌های شخصی خود را اضافه کنید</li>
                            <li>زمینه‌های فعالیت خود را تغییر دهید (تغییر زمینه‌ها باعث تغییر عضویت گروهی شما خواهد شد)</li>
                        </ul>

                        <h3><i class="fas fa-user-friends"></i> دعوت از دوستان</h3>
                        <p>هر کاربر امکان ارسال <span class="highlight">۱۰ کد دعوت</span> دارد. هر کد تا <span class="highlight">۴۸ ساعت</span> معتبر است و پس از ثبت‌نام موفق طرف مقابل، شما به‌عنوان معرف ثبت می‌شوید.</p>
                        <div class="note">
                            <i class="fas fa-gift"></i> <span class="highlight">پاداش دعوت:</span> هر دعوت موفق معادل <span class="highlight">۱۰ بهار</span> (واحد پول داخلی EarthCoop) برابر با ارزش <span class="highlight">۱ گرم طلای خالص</span> به شما پاداش می‌دهد.
                        </div>

                        <h3><i class="fas fa-layer-group"></i> گروه‌های من</h3>
                        <p>در این بخش به تمامی گروه‌هایی که به‌صورت خودکار عضو آن‌ها شده‌اید دسترسی دارید:</p>
                        <ul>
                            <li>گروه‌های مجامع عمومی: از سطح محله تا سطح جهانی</li>
                            <li>گروه‌های تخصصی: صنفی و علمی (با امکان فیلتر بر اساس سطح مکانی)</li>
                            <li>گروه‌های اختصاصی: سنی و جنسیتی در سطوح مختلف</li>
                        </ul>
                        <p>🔎 نقش شما در هر گروه (فعال یا ناظر) نیز مشخص است:</p>
                        <ul>
                            <li><span class="highlight">ناظر:</span> امکان مشاهده، بدون مشارکت</li>
                            <li><span class="highlight">فعال:</span> امکان ارسال پست، شرکت در انتخابات، نظرسنجی، رأی‌گیری و...</li>
                        </ul>

                        <h3><i class="fas fa-comments"></i> گفتگوهای خصوصی</h3>
                        <p>فهرست چت‌های شخصی شما با سایر کاربران در این بخش قابل مدیریت است.</p>

                        <h3><i class="fas fa-map-marked-alt"></i> محیط گروه‌ها</h3>
                        <p>با کلیک بر روی نام هر گروه، وارد محیط آن گروه می‌شوید. در گروه‌هایی که فعال هستید، می‌توانید پست و نظرسنجی با موضوعاتی مانند:</p>
                        <ul>
                            <li>معرفی خود</li>
                            <li>پیشنهاد برای محله</li>
                            <li>انتقاد</li>
                            <li>گزارش مشکل</li>
                            <li>درخواست نشست مجمع عمومی</li>
                        </ul>
                        <p>منتشر کنید.</p>
                        <div class="note">
                            <i class="fas fa-info-circle"></i> <span class="highlight">نکته مهم:</span> گروه‌های محله‌ای بنیادی‌ترین بخش EarthCoop هستند. اگر تنها عضو محله‌ی خود هستید، یعنی شما بنیان‌گذار آن گروه هستید. بنابراین دعوت از هم‌محله‌ای‌ها اولویت دارد!
                        </div>

                        <h3><i class="fas fa-vote-yea"></i> انتخابات</h3>
                        <p>EarthCoop از یک <span class="highlight">سیستم انتخاباتی خودکار، لحظه‌ای و شفاف</span> استفاده می‌کند. به محض رسیدن اعضای فعال یک گروه عمومی (مثلاً محله) به <span class="highlight">حدنصاب مشخص (پیش‌فرض: ۲۰ نفر)</span>، انتخابات برای تعیین مدیران و بازرسان آغاز می‌شود.</p>
                        <p>انتخابات پویاست، یعنی:</p>
                        <ul>
                            <li>هر کاربر هر زمان می‌تواند رأی خود را ثبت یا تغییر دهد</li>
                            <li>نتایج هر سه ماه یک‌بار به‌روز می‌شوند</li>
                            <li>هیچ مدیری بدون رأی حداقلی بیش از ۳ ماه نمی‌ماند</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@push('scripts')
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

        // Accordion functionality for help guide cards
        const helpGuideHeaders = document.querySelectorAll('.help-guide-header');
        const headerElement = document.getElementById('header');

        helpGuideHeaders.forEach(header => {
            header.addEventListener('click', () => {
                const cardNumber = header.getAttribute('data-card');
                const content = document.getElementById(`content-${cardNumber}`);

                if (header.classList.contains('expanded')) {
                    // Close this card
                    content.classList.remove('active');
                    header.classList.remove('expanded');
                } else {
                    // Close all other cards
                    helpGuideHeaders.forEach(otherHeader => {
                        if (otherHeader !== header && otherHeader.classList.contains('expanded')) {
                            const otherCardNumber = otherHeader.getAttribute('data-card');
                            const otherContent = document.getElementById(`content-${otherCardNumber}`);
                            otherContent.classList.remove('active');
                            otherHeader.classList.remove('expanded');
                        }
                    });

                    // Open this card
                    content.classList.add('active');
                    header.classList.add('expanded');

                    // Smooth scroll to the opened card
                    setTimeout(() => {
                        const headerHeight = headerElement ? headerElement.offsetHeight : 0;
                        const targetPosition = header.getBoundingClientRect().top + window.scrollY - headerHeight - 40;
                        window.scrollTo({
                            top: targetPosition,
                            behavior: 'smooth'
                        });
                    }, 100);
                }
            });
        });
    });
</script>
@endpush
@endsection
