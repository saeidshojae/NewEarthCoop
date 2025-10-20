@extends('layouts.app')

@section('content')
<div class="container py-4" dir="rtl">
    <h2 class="text-center mb-4 fw-bold">مدیریت اساسنامه‌ها</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @php
        $term = null;
        if (isset($_GET['edit'])) {
            $term = \App\Models\Term::find($_GET['edit']);
        }
    @endphp

    @if($term)
        <form action="{{ route('admin.rule.update', [$term->id]) }}" method="POST" class="mb-5">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">تیتر اساسنامه</label>
                <input type='text' name='title' class="form-control" value='{{ old("title", $term->title) }}' required> 
    
                <label class="form-label mt-3">متن اساسنامه</label>
                <textarea class="form-control" id="editor" name="message" rows="10" required>{{ old('message', $term->message) }}</textarea>
                
                <label class="form-label mt-3">والد</label>
                <select class='form-control' name='parent_id'>
                    <option>اساسنامه اصلی</option>
                    @foreach(\App\Models\Term::where('parent_id', null)->get() as $term)
                        <option value='{{ $term->id }}' {{ old('parent_id') == $term->id ? 'selected' : '' }}>{{ $term->title }}</option>
                    @endforeach 
                </select>
            </div>
            <button type="submit" class="btn btn-success">ذخیره تغییرات</button>
        </form>
    @elseif(isset($_GET['edit']))
        <h2 class="text-danger text-center">کد وارد شده نامعتبر است</h2>
    @else
        <form action="{{ route('admin.rule.store') }}" method="POST" class="mb-5">
            @csrf
            <div class="mb-3">
                <label class="form-label">تیتر اساسنامه</label>
                <input type='text' name='title' class="form-control" placeholder='تیتر اساسنامه را وارد کنید' required> 
    
                <label class="form-label mt-3">متن اساسنامه</label>
                <textarea name="message" id="editor" class="form-control text-end" rows="10" required>{{ old('message') }}</textarea>
                
                <label class="form-label mt-3">والد</label>
                <select class='form-control' name='parent_id'>
                    <option value='null'>اساسنامه اصلی</option>
                    @foreach(\App\Models\Term::where('parent_id', null)->get() as $term)
                        <option value='{{ $term->id }}' {{ old('parent_id') == $term->id ? 'selected' : '' }}>{{ $term->title }}</option>
                    @endforeach 
                </select>
            </div>
            <button type="submit" class="btn btn-success">ایجاد اساسنامه</button>
        </form>
    @endif

    <h4 class="fw-semibold text-center mb-3">لیست اساسنامه‌ها</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>عنوان اساسنامه</th>
                    <th>متن اساسنامه</th>
                    <th>والد</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($terms as $rule)
                    <tr>
                        <td class="text-end">{{ $rule->title }}</td>
                        <td class="text-end">{!! $rule->message !!}</td>
                        <td class="text-end">{{ $rule->parent_id == null ? '-' : \App\Models\Term::find($rule->parent_id)->title }}</td>
                        <td>
                            <a href="{{ route('admin.rule.destroy', $rule) }}" class="btn btn-danger btn-sm">حذف</a>
                            <a href="{{ route('admin.rule.index', ['edit' => $rule->id]) }}" class="btn btn-warning btn-sm">ویرایش</a>
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
