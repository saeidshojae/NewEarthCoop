@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    @if(isset($_GET['home']))
        <h2 class="text-center mb-4 fw-bold">مدیریت صفحه خانه</h2>
    @else
        <h2 class="text-center mb-4 fw-bold">مدیریت صفحه خوش آمد</h2>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @php
            $setting = \App\Models\Setting::find(1);

    @endphp
<h2 class="text-center">ثبت اسلایدر جدید</h2>
<form action="{{ route('admin.slider.store') }}" method="POST" class="mb-5" enctype='multipart/form-data'>
            @csrf
            <div class="mb-3">
                <label class="form-label">تصویر اسلایدر</label>
                <input type='file' name='src' class="form-control" required> 
                 @if(isset($_GET['home']))
                    <input type='hidden' name='position' value='1' class="form-control" required> 
                 @else
                    <input type='hidden' name='position' value='0' class="form-control" required> 
                @endif
            </div>
            <button type="submit" class="btn btn-success">ذخیره اسلایدر</button>
        </form>


        <form action="{{ route('admin.update.welcome') }}" method="POST" class="mb-5">
            @csrf
            @method('PUT')
            <div class="mb-3">
                @if(isset($_GET['home']))
                
                <label class="form-label">تیتر صفحه هوم</label>
                <input type='text' name='home_titre' class="form-control" value='{{ old("home_titre", $setting->home_titre) }}' required> 
        
                <label class="form-label mt-3">متن صفحه هوم</label>
                <textarea class="form-control" id="editor" name="home_content" rows="10" required>{{ old('home_content', $setting->home_content) }}</textarea>
                
                @else
                
                <label class="form-label">تیتر صفحه خوش آمدید</label>
                <input type='text' name='welcome_titre' class="form-control" value='{{ old("welcome_titre", $setting->welcome_titre) }}' required> 
    
                <label class="form-label mt-3">متن صفحه خوش آمدید</label>
                <textarea class="form-control" id="editor" name="welcome_content" rows="10" required>{{ old('welcome_content', $setting->welcome_content) }}</textarea>
                
                @endif
            </div>
            <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
        </form>

<hr>



    <h4 class="fw-semibold text-center mb-3">لیست اسلایدر</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>تصویر</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $sliderCode = 0;
                    if(isset($_GET['home'])){
                        $sliderCode = 1;
                    }
                @endphp
                @foreach(\App\Models\Slider::where('position', $sliderCode)->get() as $slider)
                    <tr>
                        <td class="text-end">
                            <img src='{{ asset('images/sliders/' . $slider->src) }}' width='150'>
                        </td>
                        <td>
                            <a href="{{ route('admin.slider.delete', $slider) }}" class="btn btn-danger btn-sm">حذف</a>
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
    CKEDITOR.replace('editor', {
        filebrowserUploadUrl: "{{ route('admin.pages.upload') }}?_token={{ csrf_token() }}",
        filebrowserUploadMethod: 'form',
        language: 'fa',
        height: 100,
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
