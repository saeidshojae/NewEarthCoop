@extends('layouts.app')
@section('head-tag')
    <style>

        .group-avatar {
        width: 2rem;
        height: 2rem;
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
        
                .group-avatar img{
                width: 2rem;
    height: 2rem;
    border-radius: 50rem;'
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

        .table-bordered i{
            margin-right: .5rem
        }
        
        .btn-warning{
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
  a{
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
    background-color: #d0d0d0;
  }
  .tab.active {background: #d97930;
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
  .groups-table
  {
    width: 100%;
  }
  .tab-content.active {
    display: block;
  }

  .collapsible-group{
    display: flex;
    justify-content: space-between;
    align-items: start
  }
  table {
    width: 100%;
    border-collapse: collapse;
  }
  th, td {
    border: 1px solid #ccc;
    padding: 0.5rem;
    text-align: center;
  }
  th {
    background-color: #f1f1f1;
  }
  .collapsible-group {
    display: none;
    margin-bottom: 1rem;
    transition: all 0.3s ease-in-out;
  }
  .table-bordered>:not(caption)>*{
    border-width: .2rem !important
  }
  .toggle-header {
    cursor: pointer;
    background-color: #f1f1f1;
    padding: 0.7rem 1rem;
    border-radius: 0.5rem;
    margin-top: 1rem;
    font-weight: bold;
  }

  .toggle-header:hover {
    background-color: #e0e0e0;
  }

  .arrow-icon {
    transition: transform 0.3s ease;
    margin-right: 8px;
  }

  .arrow-icon.rotate {
    transform: rotate(180deg);
  }

  .toggle-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    background: #f1f1f1;
    padding: 0.7rem 1rem;
    border-radius: 0.5rem;
    margin-top: 1rem;
    font-weight: bold;
  }
  
  @media screen and (max-width: 990px) {
    .tabs{
      flex-wrap: wrap
    }

    .tab{
      width: 100%;
      border-radius: .5rem;
      margin-bottom: 1rem
    }

    .collapsible-group{
      flex-direction: column
    }
    .location-filters{
      width: 100%;
      margin-left: 0 !important;
    }

    .location-filter-wrapper{
      width: 100%;
      margin-bottom: 1rem
    }
    .location-filters .roww{
      display: flex;
      justify-content: space-between
    }
    .location-tab
    {
      width: 48%;
    }
  }
  .location-filter-wrapper {
  display: flex;
  justify-content: space-between;
  margin-bottom: 1rem;
  flex-wrap: wrap;
}

.sub-tabs, .location-filters {
  display: flex;
  flex-direction: row;
  gap: 0.5rem;
}

.location-filters {
  flex-direction: column;
  margin-left: 1rem;
}

.sub-tab, .location-tab {
  padding: 0.5rem 1rem;
  background-color: #eee;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: 0.3s;
  font-size: 0.85rem;
  white-space: nowrap;
  margin-bottom: .3rem
}

.sub-tab.active, .location-tab.active {
  background-color: #4b94c7;
  color: white;
}
    /* استایل زیرتب‌ها */
    .sub-tabs .sub-tab {
        padding: 0.25rem 0.5rem; /* کوچک‌تر */
        font-size: 0.85rem; /* سایز فونت کمتر */
        border-radius: 0.4rem;
        transition: all 0.2s ease-in-out;
        width: auto;
    }

    /* حالت انتخاب‌شده */
    .sub-tabs .sub-tab.active {
        background-color: #d97930 !important; /* آبی بوت‌استرپ */
        color: #fff !important;
        border-color: #0d6efd !important;
        box-shadow: 0 0 4px rgba(0,0,0,0.2);
    }

    /* هاور زیباتر */
    .sub-tabs .sub-tab:hover {
        background-color: #e7f0ff;
    }

</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

@endsection
@section('content')
<div class="container" style="direction: rtl; text-align: right;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- کارت اطلاعات کاربر -->
            <div class="card">
                <div class="card-header text-center bg-primary text-white">
                    حساب کاربری شما
                </div>
                <div class="card-body">
                    <div class="text-center mb-4" style="display: flex; justify-content: center;"> 
                        <!-- تصویر پروفایل -->
                        {!! $user->profile() !!}
                    </div>
                    
                                            <div style='display: flex;'>
                                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">ویرایش اطلاعات</a>
                    <a class='btn' href='{{ url('/profile-member/' . auth()->user()->id) }}'>پیش نمایش پروفایل من</a>
                                            </div><br>

                    
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                            @if($chatRequests->isNotEmpty())

                    <!-- نمایش درخواست‌های چت -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            درخواست‌های چت
                        </div>
                        <div class="card-body">
                            @if($chatRequests->isNotEmpty())
                                <div class="list-group">
                                    @foreach($chatRequests as $request)
                                        <div class="list-group-item">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">{{ $request->sender->fullName() }}</h6>
                                                    <small class="text-muted">{{ verta($request->created_at)->format('Y-m-d H:i') }}</small>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('chat-requests.accept', $request->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check"></i> پذیرفتن
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('chat-requests.reject', $request->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times"></i> رد کردن
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle"></i> درخواست چت جدیدی وجود ندارد
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    @endif

                    @foreach ($candidates as $candidate)
                        @php
                            $role = \App\Models\Vote::where('candidate_id', $candidate->user_id)->where('election_id', $candidate->election_id)->first();
                        @endphp
                        <div class="row">
                            <h5 style="padding: 1rem; text-align: center;    border-top: 1px solid var(--bs-secondary-bg);
                            border-right: 1px solid var(--bs-secondary-bg);
                            border-left: 1px solid var(--bs-secondary-bg);
                            margin: 0;">شما به عنوان {{ $role->position == 0 ? 'بازرس' : 'مدیر' }} در گروه {{ $candidate->election->group->name }} پذیرفته شدید</h5>
                            <table class="table table-bordered">
                                
                                <tr>
                                    <td>
                                        <a href="{{ route('profile.accept.candidate', ['accept', 'id' => $candidate->id]) }}" style="width: 100%" class="btn btn-success">میپذیرم</a>
                                    </td>
                                    <th>
                                        <a href="{{ route('profile.accept.candidate', ['reject', 'id' => $candidate->id]) }}" style="width: 100%" class="btn btn-danger">نمی پذیرم</a>
                                    </th>
                                </tr>
                            </table>
                        </div>
                    @endforeach

                        @if($joinGroupRequests->isNotEmpty())


                <div class="card mb-3">
                    <div class="card-header bg-primary text-white">
                        درخواست‌های افزودن به گروه
                    </div>
                    <div class="card-body">
                        @if($joinGroupRequests->isNotEmpty())
                            <div class="list-group">
                                @foreach ($joinGroupRequests as $request)
                                <div class="row">
                                    <h5 style="padding: 1rem; text-align: center; 
                                            border-top: 1px solid var(--bs-secondary-bg);
                                            border-right: 1px solid var(--bs-secondary-bg);
                                            border-left: 1px solid var(--bs-secondary-bg);
                                            margin: 0;">
                                       شما درخواست پیوستن به گروه {{ $request->group->name }} دریافت کرده اید.
                                    </h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>
                                                <a href="{{ route('profile.join.group', ['1', 'id' => $request->id]) }}" 
                                                style="width: 100%" class="btn btn-success">میپذیرم</a>
                                            </td>
                                            <th>
                                                <a href="{{ route('profile.join.group', ['0', 'id' => $request->id]) }}" 
                                                style="width: 100%" class="btn btn-danger">نمی‌پذیرم</a>
                                            </th>
                                        </tr>
                                    </table>
                                </div>
                            @endforeach
                            </div>
                        @else
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i> درخواست افزودن به گروهی وجود ندارد
                            </div>
                        @endif
                    </div>
                </div>

                                            @endif

                    <table class="table table-bordered">
                        <tr>
                            <th>نام:</th>
                            <td>{{ Auth::user()->fullName() }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'name']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_name == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        <tr>
                            <th>ایمیل:</th>
                            <td>{{ Auth::user()->email }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'email']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_email == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>

                        <tr>
                            <th>شماره تماس:</th>
                            <td>{{ Auth::user()->phone }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'phone']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_phone == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        <tr>
                            <th>تاریخ تولد:</th>
                            <td>{{ verta(Auth::user()->birth_date)->format('Y-m-d') }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'birthdate']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_birthdate == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>

                        <tr>
                            <th>جنسیت:</th>
                            <td>{{ Auth::user()->gender == 'male' ? 'مرد' : 'زن' }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'gender']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_gender == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        <tr>
                            <th>کد ملی:</th>
                            <td>{{ Auth::user()->national_id }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'national_id']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_national_id == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        <tr>
                            <th>بیوگرافی:</th>
                            <td>{{ Auth::user()->biografie == null ? '-' : Auth::user()->biografie }}</td>
                            @if (Auth::user()->biografie == null)
                                
                            @else
                                <td><a href="{{ route('profile.show.info', ['field' => 'biografie']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_biografie == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                            @endif
                        </tr>
                        <tr>
                            <th>مدارک:</th>
                            <td style='    display: flex;
    align-items: center;
    justify-content: space-between;'>@if(Auth::user()->documents == null) - @else 
                                @foreach(explode(',', auth()->user()->documents) as $file)
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
                            @endif</td>
                            
                            @if (Auth::user()->documents == null)
                                
                            @else
                            <td><a href="{{ route('profile.show.info', ['field' => 'documents']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_documents == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                            @endif
                        </tr>
                        <tr>
                            <th>تاریخ ثبت نام:</th>
                            <td>{{ verta(Auth::user()->created_at)->format('Y-m-d') }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'created_at']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_birthdate == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        <tr>
                            <th>گروه ها:</th>
                            <td>{{ Auth::user()->groups->count() . ' گروه ' }}</td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'groups']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_groups == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                        
                        <tr>
                            <th>شبکه های اجتماعی:</th>
                             @php
                        $storedLinks = $user->social_networks ?? [];
                        if (is_string($storedLinks)) {
                            $storedLinks = json_decode($storedLinks, true);
                        }
                        $socialLinks = old('options', $storedLinks);
                    @endphp
                           
                        
                            <td style='    max-width: 1rem;'> 
                                <table class="table table-bordered table-striped">
    <tbody>
        @forelse($socialLinks as $index => $link)
            <tr>
                <td style="width: 5%; text-align: center;">{{ $index + 1 }}</td>
                <td>
                    <a href="{{ $link }}" target="_blank" style="text-decoration: none; color: #333;">
                        {{ $link }}
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center text-muted">هیچ لینک اجتماعی ثبت نشده است.</td>
            </tr>
        @endforelse
    </tbody>
</table>

                            </td>
                            <td><a href="{{ route('profile.show.info', ['field' => 'social']) }}" class="btn btn-warning"><i class="{{ Auth::user()->show_social_networks == 0 ? 'fas fa-eye' : 'fas fa-eye-slash' }}"></i></a></td>
                        </tr>
                    </table>
                    
                </div> 
            </div>

                        <!-- کارت فعالیت‌های کاربر -->

<br>

@php
    // برچسب‌های زیرتب آدرس
$levelTabs = [
    'global'       => 'جهانی',
    'continent'    => 'قاره‌ای',
    'country'      => 'کشوری',
    'province'     => 'استانی',
    'county'       => 'شهرستانی',
    'section'      => 'بخشی',
    'city'         => 'شهری/ دهستانی',
    'region'       => 'منطقه‌ای/ روستایی',
    'neighborhood' => 'محله‌ای',
];

@endphp

<div class="tabs">
    <div class="tab active" data-tab="generalGroups">گروه‌های مجمع عمومی</div>
    <div class="tab" data-tab="specialityGroups">گروه‌های شغلی و صنفی</div>
    <div class="tab" data-tab="experienceGroups">گروه‌های علمی و تجربی</div>
    <div class="tab" data-tab="ageGroups">گروه‌های سنی</div>
    <div class="tab" data-tab="genderGroups">گروه‌های جنسیتی</div>
</div>

{{-- گروه‌های مجمع عمومی --}}
<div class="tab-content active" id="generalGroups">
    @include('partials.group_list', ['groups' => $generalGroups, 'user' => $user])
</div>

{{-- گروه‌های شغلی و صنفی --}}
<div class="tab-content" id="specialityGroups">
    @if($specialityGroups->isNotEmpty())
        @php
            $groupsByLevel = $specialityGroups->groupBy('location_level');
            $firstSpecActive = null;
        @endphp

        <div class="sub-tabs d-flex flex-wrap gap-2 mb-3" dir="rtl">
            @foreach($levelTabs as $lvl => $label)
                @php
                    $hasAny = isset($groupsByLevel[$lvl]) && $groupsByLevel[$lvl]->isNotEmpty();
                    if ($hasAny && is_null($firstSpecActive)) $firstSpecActive = "spec-lvl-$lvl";
                @endphp
                @if($hasAny)
                    <button class="sub-tab btn btn-sm btn-outline-primary" data-subtab="spec-lvl-{{ $lvl }}">{{ $label }}</button>
                @endif
            @endforeach
        </div>

        @foreach($levelTabs as $lvl => $label)
            @php $collection = $groupsByLevel[$lvl] ?? collect(); @endphp
            @if($collection->isNotEmpty())
                <div class="sub-tab-content {{ $firstSpecActive === "spec-lvl-$lvl" ? '' : 'd-none' }}" id="spec-lvl-{{ $lvl }}">
                    @include('partials.group_list', ['groups' => $collection, 'user' => $user])
                </div>
            @endif
        @endforeach
    @endif
</div>

{{-- گروه‌های علمی و تجربی --}}
<div class="tab-content" id="experienceGroups">
    @if($experienceGroups->isNotEmpty())
        @php
            $groupsByLevel = $experienceGroups->groupBy('location_level');
            $firstExpActive = null;
        @endphp

        <div class="sub-tabs d-flex flex-wrap gap-2 mb-3" dir="rtl">
            @foreach($levelTabs as $lvl => $label)
                @php
                    $hasAny = isset($groupsByLevel[$lvl]) && $groupsByLevel[$lvl]->isNotEmpty();
                    if ($hasAny && is_null($firstExpActive)) $firstExpActive = "exp-lvl-$lvl";
                @endphp
                @if($hasAny)
                    <button class="sub-tab btn btn-sm btn-outline-primary" data-subtab="exp-lvl-{{ $lvl }}">{{ $label }}</button>
                @endif
            @endforeach
        </div>

        @foreach($levelTabs as $lvl => $label)
            @php $collection = $groupsByLevel[$lvl] ?? collect(); @endphp
            @if($collection->isNotEmpty())
                <div class="sub-tab-content {{ $firstExpActive === "exp-lvl-$lvl" ? '' : 'd-none' }}" id="exp-lvl-{{ $lvl }}">
                    @include('partials.group_list', ['groups' => $collection, 'user' => $user])
                </div>
            @endif
        @endforeach
    @endif
</div>

{{-- گروه‌های سنی --}}
<div class="tab-content" id="ageGroups">
    @include('partials.group_list', ['groups' => $ageGroups, 'user' => $user])
</div>

{{-- گروه‌های جنسیتی --}}
<div class="tab-content" id="genderGroups">
    @include('partials.group_list', ['groups' => $genderGroups, 'user' => $user])
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // تب‌های اصلی
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(i => i.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById(tab.getAttribute('data-tab')).classList.add('active');
        });
    });

    // زیرتب‌ها
    document.querySelectorAll('.sub-tabs').forEach(subTabsWrap => {
        const subTabBtns = subTabsWrap.querySelectorAll('.sub-tab');
        const parentContent = subTabsWrap.closest('.tab-content');
        const contents = parentContent.querySelectorAll('.sub-tab-content');

        subTabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                subTabBtns.forEach(b => b.classList.remove('btn-primary'));
                contents.forEach(cnt => cnt.classList.add('d-none'));
                btn.classList.add('btn-primary');
                const target = parentContent.querySelector('#' + btn.getAttribute('data-subtab'));
                if (target) target.classList.remove('d-none');
            });
        });

        // فعال‌سازی پیش‌فرض اولین زیرتب
        if (subTabBtns.length) {
            const first = subTabBtns[0];
            first.classList.add('btn-primary');
            const firstContent = parentContent.querySelector('#' + first.getAttribute('data-subtab'));
            if (firstContent) firstContent.classList.remove('d-none');
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.sub-tab').forEach(stab => {
        stab.addEventListener('click', () => {
            const parent = stab.closest('.tab-content');

            // حذف active از همه دکمه‌ها
            parent.querySelectorAll('.sub-tab').forEach(btn => btn.classList.remove('active'));

            // مخفی کردن همه محتواها
            parent.querySelectorAll('.sub-tab-content').forEach(cnt => cnt.classList.add('d-none'));

            // فعال کردن دکمه و نمایش محتوای مربوطه
            stab.classList.add('active');
            document.getElementById(stab.getAttribute('data-subtab')).classList.remove('d-none');
        });
    });
});
</script>

            <!-- کارت ارسال کد دعوت -->
            {{-- <div class="card mt-4">
                <div class="card-header bg-success text-white">
                    ارسال کد دعوت
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.send.invitation') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="invite_email" class="form-label">ایمیل دعوت:</label>
                            <input type="email" name="invite_email" id="invite_email" class="form-control @error('invite_email') is-invalid @enderror" required>
                            @error('invite_email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">ارسال دعوت</button>
                    </form>
                </div>
            </div> --}}
        </div>
    </div>
</div>
@endsection