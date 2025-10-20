<div id="postFormBox" style="display: none; direction: rtl;">
    <form id="postForm" action="{{ route('groups.blog.store', $group) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="text" name="title" placeholder="عنوان پست" class="form-control mb-2">
        <input type="file" name="img" class="form-control mb-2"  >

        <textarea name="content" placeholder="متن پست..." class="form-control mb-2" id='post_editor' required></textarea>
        <select class="form-control mb-2" name="category_id">
            <option value="">انتخاب دسته‌بندی</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary w-100">ارسال</button>
        <button type="button" class="btn btn-secondary w-100 mt-2" onclick="cancelPostForm()">لغو</button>
    </form>
            
    @error('img')
    <span class="title">{{ $message }}</span>
@enderror

        
@error('img')
<span class="error">{{ $message }}</span>
@enderror

        
@error('category_id')
<span class="error">{{ $message }}</span>
@enderror
@error('content')
<span class="error">{{ $message }}</span>
@enderror

</div>
