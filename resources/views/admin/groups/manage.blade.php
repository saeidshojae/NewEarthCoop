@extends('layouts.app')

@section('content')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<div class="container py-4" dir="rtl">
    <form action="{{ route('admin.groups.update', $group) }}" method="POST" class="mb-5" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h4 class="text-center mb-4 fw-bold">ویرایش اطلاعات گروه</h4>
        <div class="mb-3">
            <label for="name" class="form-label">نام گروه</label>
            <input type="text" name="name" id="name" class="form-control text-end" required value="{{ old('name', $group->name) }}">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">توضیحات</label>
            <textarea name="description" id="description" class="form-control text-end">{{ old('description', $group->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label for="avatar" class="form-label">آواتار گروه</label>
            <input type="file" name="avatar" id="avatar" class="form-control" accept="image/*">
            @if($group->avatar)
                <div class="mt-2">
                    <img src="{{ asset($group->avatar) }}" alt="آواتار گروه" style="max-width: 100px; max-height: 100px;">
                </div>
            @endif
        </div>
        <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
    </form>

    <h4 class="mb-4 fw-bold text-center">مدیریت اعضای گروه: {{ $group->name }}</h4>
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    
{{-- فرم عملیات دسته‌جمعی به‌صورت مستقل --}}
<form action='{{ route('admin.groups.change-roles', $group) }}' method='post' id="bulkActionForm">
    @csrf
    <div class="d-flex align-items-center gap-2 mb-3">
        <select name="main_role" class="form-select form-select-sm w-auto" style='width: 100% !important'> 
            <option>عملیات دسته جمعی</option>
            <option value="0">ناظر</option>
            <option value="1">فعال</option>
            <option value="2">بازرس</option>
            <option value="3">مدیر</option>
        </select>
        <button type="submit" class="btn btn-primary btn-sm">اعمال عملیات دسته جمعی</button>
    </div>

    {{-- جدول کاربران --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th><input type='checkbox' id='selectAll'></th>
                    <th>نام کاربر</th>
                    <th>نقش فعلی</th>
                    <th>تغییر نقش</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td><input type='checkbox' name='users[]' value='{{ $user->id }}'></td>
                        <td>{{ $user->fullName() }}</td>
                        <td>
                            @switch($user->pivot->role)
                                @case(0) ناظر @break
                                @case(1) فعال @break
                                @case(2) بازرس @break
                                @case(3) مدیر @break
                                @default -
                            @endswitch
                        </td>
                        <td>
                            {{-- فرم مستقل برای هر کاربر --}}
                            <form action="{{ route('admin.groups.updateRole', [$group, $user]) }}" method="POST" class="d-flex align-items-center justify-content-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-select form-select-sm w-auto">
                                    <option value="0" @selected($user->pivot->role == 0)>ناظر</option>
                                    <option value="1" @selected($user->pivot->role == 1)>فعال</option>
                                    <option value="2" @selected($user->pivot->role == 2)>بازرس</option>
                                    <option value="3" @selected($user->pivot->role == 3)>مدیر</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">اعمال</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</form>

<script>
    $(document).ready(function () {
        // انتخاب همه چک‌باکس‌ها
        $('#selectAll').on('change', function () {
            $('input[name="users[]"]').prop('checked', $(this).prop('checked'));
        });

        // کنترل ارسال فرم عملیات دسته‌جمعی
        $('#bulkActionForm').on('submit', function (e) {
            let selectedUsers = $('input[name="users[]"]:checked');
            let selectedRole = $('select[name="role"]').val();

            if (selectedRole === "" || selectedRole === "عملیات دسته جمعی") {
                e.preventDefault();
                alert('لطفاً یک نقش برای اعمال دسته‌جمعی انتخاب کنید.');
                return;
            }

            if (selectedUsers.length === 0) {
                e.preventDefault();
                alert('لطفاً حداقل یک کاربر را انتخاب کنید.');
                return;
            }
        });
    });
</script>
     <h4 class="mb-4 fw-bold text-center">لیست پست های گروه: {{ $group->name }}</h4>


    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>عنوان پست</th>
                    <th>محتوا</th>
                    <th>تصویر</th>
                    <th>دسته بندی</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Blog::where('group_id', $group->id)->get() as $blog)
                    <tr>
                        <form action='{{ url('admin/group-post-update/' . $blog->id) }}' method='POST' enctype="multipart/form-data"> 
                            @method('PUT')
                            @csrf
                            <td><input name='title' class='form-control' value='{{ $blog->title }}'></td>
                            <td><textarea name='content' class='form-control'>{{ $blog->content }}</textarea></td>
                            <td>
                                <img src='{{ asset('images/blogs/' . $blog->img) }}' width='100'>
                                <input type='file' name='img' class='form-control'>
                            </td>
                            @php
                            
                                    $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level)->first();
        
        if($group->specialty_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_job')->first();
        }elseif($group->experience_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_experience')->first();
        }elseif($group->age_group_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_age')->first();
        }elseif($group->gender != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_gender')->first();
        }
        
                                    $categoryGroupSetting = \App\Models\CategoryGroupSetting::where('group_setting_id', $groupSetting->id)->get()->pluck('category_id')->toArray();
        $categories = \App\Models\Category::whereIn('id', $categoryGroupSetting)->get();
                            @endphp
                            <td>
                                <select class="form-control mb-2" name="category_id">
                                    <option value="">انتخاب دسته‌بندی</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-primary btn-sm">ویرایش</button>
                                <a href='{{ route('admin.group.post.delete', $blog->id) }}' class='btn'>حذف</a>
                            </td>
                        </form>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
