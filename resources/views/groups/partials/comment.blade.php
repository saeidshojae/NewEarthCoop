<style>
  .chat-body {
    display: flex !important;
    align-items: center;
  }

  .message-bubble {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
    gap: 10px;
  }
  
  .other{
      position: relative;
  }
    
  .you{
      position: relative;
      padding-left: 1.5rem !important;
  }
  
  .message-bubble button{
      right: 0 !important;
      top: 0 !important;
            left: 0 !important;
  }

  /* دکمه سه‌نقطه */
  .options-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 18px;
    color: #555;
  }
  
  .options-btn i{
      position: absolute !important;
    left: .5rem !important;
    top: .5rem !important;
  }

  /* منوی بازشونده */
  .options-menu {
    display: none;
    position: absolute;
    right: 25px; /* کنار دکمه سه‌نقطه */
    top: 50%;
    transform: translateY(-50%);
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 6px 18px rgba(0,0,0,.12);
    z-index: 100;
    min-width: 160px;
    direction: rtl; /* فارسی */
  }

  .options-menu ul {
    list-style: none;
    padding: 6px 0;
    margin: 0;
  }

  .options-menu li {
    padding: 10px 12px;
    cursor: pointer;
    font-size: 14px;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .options-menu li:hover { background-color: #f7f7f7; }
  .options-menu li.is-static { cursor: default; }
  .options-menu li.is-static:hover { background: transparent; }

  .count-badge {
    margin-right: auto; /* اعداد برن سمت چپ */
    font-size: 12px;
    opacity: .8;
  }
  
  
</style>

<div class="message-bubble {{ $item->user_id == auth()->id() ? 'you' : 'other' }}"
     id="msg-{{ $item->id }}"
     data-update-url="{{ route('comments.update', $item->id) }}"
     data-delete-url="{{ route('comments.destroy', $item->id) }}"
     data-message-text="{{ trim(strip_tags($item->message)) }}"
>
  <div class="text-react" style="flex: 1;">
    @if ($item->user_id != auth()->id())
      <b><a style='color: blue' href='{{ route('profile.member.show', $item->user->id) }}'>{{ $item->user->first_name . ' ' . $item->user->last_name }}</a></b>
    @endif

    @if ($item->parent)
      <a class="replay-box" href="#msg-{{ $item->parent_id }}">
        <b>{{ $item->parent->user->first_name . ' ' . $item->parent->user->last_name }}</b>
        @php
    $text = $item->parent->message;

    // regex برای پیدا کردن لینک
    $textWithLinks = preg_replace_callback(
        '/(https?:\/\/[^\s]+)/',
        function($matches) {
            $url = e($matches[0]); // لینک رو امن میکنیم
            return '<a href="'.$url.'" target="_blank" style="color: blue; text-decoration: underline;">'.$url.'</a>';
        },
        $text
    );
@endphp

<p>{!! $textWithLinks !!}</p>

      </a>
    @endif

    @php
    $text = $item->message;

    // regex برای پیدا کردن لینک
    $textWithLinks = preg_replace_callback(
        '/(https?:\/\/[^\s]+)/',
        function($matches) {
            $url = e($matches[0]); // لینک رو امن میکنیم
            return '<a href="'.$url.'" target="_blank" style="color: blue; text-decoration: underline;">'.$url.'</a>';
        },
        $text
    );
@endphp

<p>{!! $textWithLinks !!}</p>

       <button class="options-btn" onclick="openGlobalMenu(event, {{ $item->id }})" aria-label="گزینه‌ها">
  <i class="fa-solid fa-ellipsis-vertical"></i>
</button>
  </div>

  <div @if($item->user_id != auth()->user()->id) style="    top: 0;
    right: 0; position: absolute;" @else style="position: absolute;     top: 0;
    left: -1rem;" @endif>



    <div class="options-menu" id="options-menu-{{ $item->id }}"
         @if($item->user_id != auth()->user()->id) style="left: 0;" @else style="right: 0; top: 0;" @endif>
      <ul>
        <li onclick="replyToSelectedComment({{ $item->id }})">
          <i class="fa-solid fa-reply"></i> ریپلای
        </li>

        <li onclick="reactToComment('like', {{ $item->id }})">
          <i class="fas fa-thumbs-up"></i> لایک
          <span class="count-badge" id="like-count-{{ $item->id }}">{{ $item->likes->count() }}</span>
        </li>

        <li onclick="reactToComment('dislike', {{ $item->id }})">
          <i class="fas fa-thumbs-down"></i> دیسلایک
          <span class="count-badge" id="dislike-count-{{ $item->id }}">{{ $item->dislikes->count() }}</span>
        </li>


        <li class="is-static" title="زمان ارسال">
          <i class="fa-regular fa-clock"></i>
          <span>تاریخ ارسال: {{ verta($item->created_at)->format('Y-m-d H:i') }}</span>
        </li>

        @if ($item->user_id == auth()->id())
          <li onclick="editComment({{ $item->id }})">
            <i class="fa-solid fa-pen-to-square"></i> ویرایش
          </li>
          <li onclick="deleteComment({{ $item->id }})" style="color:#d33;">
            <i class="fa-solid fa-trash"></i> حذف
          </li>
        @endif
      </ul>
    </div>
  </div>
</div>

<script>
  function toggleOptionsMenu(id) {
    document.querySelectorAll('.options-menu').forEach(menu => {
      if (menu.id !== `options-menu-${id}`) menu.style.display = 'none';
    });
    const menu = document.getElementById(`options-menu-${id}`);
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
  }

  document.addEventListener('click', function (event) {
    if (!event.target.closest('.options-btn') && !event.target.closest('.options-menu')) {
      document.querySelectorAll('.options-menu').forEach(menu => menu.style.display = 'none');
    }
  });

  function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
  }

  async function editComment(id) {
    const bubble = document.getElementById(`msg-${id}`);
    const updateUrl = bubble.getAttribute('data-update-url');
    const currentText = bubble.getAttribute('data-message-text') || '';

    const newText = prompt('متن جدید نظر را وارد کنید:', currentText);
    if (newText === null) return; // کاربر لغو کرد
    if (newText.trim() === '') { alert('متن نمی‌تواند خالی باشد.'); return; }

    try {
      const res = await fetch(updateUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': getCsrfToken(),
          'Accept': 'application/json',
          'X-HTTP-Method-Override': 'PUT',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({ message: newText })
      });

      if (!res.ok) throw new Error('خطا در ویرایش نظر');
      // در صورت نیاز، پاسخ JSON سرور را بخوان
      // const data = await res.json();

      // به‌روزرسانی UI
      const content = bubble.querySelector('.comment-name');
      if (content) content.textContent = newText; // اگر متن شما HTML است، به‌جای textContent از innerHTML استفاده کن (با احتیاط امنیتی)
      bubble.setAttribute('data-message-text', newText);
      document.getElementById(`options-menu-${id}`).style.display = 'none';
    } catch (e) {
      console.error(e);
      alert('ویرایش انجام نشد.');
    }
  }

  async function deleteComment(id) {
    if (!confirm('آیا از حذف این نظر مطمئن هستید؟')) return; 

    const bubble = document.getElementById(`msg-${id}`);
    const deleteUrl = bubble.getAttribute('data-delete-url');

    try {
      const res = await fetch(deleteUrl, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': getCsrfToken(),
          'Accept': 'application/json',
          'X-HTTP-Method-Override': 'DELETE'
        }
      });

      if (!res.ok) throw new Error('خطا در حذف نظر');

      // حذف از DOM
      bubble.remove();
    } catch (e) {
      console.error(e);
      alert('حذف انجام نشد.');
    }
  }
</script>
