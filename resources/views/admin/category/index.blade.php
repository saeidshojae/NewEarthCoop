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
    <h2 class="text-center mb-4 fw-bold">مدیریت دسته بندی ها</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif
    
    @if(!isset($_GET['edit']))
    
    <form action="{{ route('admin.category.store') }}" method="POST" class="mb-5">
        @csrf
        <div class="mb-3">
            <label for="">نام دسته</label>
            <input type="text" name="name" id="" placeholder="" class="form-control" required><br>
            <label for="">برای کدام گروه ها</label>
            <div style='    display: flex;
    flex-wrap: wrap;'>
                
                <div style='margin: .2rem .5rem;    background-color: #e9e9e9;
    padding: .5rem 1rem;
    border-radius: .3rem;'>
                                        <input type='checkbox' name='groups[]' id='c-0' value='0'>

                    <label for='c-0'>همه</label>
                </div>
                @foreach(\App\Models\GroupSetting::all() as $setting)
                    <div style='margin: .2rem .5rem;    background-color: #e9e9e9;
    padding: .5rem 1rem;
    border-radius: .3rem;'>
                        <input type='checkbox' name='groups[]' id='c-{{ $setting->id }}' value='{{ $setting->id }}'>
                                                <label for='c-{{ $setting->id }}'>{{  $setting->name() }}</label>

                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-success">ایجاد دسته بندی</button>
    </form>

    @else
    
    <form action="{{ route('admin.category.update', $_GET['edit']) }}" method="POST" class="mb-5">
        @csrf
        @method('PUT')
        
        @php
            $category = \App\Models\Category::find($_GET['edit']);
            $categoryGroup = \App\Models\CategoryGroupSetting::where('category_id', $_GET['edit'])->get()->pluck('group_setting_id')->toArray();
        @endphp
        
        <div class="mb-3">
            <label for="">نام دسته</label>
            <input type="text" name="name" id="" placeholder="" value='{{ $category->name }}' class="form-control" required><br>
            <label for="">برای کدام گروه ها</label>
            <div style='    display: flex;
    flex-wrap: wrap;'>
                
                <div style='margin: .2rem .5rem;    background-color: #e9e9e9;
    padding: .5rem 1rem;
    border-radius: .3rem;'>
                                        <input type='checkbox' name='groups[]' id='c-0' value='0' {{ in_array('0', $categoryGroup) ? 'checked' : '' }}>

                    <label for='c-0'>همه</label>
                </div>
                @foreach(\App\Models\GroupSetting::all() as $setting)
                    <div style='margin: .2rem .5rem;    background-color: #e9e9e9;
    padding: .5rem 1rem;
    border-radius: .3rem;'>
                        <input type='checkbox' name='groups[]' id='c-{{ $setting->id }}' value='{{ $setting->id }}'  {{ in_array($setting->id, $categoryGroup) ? 'checked' : '' }}>
                                                <label for='c-{{ $setting->id }}'>{{  $setting->name() }}</label>

                    </div>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-success">ایجاد دسته بندی</button>
    </form>


@endif
    <h4 class="fw-semibold text-center mb-3">لیست دسته بندی ها</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>نام دسته بندی</th>
                    <th>گروه</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\Category::all() as $category)
                    <tr>
                        <td class="text-end">{{ $category->name }}</td>
                        <td class="text-end"></td>
                        <td>
                            <a href="{{ route('admin.category.delete', $category) }}" class="btn btn-danger btn-sm">حذف</a>
                            <a href="{{ route('admin.category.index', ['edit' => $category->id]) }}" class="btn btn-danger btn-sm">ویرایش</a>
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
