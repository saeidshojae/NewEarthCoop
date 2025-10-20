@php
    // عضویت کاربر پست‌دهنده در گروه (ممکنه وجود نداشته باشه)
    $groupUserPost = \App\Models\GroupUser::where('group_id', $item->group_id)
        ->where('user_id', $item->user_id)
        ->first();

    // رنگ ثابت بر اساس شناسه‌ی کاربر (ایمن)
    $ownerId = $item->user_id ?? optional($item->user)->id ?? 0;
    $hue = fmod($ownerId * 137.508, 360); // Golden angle
    $saturation = 70;
    $lightness = 85;
    $backgroundColor = "hsl({$hue}, {$saturation}%, {$lightness}%)";
    $textColor = "hsl({$hue}, {$saturation}%, 30%)";

    // حروف اول نام برای آواتار
    $fn = trim($item->user->first_name ?? '');
    $ln = trim($item->user->last_name ?? '');
    $inits = (mb_substr($fn,0,1) ?: '؟') . ' ' . (mb_substr($ln,0,1) ?: '؟');
@endphp

<div style="display:flex; @if(($item->user_id ?? null) != auth()->id()) justify-content:start; padding:0 8px; @else justify-content:end; @endif     margin-right: -1rem;">

    @if(($item->user_id ?? null) != auth()->id())
        @if($item->user)
            <a href='{{ route("profile.member.show", $item->user) }}' style="margin-left:.5rem; height: 2rem">
                @if(empty($item->user->avatar))
                    <div class="group-avatar" style="width:2rem;height:2rem;font-size:.6rem;margin:0;background-color:{{ $backgroundColor }};color:{{ $textColor }};">
                        <span>{{ $inits }}</span>
                    </div>
                @else
                    <img alt="تصویر پروفایل" class="rounded-circle" width="32" height="32" src="{{ asset('/images/users/avatars/' . $item->user->avatar) }}">
                @endif
            </a>
        @else
            <div class="group-avatar" style="width:2rem;height:2rem;font-size:.6rem;margin-left:.5rem;background:#eee;color:#666;">
                <span>؟ ؟</span>
            </div>
        @endif
    @endif

    <div class="post-card @if(optional($groupUserPost)->role === 3) manager-post @endif" id="blog-{{ $item->id }}" style="margin-top:0;padding-top:.5rem">
        @if(($item->user_id ?? null) != auth()->id() && $item->user)
            <div class="message-sender" style="margin-left:.4rem;margin-bottom:.5rem">
                <a href='{{ route("profile.member.show", $item->user) }}' style="color:blue;font-weight:900">
                    {{ $item->user->first_name }} {{ $item->user->last_name }} 
                </a>
            </div>
        @endif
        
        @if(!$item->user)
                
        <div class="message-sender" style="margin-left:.4rem;margin-bottom:.5rem">
                <a href='#' style="color:blue;font-weight:900">
                    حساب حذف شده
                </a>
            </div>
        
        @endif


        @if(!empty($item->img))
            @php
                $type = $item->file_type ? explode('/', $item->file_type)[0] : '';
            @endphp

            @if($type === 'image')
                <img src="{{ asset('images/blogs/' . $item->img) }}" @if(($item->user_id ?? null) == auth()->id()) style="margin-top:2rem" @endif>
            @elseif($type === 'video')
                <video controls style="width:100%">
                    <source src="{{ asset('images/blogs/' . $item->img) }}" type="{{ $item->file_type }}">
                </video>
            @elseif($type === 'audio')
                <audio controls>
                    <source src="{{ asset('images/blogs/' . $item->img) }}" type="{{ $item->file_type }}">
                </audio>
            @else
                <a href="{{ asset('images/blogs/' . $item->img) }}" class="btn btn-primary" target="_blank" style="width:100%;margin-bottom:1rem;direction:rtl; @if(($item->user_id ?? null) == auth()->id()) margin-top:2rem @endif">
                    دانلود فایل {{ $item->file_type ? (explode('/', $item->file_type)[1] ?? 'فایل') : 'فایل' }}
                </a>
            @endif
        @endif

        <h3 style="text-align:center">{{ $item->title }}</h3>
<p style="text-align:right;font-weight:900">
  دسته‌بندی:
  @if($item->category)
<a href="javascript:void(0)"
   class="open-category-blogs"
   data-url="{{ url('/categories/'.$item->category->id.'/blogs') }}"
   data-group-id="{{ $item->group_id }}"
   style="color:#0d6efd; text-decoration: underline;">
  {{ $item->category->name }}
</a>

  @else
    —
  @endif
  
  
</p>        <p style="text-align:right">{!! $item->content !!}</p>
        
        <a href="{{ route('groups.comment', $item) }}" class="comments-link" style="color:blue">
            <i class="fa fa-comment"></i>
            نظر دهید
        </a>
        <p style="margin: 0; font-size: .5rem;
    margin-top: 0.5rem;">تعداد نظر: {{ $item->comments->count() }}</p>
    
        <div class="d-flex justify-content-between align-items-center">
            <span class="time" style="margin:0">{{ verta($item->created_at)->format('Y/m/d H:i') }}</span>
            

            <div class="reaction-buttons" data-post-id="{{ $item->id }}" style="display:flex;flex-direction:row-reverse;">
                <button class="btn-like" style="border:none;margin-bottom:0">
                    <i class="fas fa-thumbs-up"></i>
                    <span class="like-count">{{ $item->reactions()->where('type','1')->count() }}</span>
                </button>
                <button class="btn-dislike" style="border:none;margin-bottom:0">
                    <i class="fas fa-thumbs-down"></i>
                    <span class="dislike-count">{{ $item->reactions()->where('type','0')->count() }}</span>
                </button>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <div class="dropdown" style='@if(($item->user_id ?? null) == auth()->id()) right:.2rem; @else left:.2rem; @endif'>
                <button class="btn-sm btn-light" type="button" id="dropdownMenuButton-{{ $item->id }}" data-bs-toggle="dropdown" aria-expanded="false" style="display:flex;border:none">
                    <i class="fas fa-ellipsis-v" style="color:#000;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton-{{ $item->id }}">
                                            <li onclick="replyToMessage('post-{{ $item->id }}', '', 'مقاله: {{ $item->title }}')">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-reply me-2"></i> پاسخ
                            </a>
                        </li>
                    @if(($item->user_id ?? null) == auth()->id())
                    
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editPostModal-{{ $item->id }}">
                                <i class="fas fa-edit me-2"></i> ویرایش
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="deletePost({{ $item->id }})">
                                <i class="fas fa-trash me-2"></i> حذف
                            </a>
                        </li>
                    @else
                        
                        <li>
                            <a class="dropdown-item text-danger" href="#" onclick="reportMessage({{ $item->id }})" data-bs-toggle="modal" >
                                <i class="fas fa-flag me-2"></i> گزارش
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        @if(($item->user_id ?? null) == auth()->id())
            <!-- Edit Post Modal -->
            <div class="modal fade" id="editPostModal-{{ $item->id }}" tabindex="-1" aria-labelledby="editPostModalLabel-{{ $item->id }}" aria-hidden="true" style="z-index:99999">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form onsubmit="submitPostEdit(event, {{ $item->id }})">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPostModalLabel-{{ $item->id }}">ویرایش پست</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">عنوان پست</label>
                                    <input type="text" class="form-control" id="edit-post-title-{{ $item->id }}" value="{{ $item->title }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">متن پست</label>
                                    <textarea class="form-control" rows="4" id="edit-post-content-{{ $item->id }}">{{ $item->content }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">دسته‌بندی</label>
                                    <select class="form-control" id="edit-post-category-{{ $item->id }}">
                                        <option value="">انتخاب دسته‌بندی</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @selected($item->category_id == $category->id)>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.dropdown-menu .dropdown-item:hover,
.dropdown-menu .dropdown-item:focus { background-color: transparent !important; color: inherit !important; }
.post-actions { display:flex; gap:.5rem; margin-top:.5rem; }
.post-edit-form { margin-top:1rem; padding:1rem; background:#f8f9fa; border-radius:8px; }
.edit-actions { display:flex; gap:.5rem; margin-top:.5rem; }
.post-card { border:1px solid #dee2e6; border-radius:8px; padding:1rem; margin-bottom:1rem; background:#fff; position:relative; }
.manager-post { border:2px solid #ffc107; box-shadow:0 0 10px rgba(255,193,7,.2); }
.dropdown { position:absolute; left:.5rem; top:.5rem; }
</style>

<script>
function submitPostEdit(event, postId) {
    event.preventDefault();
    const title = document.getElementById(`edit-post-title-${postId}`).value;
    const content = document.getElementById(`edit-post-content-${postId}`).value;
    const categoryId = document.getElementById(`edit-post-category-${postId}`).value;
    console.log("ویرایش پست:", { postId, title, content, categoryId });
    const modalEl = document.getElementById(`editPostModal-${postId}`);
    const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
    modal.hide();
}

function deletePost(id) {
    if (confirm("آیا مطمئن هستید که می‌خواهید این پست را حذف کنید؟")) {
        // توجه: آدرس حذف را با روت درست جایگزین کن
        window.location.href = `/groups/post/delete/${id}`;
    }
}
</script>
