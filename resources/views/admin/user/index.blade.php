@extends('layouts.app')


@section('content')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

    
<div class="container py-4" dir="rtl">
    <a href='{{ route('admin.users.create') }}' class='btn btn-warning'>ایجاد کاربر جدید</a><br><br>
    <h2 class="text-center mb-4 fw-bold">مدیریت کاربران</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover text-center align-middle" id='myTable'>
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>ایمیل</th>
                    <th>نام</th>
                    <th>تاریخ تولد</th>
                    <th>شماره تماس</th>
                    <th>جنسیت</th>
                    <th>کد ملی</th>
                    <th>صنف</th>
                    <th>تخصص</th>
                    <th>کشور</th>
                    <th>استان</th>
                    <th>شهرستان</th>
                    <th>بخش</th>
                    <th>شهر/روستا</th>
                    <th>منطقه/دهستان</th>
                    <th>محله</th>
                    <th>خیابان</th>
                    <th>کوچه</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $key => $user)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->first_name . ' ' . $user->last_name }}</td>
                        <td>{{ $user->birth_date }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ $user->gender == 'male' ? 'مرد' : 'زن' }}</td>
                        <td>{{ $user->national_id }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach ($user->occupationalFields as $item)
                                    <li>{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach ($user->experienceFields as $item)
                                    <li>{{ $item->name }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $user->address?->country->name ?? '-' }}</td>
                        <td>{{ $user->address?->province->name ?? '-' }}</td>
                        <td>{{ $user->address?->county->name ?? '-' }}</td>
                        <td>{{ $user->address?->section->name ?? '-' }}</td>
                        <td>
                            {{ $user->address?->city?->name ?? $user->address?->rural?->name ?? '-' }}
                        </td>
                        <td>
                            {{ $user->address?->region?->name ?? $user->address?->village?->name ?? '-' }}
                        </td>
                        <td>{{ $user->address?->neighborhood?->name ?? '-' }}</td>
                        <td>{{ $user->address?->street?->name ?? '-' }}</td>
                        <td>{{ $user->address?->alley?->name ?? '-' }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.group.index', ['user' => $user->id]) }}" class="btn btn-warning">گروه‌ها</a>
                                <a href="{{ route('admin.user.destroy', $user->id) }}" class="btn btn-danger">حذف</a>
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-danger">ویرایش</a>
                            </div>
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
