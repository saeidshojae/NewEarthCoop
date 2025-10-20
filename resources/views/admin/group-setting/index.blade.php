@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h2 class="text-center mb-4 fw-bold">تنظیمات انتخابات</h2>

    <div class="table-responsive">
        <ul style='list-style-type: none; display: flex; justify-content:space-between'>
            <li><a href='?sort=experience' style='{{ (isset($_GET['sort']) AND $_GET['sort'] == 'experience') ? 'background-color: orange' : '' }}' class='btn'>علمی/تجربی</a></li>
            <li><a href='?sort=job' style='{{ (isset($_GET['sort']) AND $_GET['sort'] == 'job') ? 'background-color: orange' : '' }}' class='btn'>صنفی/شغلی</a></li>
            <li><a href='?sort=age' style='{{ (isset($_GET['sort']) AND $_GET['sort'] == 'age') ? 'background-color: orange' : '' }}' class='btn'>سنی</a></li>
            <li><a href='?sort=gender' style='{{ (isset($_GET['sort']) AND $_GET['sort'] == 'gender') ? 'background-color: orange' : '' }}' class='btn'>جنسیتی</a></li>
            <li><a href='?sort=total' style='{{ (isset($_GET['sort']) AND $_GET['sort'] == 'total') ? 'background-color: orange' : '' }}' class='btn'>عمومی</a></li>
        </ul>
        <table class="table table-bordered align-middle text-center table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">سطح گروه</th>
                    <th scope="col">تعداد بازرسان</th>
                    <th scope="col">تعداد مدیران</th>
                    <th scope="col">تعداد برای شروع انتخابات</th>
                    <th scope="col">زمان انتخابات</th>
                    <th scope="col">زمان ثانویه انتخابات</th>
                    <th scope="col">عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupSettings as $key => $setting)
                    <tr>
                        <form action="{{ route('admin.group.setting.update', $setting) }}" method="POST" class="d-flex align-items-center justify-content-center gap-2">
                            @csrf
                            @method('PUT')

                            <td>{{ $key + 1 }}</td>

                            <td class="fw-semibold">{{ $setting->name() }}</td>

                            <td>
                                <input type="number" name="inspector_count" class="form-control text-center" value="{{ old('inspector_count', $setting->inspector_count) }}">
                            </td>

                            <td>
                                <input type="number" name="manager_count" class="form-control text-center" value="{{ old('manager_count', $setting->manager_count) }}">
                            </td>

                            <td>
                                <input type="number" name="max_for_election" class="form-control text-center" value="{{ old('max_for_election', $setting->max_for_election) }}">
                            </td>


                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <input type="number" name="election_time" class="form-control text-center" value="{{ old('election_time', $setting->election_time) }}">
                                    <span class="fw-semibold">روز</span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-1">
                                    <input type="number" name="second_election_time" class="form-control text-center" value="{{ old('second_election_time', $setting->second_election_time) }}">
                                    <span class="fw-semibold">روز</span>
                                </div>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-sm btn-success">به‌روزرسانی</button>
                                <a class="btn btn-sm btn-warning" href='{{ route('admin.group.setting.edit', $setting->id) }}'>{{ $setting->election_status == 0 ? 'فعال کردن انتخابات' : 'غیرفعال کردن انتخابات' }}</a>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
