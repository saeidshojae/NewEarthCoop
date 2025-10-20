@extends('layouts.app')

@section('content')
<div class="container" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>صفحات</h4>
                    <div>
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">ایجاد صفحه جدید</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>نامک</th>
                                    <th>وضعیت</th>
                                    <th>تاریخ ایجاد</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                    <tr>
                                        <td>{{ $page->title }}</td>
                                        <td>{{ $page->slug }}</td>
                                        <td>
                                            <span class="badge {{ $page->is_published ? 'bg-success' : 'bg-warning' }}">
                                                {{ $page->is_published ? 'منتشر شده' : 'پیش‌نویس' }}
                                            </span>
                                        </td>
                                        <td>{{ $page->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-sm btn-primary">ویرایش</a>
                                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('آیا مطمئن هستید؟')">حذف</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 