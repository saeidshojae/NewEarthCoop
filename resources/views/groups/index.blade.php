@extends('layouts.unified')

@section('title', __('navigation.footer_my_groups') . ' - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    /* ==================== Styles from my group.html ==================== */
    
    /* Main container layout */
    .main-container {
        display: flex;
        padding: 24px;
        gap: 32px;
        flex: 1;
        max-width: 1400px;
        margin: 0 auto;
        width: 100%;
    }

    /* Dashboard content (main content area) */
    .dashboard-content {
        flex-grow: 1;
        background-color: var(--color-pure-white);
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        min-height: calc(100vh - 170px);
        display: flex;
        flex-direction: column;
        border: 1px solid #e2e8f0;
        text-align: right;
        font-size: 1.1rem;
        color: var(--color-gentle-black);
    }

    body.dark-mode .dashboard-content {
        background-color: var(--card-dark);
        border-color: var(--border-dark);
    }

    /* Groups Section Styles */
    .groups-section h2 {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--color-dark-green);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 3px solid var(--color-earth-green);
        position: relative;
        text-align: right;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .groups-section h2::after {
        content: '';
        position: absolute;
        bottom: -3px;
        right: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, var(--color-digital-gold), var(--color-accent-peach));
        border-radius: 2px;
    }

    /* Tab Buttons */
    .tab-buttons {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 30px;
        background-color: var(--color-light-gray);
        border-radius: 0.75rem;
        padding: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        flex-wrap: wrap;
        gap: 5px;
    }

    body.dark-mode .tab-buttons {
        background-color: var(--card-dark);
    }

    .tab-button {
        padding: 12px 20px;
        border: none;
        background-color: transparent;
        color: var(--color-gentle-black);
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        flex-grow: 1;
        text-align: center;
        min-width: 180px;
    }

    body.dark-mode .tab-button {
        color: var(--text-dark);
    }

    .tab-button:hover {
        background-color: #eef2f6;
        color: var(--color-earth-green);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .tab-button.active {
        background: linear-gradient(45deg, var(--color-earth-green) 0%, var(--color-dark-green) 100%);
        color: var(--color-pure-white);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transform: translateY(-1px);
    }

    /* Tab Content */
    .tab-content {
        background-color: var(--color-pure-white);
        padding: 25px;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        min-height: 300px;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    body.dark-mode .tab-content {
        background-color: var(--card-dark);
        border-color: var(--border-dark);
    }

    /* Sub-tab Buttons */
    .sub-tab-buttons {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 20px;
        gap: 10px;
        flex-wrap: wrap;
    }

    .sub-tab-button {
        padding: 10px 18px;
        border: none;
        background-color: #f1f5f9;
        color: var(--color-gentle-black);
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    body.dark-mode .sub-tab-button {
        background-color: #2a2a2a;
        color: var(--text-dark);
    }

    .sub-tab-button:hover {
        background-color: var(--color-earth-green);
        color: white;
        transform: translateY(-2px);
    }

    .sub-tab-button.active {
        background: linear-gradient(45deg, var(--color-ocean-blue), var(--color-dark-blue));
        color: white;
    }

    /* Filter Buttons */
    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
        justify-content: flex-end;
    }

    .filter-button {
        padding: 8px 16px;
        border: 1px solid #e5e7eb;
        background-color: white;
        color: var(--color-gentle-black);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }

    body.dark-mode .filter-button {
        background-color: var(--card-dark);
        border-color: var(--border-dark);
        color: var(--text-dark);
    }

    .filter-button:hover {
        background-color: var(--color-digital-gold);
        color: white;
        border-color: var(--color-digital-gold);
    }

    .filter-button.active {
        background-color: var(--color-earth-green);
        color: white;
        border-color: var(--color-earth-green);
    }

    /* Data Table */
    .data-table-container {
        overflow-x: auto;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.95rem;
    }

    .data-table thead {
        background: linear-gradient(135deg, var(--color-earth-green), var(--color-dark-green));
        color: white;
    }

    body.dark-mode .data-table thead {
        background: linear-gradient(135deg, #047857, #065f46);
    }

    .data-table th {
        padding: 14px 16px;
        text-align: center;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 12px 16px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
    }

    body.dark-mode .data-table td {
        border-bottom-color: var(--border-dark);
    }

    .data-table tbody tr {
        transition: all 0.2s ease;
    }

    .data-table tbody tr:hover {
        background-color: #f9fafb;
        transform: scale(1.01);
    }

    body.dark-mode .data-table tbody tr:hover {
        background-color: var(--hover-dark);
    }

    /* Table Icons */
    .table-icon {
        color: var(--color-earth-green);
        font-size: 1.1rem;
    }

    /* Status Badges */
    .status-badge {
        padding: 6px 14px;
        border-radius: 9999px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }

    .status-badge.active-status {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-badge.inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Sidebar Styles */
    .sidebar {
        width: 320px;
        background-color: var(--color-pure-white);
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        flex-shrink: 0;
        position: sticky;
        top: 100px;
        height: fit-content;
        border: 1px solid #e2e8f0;
    }

    body.dark-mode .sidebar {
        background-color: var(--card-dark);
        border-color: var(--border-dark);
    }

    .sidebar-menu-item {
        margin-bottom: 0.5rem;
    }

    .sidebar-menu-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        color: var(--color-gentle-black);
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
    }

    body.dark-mode .sidebar-menu-link {
        color: var(--text-dark);
    }

    .sidebar-menu-link:hover {
        background-color: var(--color-light-gray);
        color: var(--color-earth-green);
        transform: translateX(-5px);
    }

    body.dark-mode .sidebar-menu-link:hover {
        background-color: var(--hover-dark);
    }

    .sidebar-menu-link.active {
        background-color: #dcfce7;
        color: var(--color-dark-green);
        font-weight: 600;
    }

    .sidebar-menu-link .badge {
        font-size: 0.75rem;
        padding: 2px 8px;
        border-radius: 9999px;
    }

    /* Accordion (mobile) */
    .accordion-tabs {
        display: none;
        flex-direction: column;
        gap: 1rem;
    }

    .accordion-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        background-color: var(--color-pure-white);
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .accordion-header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(45deg, var(--color-earth-green), var(--color-dark-green));
        color: var(--color-pure-white);
        border: none;
        padding: 16px 20px;
        border-radius: 0.75rem 0.75rem 0 0;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
    }

    .accordion-content {
        display: none;
        padding: 20px;
    }

    .accordion-content.active {
        display: block;
    }

    .toggle-icon {
        transition: transform 0.25s ease;
    }

    .toggle-icon.rotate {
        transform: rotate(180deg);
    }

    /* Inner toggle sections */
    .toggle-group {
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .toggle-header {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background-color: #f8fafc;
        padding: 14px 18px;
        border: none;
        font-weight: 600;
        color: var(--color-gentle-black);
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .toggle-header:hover {
        background-color: #edf2fb;
    }

    .toggle-content {
        display: none;
        padding: 16px 18px;
        background-color: var(--color-pure-white);
    }

    .toggle-content.open {
        display: block;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .main-container {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            position: relative;
            top: 0;
        }

        .tab-buttons {
            flex-direction: column;
        }

        .tab-button {
            min-width: 100%;
        }

        .tab-buttons {
            display: none;
        }

        .desktop-tabs {
            display: none;
        }

        .accordion-tabs {
            display: flex;
        }
    }
</style>
@endpush

@section('content')
@php
    $generalTable = view('groups.partials.table-basic', [
        'groups' => $generalGroups ?? collect(),
        'icon' => 'fas fa-users',
        'emptyMessage' => 'هیچ گروهی یافت نشد',
        'type' => 'general'
    ])->render();

    $specialtyFilters = [
        'all' => 'همه گروه‌های شما',
        'global' => 'جهانی',
        'continent' => 'قاره',
        'country' => 'کشور',
        'province' => 'استان',
        'county' => 'شهرستان',
        'section' => 'بخش',
        'city' => 'شهر / دهستان',
        'region' => 'منطقه / روستا',
        'neighborhood' => 'محله',
    ];

    $specialtyJobTable = view('groups.partials.table-basic', [
        'groups' => $specialityGroups ?? collect(),
        'icon' => 'fas fa-briefcase',
        'emptyMessage' => 'هیچ گروه شغلی یافت نشد',
        'filters' => $specialtyFilters,
        'tableId' => 'specialty_job_table',
        'levelKey' => 'location_level',
        'type' => 'specialty'
    ])->render();

    $specialtyExperienceTable = view('groups.partials.table-basic', [
        'groups' => $experienceGroups ?? collect(),
        'icon' => 'fas fa-graduation-cap',
        'emptyMessage' => 'هیچ گروه تجربی یافت نشد',
        'filters' => $specialtyFilters,
        'tableId' => 'specialty_experience_table',
        'levelKey' => 'scope',
        'type' => 'specialty'
    ])->render();

    $ageGroupsTable = view('groups.partials.table-basic', [
        'groups' => $ageGroups ?? collect(),
        'icon' => 'fas fa-user-clock',
        'emptyMessage' => 'هیچ گروه سنی یافت نشد',
        'type' => 'exclusive'
    ])->render();

    $genderGroupsTable = view('groups.partials.table-basic', [
        'groups' => $genderGroups ?? collect(),
        'icon' => 'fas fa-venus-mars',
        'emptyMessage' => 'هیچ گروه جنسیتی یافت نشد',
        'type' => 'exclusive'
    ])->render();

    $managedTable = '';
    if (isset($managedGroups) && $managedGroups->count() > 0) {
        $managedTable = view('groups.partials.table-managed', [
            'groups' => $managedGroups,
            'type' => 'managed'
        ])->render();
    }

    $totalGroups = ($generalGroups ?? collect())->count()
        + ($specialityGroups ?? collect())->count()
        + ($experienceGroups ?? collect())->count()
        + ($ageGroups ?? collect())->count()
        + ($genderGroups ?? collect())->count();
@endphp
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col lg:flex-row gap-8">
        {{-- Sidebar --}}
        <aside class="sidebar">
            <div class="pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold flex items-center justify-between">
                    <i class="fas fa-bars text-earth-green"></i>
                    <span class="mr-3">منو</span>
                </h2>
            </div>
            <nav>
                <ul class="space-y-2">
                    <li class="sidebar-menu-item">
                        <a href="{{ route('groups.index') }}" class="sidebar-menu-link active">
                            <i class="fas fa-users text-ocean-blue"></i>
                            <span class="flex-grow text-right mx-3">{{ __('navigation.footer_my_groups') }}</span>
                            @if($totalGroups > 0)
                                <span class="badge bg-digital-gold text-white">{{ $totalGroups }}</span>
                            @endif
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('history.index') }}" class="sidebar-menu-link">
                            <i class="fas fa-handshake text-digital-gold"></i>
                            <span class="flex-grow text-right mx-3">مشارکت‌های من</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('history.election') }}" class="sidebar-menu-link">
                            <i class="fas fa-vote-yea text-earth-green"></i>
                            <span class="flex-grow text-right mx-3">انتخابات جاری</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('history.poll') }}" class="sidebar-menu-link">
                            <i class="fas fa-chart-pie text-ocean-blue"></i>
                            <span class="flex-grow text-right mx-3">نظرسنجی‌های جاری</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        @php
                            $checkAcceptSpringAccount = \App\Models\Spring::where('user_id', auth()->user()->id)->first();
                        @endphp
                        <a href="{{ route('spring-accounts') }}" class="sidebar-menu-link {{ $checkAcceptSpringAccount && $checkAcceptSpringAccount->status == 0 ? 'blinking-item' : '' }}">
                            <i class="fas fa-wallet text-digital-gold"></i>
                            <span class="flex-grow text-right mx-3">حساب مالی نجم بهار</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('my-invation-code') }}" class="sidebar-menu-link">
                            <i class="fas fa-user-plus text-earth-green"></i>
                            <span class="flex-grow text-right mx-3">دعوت از دوستان</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('profile.edit') }}" class="sidebar-menu-link">
                            <i class="fas fa-cog text-ocean-blue"></i>
                            <span class="flex-grow text-right mx-3">ویرایش حساب کاربری</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-menu-item">
                        <a href="{{ route('user.tickets.index') }}" class="sidebar-menu-link">
                            <i class="fas fa-headset text-ocean-blue"></i>
                            <span class="flex-grow text-right mx-3">پشتیبانی</span>
                            @php
                                $openTicketsCount = \App\Models\Ticket::where(function($q) {
                                    $q->where('user_id', auth()->id())
                                      ->orWhere('email', auth()->user()->email);
                                })->whereIn('status', ['open', 'in-progress'])->count();
                            @endphp
                            @if($openTicketsCount > 0)
                            <span class="badge text-white text-xs px-2 py-1 rounded-full font-bold" style="background-color: var(--color-red-tomato);">{{ $openTicketsCount }}</span>
                            @endif
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-center text-sm text-gray-500">
                نسخه ۲.۱.۰ - EarthCoop
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="dashboard-content">
            <div class="groups-section">
                <h2>{{ __('navigation.footer_my_groups') }}</h2>

                {{-- Main Tabs (desktop) --}}
                <div class="tab-buttons">
                    <button class="tab-button active" data-target="public">
                        گروه‌های مجمع عمومی
                    </button>
                    <button class="tab-button" data-target="specialty">
                        گروه‌های تخصصی
                    </button>
                    <button class="tab-button" data-target="exclusive">
                        گروه‌های اختصاصی
                    </button>
                    @if(!empty($managedTable))
                        <button class="tab-button" data-target="managed">
                            گروه‌های مدیریتی
                        </button>
                    @endif
                </div>

                {{-- Accordion (mobile) --}}
                <div class="accordion-tabs">
                    <div class="accordion-item">
                        <button class="accordion-header" data-target="accordion-public">
                            گروه‌های مجمع عمومی
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </button>
                        <div class="accordion-content" id="accordion-public">
                            {!! $generalTable !!}
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header" data-target="accordion-specialty">
                            گروه‌های تخصصی
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </button>
                        <div class="accordion-content" id="accordion-specialty">
                            <div class="toggle-group">
                                <button class="toggle-header" data-target="job-groups-mobile">
                                    گروه‌های شغلی و صنفی شما
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </button>
                                <div id="job-groups-mobile" class="toggle-content">
                                    {!! $specialtyJobTable !!}
                                </div>
                            </div>
                            <div class="toggle-group">
                                <button class="toggle-header" data-target="experience-groups-mobile">
                                    گروه‌های تخصصی و تجربی شما
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </button>
                                <div id="experience-groups-mobile" class="toggle-content">
                                    {!! $specialtyExperienceTable !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item">
                        <button class="accordion-header" data-target="accordion-exclusive">
                            گروه‌های اختصاصی
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </button>
                        <div class="accordion-content" id="accordion-exclusive">
                            <div class="toggle-group">
                                <button class="toggle-header" data-target="age-groups-mobile">
                                    گروه‌های سنی شما
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </button>
                                <div id="age-groups-mobile" class="toggle-content">
                                    {!! $ageGroupsTable !!}
                                </div>
                            </div>
                            <div class="toggle-group">
                                <button class="toggle-header" data-target="gender-groups-mobile">
                                    گروه‌های جنسیتی شما
                                    <i class="fas fa-chevron-down toggle-icon"></i>
                                </button>
                                <div id="gender-groups-mobile" class="toggle-content">
                                    {!! $genderGroupsTable !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(!empty($managedTable))
                        <div class="accordion-item">
                            <button class="accordion-header" data-target="accordion-managed">
                                گروه‌های مدیریتی
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div class="accordion-content" id="accordion-managed">
                                {!! $managedTable !!}
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Desktop Tab Contents --}}
                <div class="desktop-tabs">
                    <div id="public" class="tab-content active">
                        <h3 class="text-xl font-bold mb-4 text-earth-green">گروه‌های مجمع عمومی</h3>
                        {!! $generalTable !!}
                    </div>

                    <div id="specialty" class="tab-content">
                        <h3 class="text-xl font-bold mb-4 text-earth-green">گروه‌های تخصصی</h3>
                        <div class="toggle-group">
                            <button class="toggle-header" data-target="job-groups-desktop">
                                گروه‌های شغلی و صنفی شما
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="job-groups-desktop" class="toggle-content">
                                {!! $specialtyJobTable !!}
                            </div>
                        </div>
                        <div class="toggle-group">
                            <button class="toggle-header" data-target="experience-groups-desktop">
                                گروه‌های تخصصی و تجربی شما
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="experience-groups-desktop" class="toggle-content">
                                {!! $specialtyExperienceTable !!}
                            </div>
                        </div>
                    </div>

                    <div id="exclusive" class="tab-content">
                        <h3 class="text-xl font-bold mb-4 text-earth-green">گروه‌های اختصاصی</h3>
                        <div class="toggle-group">
                            <button class="toggle-header" data-target="age-groups-desktop">
                                گروه‌های سنی شما
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="age-groups-desktop" class="toggle-content">
                                {!! $ageGroupsTable !!}
                            </div>
                        </div>
                        <div class="toggle-group">
                            <button class="toggle-header" data-target="gender-groups-desktop">
                                گروه‌های جنسیتی شما
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </button>
                            <div id="gender-groups-desktop" class="toggle-content">
                                {!! $genderGroupsTable !!}
                            </div>
                        </div>
                    </div>

                    @if(!empty($managedTable))
                        <div id="managed" class="tab-content">
                            <h3 class="text-xl font-bold mb-4 text-earth-green">گروه‌های مدیریتی</h3>
                            {!! $managedTable !!}
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Desktop tabs
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.desktop-tabs .tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.dataset.target;
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                this.classList.add('active');
                const targetContent = document.getElementById(targetId);
                if (targetContent) {
                    targetContent.classList.add('active');
                }
            });
        });

        // Mobile accordion
        const accordionHeaders = document.querySelectorAll('.accordion-header');
        accordionHeaders.forEach(header => {
            header.addEventListener('click', function () {
                const targetId = this.dataset.target;
                const content = document.getElementById(targetId);
                const icon = this.querySelector('.toggle-icon');
                if (!content) return;
                const isActive = content.classList.contains('active');
                content.classList.toggle('active', !isActive);
                icon?.classList.toggle('rotate', !isActive);
            });
        });

        // Inner toggle sections
        const toggleHeaders = document.querySelectorAll('.toggle-header');
        toggleHeaders.forEach(header => {
            header.addEventListener('click', function (event) {
                event.stopPropagation();
                const targetId = this.dataset.target;
                const content = document.getElementById(targetId);
                const icon = this.querySelector('.toggle-icon');
                if (!content) return;
                const isOpen = content.classList.contains('open');
                content.classList.toggle('open', !isOpen);
                icon?.classList.toggle('rotate', !isOpen);
            });
        });

        // Filters for specialty tables
        const filterContainers = document.querySelectorAll('.filter-buttons');
        filterContainers.forEach(container => {
            const targetId = container.dataset.target;
            const table = document.getElementById(targetId);
            if (!table) {
                return;
            }
            const rows = table.querySelectorAll('tbody tr[data-filter-value]');
            const buttons = container.querySelectorAll('.filter-button');

            buttons.forEach(button => {
                button.addEventListener('click', function (event) {
                    event.preventDefault();
                    const filter = this.dataset.filter;
                    buttons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    rows.forEach(row => {
                        const value = row.dataset.filterValue || 'all';
                        row.style.display = (filter === 'all' || value === filter) ? '' : 'none';
                    });
                });
            });

            const defaultButton = container.querySelector('.filter-button.active') || buttons[0];
            defaultButton?.dispatchEvent(new Event('click'));
        });
    });
</script>
@endpush
