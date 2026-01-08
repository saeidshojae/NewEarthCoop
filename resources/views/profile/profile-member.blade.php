@extends('layouts.unified')

@section('title', 'پروفایل کاربر - ' . config('app.name', 'EarthCoop'))

@push('styles')
<style>
    /* Profile Member Page Styles */
    .profile-member-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem 1rem;
        direction: rtl;
    }

    /* User Info Card */
    .user-info-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .user-info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .user-info-header {
        background: linear-gradient(135deg, var(--color-earth-green) 0%, var(--color-dark-green) 100%);
        color: white;
        padding: 1.5rem;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 700;
    }

    .user-info-body {
        padding: 2rem;
    }

    .profile-avatar-container {
        display: flex;
        justify-content: center;
        margin-bottom: 2rem;
    }

    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid var(--color-earth-green);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1rem;
    }

    .info-table tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .info-table tr:hover {
        background-color: #f9fafb;
    }

    .info-table th {
        padding: 1rem;
        text-align: right;
        font-weight: 700;
        color: var(--color-gentle-black);
        width: 30%;
        vertical-align: top;
    }

    .info-table td {
        padding: 1rem;
        text-align: right;
        color: #4b5563;
    }

    .info-table td a {
        color: var(--color-ocean-blue);
        text-decoration: none;
        transition: color 0.2s ease;
    }

    .info-table td a:hover {
        color: var(--color-dark-blue);
        text-decoration: underline;
    }

    /* Documents Grid */
    .documents-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-start;
    }

    .document-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .document-item img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e5e7eb;
        transition: transform 0.2s ease;
    }

    .document-item img:hover {
        transform: scale(1.05);
    }

    .document-download-btn {
        background: linear-gradient(135deg, var(--color-earth-green) 0%, var(--color-dark-green) 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .document-download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Social Networks Card */
    .social-networks-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .social-networks-header {
        background: linear-gradient(135deg, var(--color-ocean-blue) 0%, var(--color-dark-blue) 100%);
        color: white;
        padding: 1.5rem;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .social-networks-body {
        padding: 1.5rem;
    }

    .social-link-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .social-link-item:last-child {
        border-bottom: none;
    }

    .social-link-item:hover {
        background-color: #f9fafb;
    }

    .social-link-item a {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: var(--color-gentle-black);
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease;
    }

    .social-link-item a:hover {
        color: var(--color-ocean-blue);
    }

    .social-link-item i {
        font-size: 1.5rem;
        width: 30px;
        text-align: center;
    }

    /* Groups Card */
    .groups-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .groups-header {
        background: linear-gradient(135deg, var(--color-earth-green) 0%, var(--color-dark-green) 100%);
        color: white;
        padding: 1.5rem;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .groups-body {
        padding: 0;
    }

    /* Tabs - طراحی مدرن */
    .tabs-container {
        border-bottom: 2px solid #e5e7eb;
        background: #f9fafb;
        padding: 0.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }

    .tab {
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        font-weight: 600;
        border-radius: 12px;
        background: white;
        color: #6b7280;
        border: 2px solid transparent;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        text-align: center;
        flex: 1;
        min-width: 150px;
    }

    .tab:hover {
        background: #f3f4f6;
        color: var(--color-ocean-blue);
        border-color: var(--color-ocean-blue);
        transform: translateY(-2px);
    }

    .tab.active {
        background: linear-gradient(135deg, var(--color-earth-green) 0%, var(--color-dark-green) 100%);
        color: white;
        border-color: var(--color-earth-green);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Tab Content */
    .tab-content {
        display: none;
        padding: 2rem;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Groups Table */
    .groups-table {
        width: 100%;
        border-collapse: collapse;
    }

    .groups-table tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }

    .groups-table tr:hover {
        background-color: #f9fafb;
    }

    .groups-table th {
        padding: 1rem;
        text-align: right;
        font-weight: 600;
    }

    .groups-table th a {
        color: var(--color-gentle-black);
        text-decoration: none;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .groups-table th a:hover {
        color: var(--color-ocean-blue);
    }

    .groups-table th a.disabled-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
    }

    .groups-table th a:not(.disabled-link)::before {
        content: '👥';
        font-size: 1.2rem;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #9ca3af;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 1.1rem;
    }

    /* Responsive */
    @media screen and (max-width: 768px) {
        .profile-member-container {
            padding: 1rem 0.5rem;
        }

        .tabs-container {
            flex-direction: column;
        }

        .tab {
            width: 100%;
            min-width: auto;
        }

        .info-table th,
        .info-table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.9rem;
        }

        .info-table th {
            width: 35%;
        }
    }

    /* Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .user-info-card,
        .social-networks-card,
        .groups-card {
            background: #1f2937;
            color: #f9fafb;
        }

        .info-table tr:hover,
        .social-link-item:hover,
        .groups-table tr:hover {
            background-color: #374151;
        }

        .info-table th,
        .groups-table th a {
            color: #f9fafb;
        }

        .info-table td {
            color: #d1d5db;
        }

        .tabs-container {
            background: #111827;
            border-bottom-color: #374151;
        }

        .tab {
            background: #1f2937;
            color: #d1d5db;
        }

        .tab:hover {
            background: #374151;
        }
    }
</style>
@endpush

@section('content')
<div class="profile-member-container">
    <!-- User Info Card -->
    <div class="user-info-card">
        <div class="user-info-header">
            <i class="fas fa-user-circle ml-2"></i>
            پروفایل کاربر
        </div>
        <div class="user-info-body">
            <div class="profile-avatar-container">
                <div class="profile-avatar">
                    {!! $user->profile() !!}
                </div>
            </div>

            <table class="info-table">
                @if($user->show_name)
                    <tr>
                        <th><i class="fas fa-user ml-2"></i> نام:</th>
                        <td>{{ $user->fullName() }}</td>
                    </tr>
                @endif

                @if($user->show_email)
                    <tr>
                        <th><i class="fas fa-envelope ml-2"></i> ایمیل:</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endif

                @if($user->show_phone)
                    <tr>
                        <th><i class="fas fa-phone ml-2"></i> شماره تماس:</th>
                        <td>{{ $user->phone }}</td>
                    </tr>
                @endif

                @if($user->show_birthdate)
                    <tr>
                        <th><i class="fas fa-birthday-cake ml-2"></i> تاریخ تولد:</th>
                        <td>{{ $user->national_id == null ? '' : verta($user->birth_date)->format('Y-m-d') }}</td>
                    </tr>
                @endif

                @if($user->show_gender)
                    <tr>
                        <th><i class="fas fa-venus-mars ml-2"></i> جنسیت:</th>
                        @if($user->national_id != null)
                            <td>{{ $user->gender == 'male' ? 'مرد' : 'زن' }}</td>
                        @endif
                    </tr>
                @endif

                @if($user->show_national_id)
                    <tr>
                        <th><i class="fas fa-id-card ml-2"></i> کد ملی:</th>
                        <td>{{ $user->national_id }}</td>
                    </tr>
                @endif

                @if($user->show_biografie && $user->biografie)
                    <tr>
                        <th><i class="fas fa-book ml-2"></i> بیوگرافی:</th>
                        <td>{{ $user->biografie }}</td>
                    </tr>
                @endif

                @if($user->show_documents && $user->documents)
                    <tr>
                        <th><i class="fas fa-file-alt ml-2"></i> مدارک:</th>
                        <td>
                            <div class="documents-grid">
                                @php
                                    $documentsData = $user->documents;
                                    $decoded = json_decode($documentsData, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $documents = $decoded;
                                    } else {
                                        $files = explode(',', $documentsData);
                                        $documents = [];
                                        foreach ($files as $file) {
                                            $file = trim($file);
                                            if (!empty($file)) {
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                $documents[] = [
                                                    'filename' => $file,
                                                    'name' => 'مدرک',
                                                    'type' => strtolower($extension)
                                                ];
                                            }
                                        }
                                    }
                                @endphp
                                @foreach($documents as $doc)
                                    @php
                                        $filename = is_array($doc) ? $doc['filename'] : $doc;
                                        $docName = is_array($doc) ? ($doc['name'] ?? $filename) : $filename;
                                        $extension = is_array($doc) ? ($doc['type'] ?? pathinfo($filename, PATHINFO_EXTENSION)) : pathinfo($filename, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($extension), ['png', 'jpg', 'jpeg']);
                                    @endphp
                                    <div class="document-item">
                                        @if($isImage)
                                            <img src="{{ asset('images/users/documents/' . $filename) }}" alt="{{ $docName }}">
                                        @else
                                            <img src="https://www.svgrepo.com/show/452084/pdf.svg" alt="PDF Document">
                                        @endif
                                        <div class="document-name">{{ $docName }}</div>
                                        <a href="{{ asset('images/users/documents/' . $filename) }}" class="document-download-btn" download>
                                            <i class="fas fa-download"></i>
                                            دانلود
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                @endif

                @if($user->show_created_at)
                    <tr>
                        <th><i class="fas fa-calendar-alt ml-2"></i> تاریخ ثبت نام:</th>
                        <td>{{ verta($user->created_at)->format('Y-m-d') }}</td>
                    </tr>
                @endif
            </table>

            @include('chat_request', ['user' => $user])
        </div>
    </div>

    <!-- Social Networks Card -->
    @if($user->show_social_networks)
        <div class="social-networks-card">
            <div class="social-networks-header">
                <i class="fas fa-share-alt ml-2"></i>
                شبکه‌های اجتماعی
            </div>
            <div class="social-networks-body">
                @php
                    $socialLinks = json_decode($user->social_networks, true);
                @endphp

                @if(is_array($socialLinks) && count($socialLinks) > 0)
                    @foreach($socialLinks as $link)
                        @php
                            $icon = '';
                            $name = '';

                            if (stripos($link, 'instagram.com') !== false) {
                                $icon = 'fab fa-instagram';
                                $name = 'اینستاگرام';
                            } elseif (stripos($link, 't.me') !== false || stripos($link, 'telegram.me') !== false || stripos($link, 'telegram.org') !== false) {
                                $icon = 'fab fa-telegram';
                                $name = 'تلگرام';
                            } elseif (stripos($link, 'x.com') !== false || stripos($link, 'twitter.com') !== false) {
                                $icon = 'fab fa-x-twitter';
                                $name = 'توییتر';
                            } elseif (stripos($link, 'linkedin.com') !== false) {
                                $icon = 'fab fa-linkedin';
                                $name = 'لینکدین';
                            } elseif (stripos($link, 'wa.me') !== false || stripos($link, 'whatsapp.com') !== false) {
                                $icon = 'fab fa-whatsapp';
                                $name = 'واتساپ';
                            } elseif (stripos($link, 'clubhouse') !== false) {
                                $icon = 'fas fa-microphone';
                                $name = 'کلاب‌هاوس';
                            } else {
                                $icon = 'fas fa-link';
                                $name = $link;
                            }
                        @endphp

                        <div class="social-link-item">
                            <a href="{{ $link }}" target="_blank" rel="noopener noreferrer">
                                <i class="{{ $icon }}"></i>
                                <span>{{ $name }}</span>
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state">
                        <i class="fas fa-share-alt"></i>
                        <p>شبکه اجتماعی ثبت نشده است</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Groups Card -->
    @if($user->show_groups == 1)
        <div class="groups-card">
            <div class="groups-header">
                <i class="fas fa-users ml-2"></i>
                گروه‌های کاربر
            </div>
            <div class="groups-body">
                <div class="tabs-container">
                    <div class="tab active" data-tab="generalGroups">
                        <i class="fas fa-globe ml-2"></i>
                        گروه‌های مجمع عمومی
                    </div>
                    <div class="tab" data-tab="specialityGroups">
                        <i class="fas fa-briefcase ml-2"></i>
                        گروه‌های شغلی و صنفی
                    </div>
                    <div class="tab" data-tab="experienceGroups">
                        <i class="fas fa-graduation-cap ml-2"></i>
                        گروه‌های علمی و تجربی
                    </div>
                    <div class="tab" data-tab="ageGroups">
                        <i class="fas fa-birthday-cake ml-2"></i>
                        گروه‌های سنی
                    </div>
                    <div class="tab" data-tab="genderGroups">
                        <i class="fas fa-venus-mars ml-2"></i>
                        گروه‌های جنسیتی
                    </div>
                </div>

                <!-- Tab Contents - حفظ کامل منطق -->
                <div class="tab-content active" id="generalGroups">
                    @if($generalGroups->isNotEmpty())
                        <table class="groups-table">
                            @foreach ($generalGroups as $group)
                                @php
                                    $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        <a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" 
                                           class="{{ $checkCurrentUserIsHere == null ? 'disabled-link' : '' }}">
                                            {{ $group->name }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users"></i>
                            <p>گروهی در این دسته‌بندی وجود ندارد</p>
                        </div>
                    @endif
                </div>

                <div class="tab-content" id="specialityGroups">
                    @if($specialityGroups->isNotEmpty())
                        <table class="groups-table">
                            @foreach ($specialityGroups as $group)
                                @php
                                    $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        <a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" 
                                           class="{{ $checkCurrentUserIsHere == null ? 'disabled-link' : '' }}">
                                            {{ $group->name }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-briefcase"></i>
                            <p>گروهی در این دسته‌بندی وجود ندارد</p>
                        </div>
                    @endif
                </div>

                <div class="tab-content" id="experienceGroups">
                    @if($experienceGroups->isNotEmpty())
                        <table class="groups-table">
                            @foreach ($experienceGroups as $group)
                                @php
                                    $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        <a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" 
                                           class="{{ $checkCurrentUserIsHere == null ? 'disabled-link' : '' }}">
                                            {{ $group->name }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-graduation-cap"></i>
                            <p>گروهی در این دسته‌بندی وجود ندارد</p>
                        </div>
                    @endif
                </div>

                <div class="tab-content" id="ageGroups">
                    @if($ageGroups->isNotEmpty())
                        <table class="groups-table">
                            @foreach ($ageGroups as $group)
                                @php
                                    $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        <a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" 
                                           class="{{ $checkCurrentUserIsHere == null ? 'disabled-link' : '' }}">
                                            {{ $group->name }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-birthday-cake"></i>
                            <p>گروهی در این دسته‌بندی وجود ندارد</p>
                        </div>
                    @endif
                </div>

                <div class="tab-content" id="genderGroups">
                    @if($genderGroups->isNotEmpty())
                        <table class="groups-table">
                            @foreach ($genderGroups as $group)
                                @php
                                    $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                @endphp
                                <tr>
                                    <th>
                                        <a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" 
                                           class="{{ $checkCurrentUserIsHere == null ? 'disabled-link' : '' }}">
                                            {{ $group->name }}
                                        </a>
                                    </th>
                                </tr>
                            @endforeach
                        </table>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-venus-mars"></i>
                            <p>گروهی در این دسته‌بندی وجود ندارد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Tab Switching Logic - حفظ کامل منطق قبلی
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                document.querySelectorAll('.tab').forEach(item => item.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked tab
                tab.classList.add('active');
                const tabContent = document.getElementById(tab.getAttribute('data-tab'));
                if (tabContent) {
                    tabContent.classList.add('active');
                }
            });
        });
    });
</script>
@endpush

@endsection
