@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    
    
<div class="container py-4" dir="rtl">
    <h2 class="text-center mb-4 fw-bold">مدیریت گروه‌ها</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    
    <style>
        li{
            list-style-type: none;
        }
    </style>
    
@php
$currentUser = isset($_GET['user']) ? $_GET['user'] : null;
$levels = [
    'alley' => 'کوچه',
    'street' => 'خیابان',
    'neighborhood' => 'محله',
    'region' => 'منطقه',
    'city' => 'شهر',
    'section' => 'بخش',
    'county' => 'شهرستان',
    'province' => 'استان',
    'country' => 'کشور'
];
@endphp

<ul style='display: flex; justify-content: space-evenly;'>
@foreach($levels as $levelKey => $levelName)
    <li>
        <a 
            href='?level={{ $levelKey }}{{ $currentUser ? "&user=$currentUser" : "" }}' 
            class='btn' 
            style='{{ (isset($_GET['level']) && $_GET['level'] == $levelKey) ? "background-color: orange;" : "" }}'
        >{{ $levelName }}</a>
    </li>
@endforeach
</ul>

@php
$sorts = [
    'experience' => 'علمی/تجربی',
    'job'        => 'صنفی/شغلی',
    'age'        => 'سنی',
    'gender'     => 'جنسیتی',
    'total'      => 'عمومی'
];
@endphp

<ul style='list-style-type: none; display: flex; justify-content:space-between'>
@foreach($sorts as $sortKey => $sortName)
    <li>
        <a 
            href='?sort={{ $sortKey }}{{ $currentUser ? "&user=$currentUser" : "" }}' 
            class='btn'
            style='{{ (isset($_GET['sort']) && $_GET['sort'] == $sortKey) ? "background-color: orange;" : "" }}'
        >{{ $sortName }}</a>
    </li>
@endforeach
</ul>


    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center align-middle" id='myTable'>
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>نام</th>
                    <th>توضیحات</th>
                    <th>سطح</th>
                    <th>تخصص</th>
                    <th>صنف</th>
                    <th>رده سنی</th>
                    <th>جنسیت</th>
                    @if(isset($_GET['user']))
                        <th>نقش</th>
                    @endif
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $key => $group)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $group->name }}</td>
                        <td>{{ $group->description ?? '-' }}</td>
                        <td>
                            @if($group->location_level == 'continent')
                                قاره
                            @elseif($group->location_level == 'country')
                            کشور    
                            @elseif($group->location_level == 'province')
                            استان
                            @elseif($group->location_level == 'county')
                            شهرستان
                            @elseif($group->location_level == 'section')
                            بخش
                            @elseif($group->location_level == 'city')
                            شهر
                            @elseif($group->location_level == 'region')
                            منطقه
                            @elseif($group->location_level == 'neighborhood')
                            محله
                            @elseif($group->location_level == 'street')
                            خیابان
                            @elseif($group->location_level == 'alley')
                            کوچه
                            @elseif($group->location_level == 'global')
                            جهانی
                            @endif
                        </td>
                        <td>{{ $group->experience_id ? $group->experience->name : '-' }}</td>
                                                <td>{{ $group->specialty_id ? $group->specialty->name : '-' }}</td>

                        <td>{{ $group->age_group_id ? $group->ageGroup->title : '-' }}</td>
                        <td>{{ $group->gender ? $group->gender() : '-' }}</td>

                        @if(isset($_GET['user']))
                        <td>
                            @if(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first() == null)
                             -
                            @else
                                @if(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first()->role == 0)
                                    ناظر
                                @elseif(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first()->role == 1)
                                    فعال
                                @elseif(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first()->role == 2)
                                    بازرس
                                @elseif(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first()->role == 3)
                                    مدیر
                                @elseif(\App\Models\GroupUser::where('user_id', $_GET['user'])->where('group_id', $group->id)->first()->role == 4)
                                    مهمان
                                @endif
                            @endif
                            </td>
                        @endif
                        <td> 
                            <a href="{{ route('admin.groups.manage', $group) }}" class="btn btn-sm btn-success">مدیریت</a>
                            <a href="{{ route('admin.group.delete', $group) }}" class="btn btn-sm btn-success">حذف</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

    <script>
        new DataTable('#myTable', {
            language: {
            "decimal":        "",
            "emptyTable":     "هیچ داده‌ای در جدول وجود ندارد",
            "info":           "نمایش _START_ تا _END_ از _TOTAL_ رکورد",
            "infoEmpty":      "نمایش 0 تا 0 از 0 رکورد",
            "infoFiltered":   "(فیلتر شده از مجموع _MAX_ رکورد)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "نمایش _MENU_ رکورد",
            "loadingRecords": "در حال بارگذاری...",
            "processing":     "در حال پردازش...",
            "search":         "جستجو:",
            "zeroRecords":    "رکوردی یافت نشد",
            "paginate": {
                "first":      "اولین",
                "last":       "آخرین",
                "next":       "بعدی",
                "previous":   "قبلی"
            },
            "aria": {
                "sortAscending":  ": فعال‌سازی مرتب‌سازی صعودی",
                "sortDescending": ": فعال‌سازی مرتب‌سازی نزولی"
            }
        }
        });
    </script>
@endsection
