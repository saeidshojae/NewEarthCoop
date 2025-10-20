@extends('layouts.app')

@section('head-tag')
    <style>
        .group-avatar {
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
            background-color: #ecf5ff;
            color: rgb(61, 131, 175);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-left: 12px;
        }

        .group-info h4 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }

        .group-info p {
            margin: 2px 0 0;
            font-size: 13px;
            color: #ffffff;
            text-align: right
        }

        .table-bordered i {
            margin-right: .5rem
        }
        
        .btn-warning {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        
        .fas {
            margin: 0 !important;
        }

        .tabs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0;
            border-bottom: 2px solid #ccc;
            direction: rtl
        }

        a {
            text-decoration: none;
            color: #333
        }

        .tab {
            padding: 0.7rem 1.5rem;
            cursor: pointer;
            font-weight: bold;
            border-radius: 1rem 1rem 0 0;
            background: #e0e0e0;
            margin-left: 5px;
            width: 25%;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .tab:hover {
            background: #d97930;
    background: linear-gradient(90deg, rgb(210, 134, 75) 0%, rgba(239, 150, 81, 1) 100%);
        }

        .tab.active {
            background: #d97930;
    background: linear-gradient(90deg, rgb(210, 134, 75) 0%, rgba(239, 150, 81, 1) 100%);
            color: white;
        }

        .tab-content {
            direction: rtl;
            display: none;
            background: white;
            padding: 1rem;
            border-radius: 0 0 1rem 1rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
            transition: all 0.3s ease-in-out;
        }

        .tab-content.active {
            display: block;
        }

        @media screen and (max-width: 990px) {
            .tabs {
                flex-wrap: wrap
            }

            .tab {
                width: 100%;
                border-radius: .5rem;
                margin-bottom: 1rem
            }
        }
    </style>
@endsection

@section('content')
<div class="container" style="direction: rtl; text-align: right;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- کارت اطلاعات کاربر -->
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    پروفایل کاربر
                </div>
                <div class="card-body">
                    <div class="text-center mb-4" style="display: flex; justify-content: center;"> 
                        <!-- تصویر پروفایل -->
                        {!! $user->profile() !!}
                    </div>

                    <table class="table table-bordered">
                        @if($user->show_name)
                            <tr>
                                <th>نام:</th>
                                <td>{{ $user->fullName() }}</td>
                            </tr>
                        @endif

                        @if($user->show_email)
                            <tr>
                                <th>ایمیل:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                        @endif

                        @if($user->show_phone)
                            <tr>
                                <th>شماره تماس:</th>
                                <td>{{ $user->phone }}</td>
                            </tr>
                        @endif

                        @if($user->show_birthdate)
                            <tr>
                                <th>تاریخ تولد:</th>
                                <td>{{ $user->national_id == null ? '' : verta($user->birth_date)->format('Y-m-d') }}</td>
                            </tr>
                        @endif

                        @if($user->show_gender)
                            <tr>
                                <th>جنسیت:</th>
                                @if($user->national_id != null)
                                <td>{{ $user->gender == 'male' ? 'مرد' : 'زن' }}</td>
                                
                                @endif
                            </tr>
                        @endif

                        @if($user->show_national_id)
                            <tr>
                                <th>کد ملی:</th>
                                <td>{{ $user->national_id }}</td>
                            </tr>
                        @endif

                        @if($user->show_biografie && $user->biografie)
                            <tr>
                                <th>بیوگرافی:</th>
                                <td>{{ $user->biografie }}</td>
                            </tr>
                        @endif

                        @if($user->show_documents && $user->documents)
                            <tr>
                                <th>مدارک:</th>
                                <td style='display: flex; align-items: center; justify-content: space-between; flex-wrap:wrap'>
                                        @foreach(explode(',', $user->documents) as $file)
                                        @if(explode('.', $file)[1] == 'png' OR explode('.', $file)[1] == 'jpg' Or explode('.', $file)[1] == 'jpeg')
                                                                            <div>

                                            <img src='{{ asset('images/users/documents/' . $file) }}' width='100'>
                                            <a href="{{ asset('images/users/documents/' . $file) }}" class='btn btn-warning' style='margin: 0;
    margin-top: .5rem;' download><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
</svg></a>
</div>
                                        @else
                                                                            <div>

                                            <img src='https://www.svgrepo.com/show/452084/pdf.svg' width='100'>
                                            <a href="{{ asset('images/users/documents/' . $file) }}" class='btn btn-warning' style='margin: 0;
    margin-top: .5rem;' download><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
  <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
  <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
</svg></a>
</div>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif

                        @if($user->show_created_at)
                            <tr>
                                <th>تاریخ ثبت نام:</th>
                                <td>{{ verta($user->created_at)->format('Y-m-d') }}</td>
                            </tr>
                        @endif
                    </table>
                                        @include('chat_request', ['user' => $user])

                </div>
            </div>

            <!-- کارت شبکه‌های اجتماعی -->
            @if($user->show_social_networks)
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        شبکه های اجتماعی
                    </div>
                    <div class="card-body">
                       <table class="table table-bordered">
    @php
        $socialLinks = json_decode($user->social_networks, true);
    @endphp

    @if(is_array($socialLinks)) @foreach($socialLinks as $link)
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
                $icon = 'fab fa-x-twitter'; // یا 'fab fa-twitter'
                $name = 'توییتر';
            } elseif (stripos($link, 'linkedin.com') !== false) {
                $icon = 'fab fa-linkedin';
                $name = 'لینکدین';
            } elseif (stripos($link, 'wa.me') !== false || stripos($link, 'whatsapp.com') !== false) {
                $icon = 'fab fa-whatsapp';
                $name = 'واتساپ';
            } elseif (stripos($link, 'clubhouse') !== false) {
                $icon = 'fas fa-microphone'; // چون Font Awesome آیکون رسمی Clubhouse رو نداره
                $name = 'کلاب‌هاوس';
            } else {
                $icon = 'fas fa-link';
                $name = $link;
            }
        @endphp

        <tr>
            <th>
                <a href="{{ $link }}" style="text-decoration: none; color: #333;" target="_blank">
                    <i class="{{ $icon }}" style="margin-left: 5px;"></i> {{ $name }}
                </a>
            </th>
        </tr>
    @endforeach
    @endif
</table>

                    </div>
                </div>
            @endif

            @if($user->show_groups == 1)
                <!-- کارت گروه‌ها -->
            <div class="card mt-4">
                <div class="card-header bg-primary text-white">
                    گروه‌های کاربر
                </div>
                <div class="card-body">
                    <div class="tabs">
                        <div class="tab active" data-tab="generalGroups">گروه‌های مجمع عمومی</div>
                        <div class="tab" data-tab="specialityGroups">گروه‌های شغلی و صنفی</div>
                        <div class="tab" data-tab="experienceGroups">گروه‌های علمی و تجربی</div>
                        <div class="tab" data-tab="ageGroups">گروه‌های سنی</div>
                        <div class="tab" data-tab="genderGroups">گروه‌های جنسیتی</div>
                    </div>

                    <div class="tab-content active" id="generalGroups">
                        @if($generalGroups->isNotEmpty())
                            <table class="table table-bordered">
                                @foreach ($generalGroups as $group)
                                @php
                                        $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <tr>
                                        <th><a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" style="text-decoration: none; color: #333; {{ $checkCurrentUserIsHere == null ? 'opacity: .5' : '' }}">{{ $group->name }}</a></th>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>

                    <div class="tab-content" id="specialityGroups">
                        @if($specialityGroups->isNotEmpty())
                            <table class="table table-bordered">
                                @foreach ($specialityGroups as $group)
                                @php
                                        $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <tr>
                                        <th><a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" style="text-decoration: none; color: #333; {{ $checkCurrentUserIsHere == null ? 'opacity: .5' : '' }}">{{ $group->name }}</a></th>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>

                    <div class="tab-content" id="experienceGroups">
                        @if($experienceGroups->isNotEmpty())
                            <table class="table table-bordered">
                                @foreach ($experienceGroups as $group)
                                @php
                                        $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <tr>
                                        <th><a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" style="text-decoration: none; color: #333; {{ $checkCurrentUserIsHere == null ? 'opacity: .5' : '' }}">{{ $group->name }}</a></th>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>

                    <div class="tab-content" id="ageGroups">
                        @if($ageGroups->isNotEmpty())
                            <table class="table table-bordered">
                                @foreach ($ageGroups as $group)
                                @php
                                        $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <tr>
                                        <th><a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" style="text-decoration: none; color: #333; {{ $checkCurrentUserIsHere == null ? 'opacity: .5' : '' }}">{{ $group->name }}</a></th>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>

                    <div class="tab-content" id="genderGroups">
                        @if($genderGroups->isNotEmpty())
                            <table class="table table-bordered">
                                @foreach ($genderGroups as $group)
                                    @php
                                        $checkCurrentUserIsHere = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', auth()->user()->id)->first();
                                    @endphp
                                    <tr>
                                        <th><a href="{{ $checkCurrentUserIsHere != null ? route('groups.chat', $group->id) : '#' }}" style="text-decoration: none; color: #333; {{ $checkCurrentUserIsHere == null ? 'opacity: .5' : '' }}">{{ $group->name }}</a></th>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(item => item.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            tab.classList.add('active');
            const tabContent = document.getElementById(tab.getAttribute('data-tab'));
            tabContent.classList.add('active');
        });
    });
</script>
@endsection