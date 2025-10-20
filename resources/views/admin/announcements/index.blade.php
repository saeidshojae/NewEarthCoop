@extends('layouts.app')

@section('content')
<style>
          
      #cke_1_bottom{
                    display: none;

      }
            .cke_notification{
                              display: none !important; 

      }
</style>
<div class="container py-4" dir="rtl">
    <h2 class="text-center mb-4 fw-bold">مدیریت اطلاعیه ها</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.announcement.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="">تیتر اطلاعیه</label>
            <input type="text" name="title" id="" placeholder="تیتر را وارد کنید" class="form-control" required><br>
            <label for="content" class="form-label">متن اطلاعیه</label>
            <textarea name="content" id="content" class="form-control text-end" rows="4" required></textarea><br>
            <label for="">گروه</label>
            <select name="group_level" class="form-control" id="">
                <option value="global">جهانی</option>
                <option value="continent">قاره</option>
                <option value="country">کشور</option>
                <option value="province">استان</option>
                <option value="county">شهرستان</option>
                <option value="section">بخش</option>
                <option value="city">شهر/روستا</option>
                <option value="region">منطقه/دهستان</option>
                <option value="neighborhood">محله</option>
                <option value="street">خیابان</option>
                <option value="alley">کوچه</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">ایجاد اطلاعیه</button>
    </form>

    <h4 class="fw-semibold text-center mb-3">لیست اطلاعیه ها</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>تیتر اطلاعیه</th>
                    <th>متن اطلاعیه</th>
                    <th>سطح گروه</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($announcements as $announcement)
                    <tr>
                        <td class="text-end">{{ $announcement->title }}</td>
                        <td class="text-end">{!! $announcement->content !!}</td>
                        <td class="text-end">{{ $announcement->group_level }}</td>
                        <td>
                            <a href="{{ route('admin.announcement.delete', $announcement) }}" class="btn btn-danger btn-sm">حذف</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content', {
        filebrowserUploadUrl: "{{ route('admin.pages.upload') }}?_token={{ csrf_token() }}",
        filebrowserUploadMethod: 'form',
        language: 'fa',
        height: 400,
        extraPlugins: 'uploadimage',
        removeButtons: '',
        toolbarGroups: [
            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align' ] },
            { name: 'styles' },
            { name: 'colors' },
            { name: 'insert' },
            { name: 'tools' },
            { name: 'editing' },
            { name: 'document', groups: [ 'mode', 'document' ] },
            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
            { name: 'links' }
        ]
    });
    


</script> 
@endpush
@endsection
