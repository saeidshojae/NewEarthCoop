@extends('layouts.app')

@section('head-tag')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<link rel="stylesheet" href="{{ asset('Css/comment.chat.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    #categoryBlogsModal{
            display: flex;
    justify-content: center;
    }
    #chatForm{
            width: 50%;
    margin-left: 25%;
    }
        .post-card{
            width: 100%;
    }
    
    .chat-body{
                    width: 50%;
    margin-left: 25%;
    }
  .chat-body{
        display: flex !important;
        align-items: center;
    }
    .post-card img{
        width: 100% !important;
    }
    
    .post-card{
        direction: rtl;
    }
    .main-section {
    padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
}

</style>
<style>
      .main-section{
              padding: 0 !important;
    margin: 0 !important;
    max-width: 100% !important;
      }
      

      @media screen and (min-width: 768px) {
        .pinned-messages{
          width: calc(100% - 400px) !important;
        }
      }
            @media screen and (max-width: 768px) {
          #chatForm{
            width: 100%;
    margin-left: 0;
    }
        .chat-body{
            width: 100%;
    margin-left: 0;
    }
      }
      

      
      #cke_1_top{
          display: none;
      }
            #cke_2_top{
          display: none;
      }
      #cke_2_bottom{
                    display: none;

      }
      .cke_notification{
                              display: none !important; 

      }
      #cke_post_editor{
        overflow: auto;
        height: 7rem;
        margin-bottom: .5rem;
      }
      
        #cke_message_editor{
        overflow: auto;
        height: 2rem !important;
        width: 100%;
      }
      
      #postFormBox{
          height: auto;
      }
      

  </style>


@endsection

@section('content')
<div class="chat-wrapper">

    <div class="chat-header ">
        <div class="d-flex align-items-center gap-2" style="flex-direction: row-reverse;">
            <div class="group-avatar">
                <span>{{ strtoupper(substr($group->location_level, 0, 1)) }}</span>
            </div>
            <div class="group-info">
              <h4 style="cursor: pointer;" onclick="">{{ $group->name }}</h4>
              <p>{{ $group->userCount() }} عضو</p>
            </div>
        </div>

        <a href="{{ route('groups.chat', $blog->group->id) }}">
          <div class="border-0">
              <i class="fa-solid fa-chevron-left" style="color: #fff"></i>
          </div>
        </a>
    </div>
      

  <div class="chat-body d-flex flex-column" id="chat-box">

    
    <div class="post-card">
          
        @if ($blog->img != null)
        <img src="{{ asset('images/blogs/' . $blog->img) }}" style="width: 100%">
        @endif
      
                <h3 style="text-align:center">{{ $blog->title }}</h3>
<p style="text-align:right;font-weight:900">
  دسته‌بندی:
  @if($blog->category)
<a href="javascript:void(0)"
   class="open-category-blogs"
   data-url="{{ url('/categories/'.$blog->category->id.'/blogs') }}"
   data-group-id="{{ $blog->group_id }}"
   style="color:#0d6efd; text-decoration: underline;">
  {{ $blog->category->name }}
</a>

  @else
    —
  @endif
</p>
        <p style="text-align:right">{!! $blog->content !!}</p>
        
          



        <div class="d-flex justify-content-between align-items-center">
<div>
                        <a href="{{ route('groups.chat', $blog->group_id) }}" class="comments-link" style="color:blue">
            <i class="fa fa-comment"></i>
            بستن نظر
        </a>
        <p style="margin: 0; font-size: .5rem;
    margin-top: 0.5rem;">تعداد نظر: {{ $blog->comments->count() }}</p>

            <span class="time" style="margin:0">{{ verta($blog->created_at)->format('Y/m/d H:i') }}</span>
            
</div>

            <div style=''>
                <div class="reaction-buttons" data-post-id="{{ $blog->id }}" style="display:flex;flex-direction:row-reverse;">
                <button class="btn-like" style="border:none;margin-bottom:0" onclick='sendReaction(1)'>
                    <i class="fas fa-thumbs-up"></i>
                    <span class="like-count">{{ $blog->reactions()->where('type','1')->count() }}</span>
                </button>
                <button class="btn-dislike" style="border:none;margin-bottom:0" onclick='sendReaction(0)'>
                    <i class="fas fa-thumbs-down"></i>
                    <span class="dislike-count">{{ $blog->reactions()->where('type','0')->count() }}</span>
                </button>
            </div>
            <p></p>
            @if($blog->user)
            <p style="font-size: .5rem; margin: 0;
    margin-top: 0.5rem;">نویسنده: <a style='color :blue' href='{{ route('profile.member.show', $blog->user->id) }}'>{{ $blog->user->fullName() }}</a></p>
            </div>
            @else
                        <p style="font-size: .5rem; margin: 0;
    margin-top: 0.5rem;">نویسنده: <a style='color :blue'>نویسنده حذف شده</a></p>
            </div>
            @endif

        </div>

      </div>

      @foreach($comments as $item)
        @include('groups.partials.comment', compact('item'))
      @endforeach
      

  </div>
  
  <form id="chatForm" class="chat-input d-flex" method="POST" action="{{ route('groups.comment.store') }}" onsubmit="return syncEditor();">
        @csrf
        <input type="hidden" name="blog_id" value="{{ $blog->id }}">
        <input type="hidden" name="parent_id" id="parent_id" value="">
        <input type="text" class="form-control" name="message" placeholder="نظر خود را بنویسید..." required>
        <button type="submit" class="btn btn-primary ms-2">
            <i class="fas fa-paper-plane"></i>
        </button>
    </form>
</div>

<!-- پنل اطلاعات گروه -->
<div id="groupInfoPanel" style="position: fixed; top: 0; right: -100%; width: 100%; max-width: 400px; height: 100vh; background-color: #fff; box-shadow: -2px 0 6px rgba(0,0,0,0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto;">
  <div style="padding: 1rem; direction: rtl;">
    <button onclick="closeGroupInfo()" style="float: left; border: none; background: transparent; font-size: 1.2rem; position: absolute; left: 1rem;">✖</button>
    <div style="text-align: center; margin-top: 2rem;    display: flex;
    flex-direction: column;
    align-items: center;">
      <div class="group-avatar" style="width: 6rem; height: 6rem; font-size: 3rem; margin: 0;">
          <span>{{ strtoupper(substr($group->location_level, 0, 1)) }}</span>
      </div>
      <h4 style="margin-top: 1rem;">{{ $group->name }}</h4>
      <p>{{ $group->userCount() }} عضو</p>
    </div>

    <hr>

    <div class="tabs">
      <div class="tab active" data-tab="members">اعضا</div>
      <div class="tab" data-tab="post">پست</div>
      <div class="tab" data-tab="poll">نظرسنجی</div>
    </div>
    
    <div class="tab-content active" id="public" style="padding: .5rem 1rem;">
      <ul style="list-style: none; padding: 0;">
        @foreach ($group->users as $user)
        @php
          $fColor = rand(1, 255);
          $sColor = rand(1, 255);
          $sColor = rand(1, 255);
        @endphp
          <li style="margin: .5rem 0; display: flex; align-items: center; margin-top: 1rem;">
            <div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .7rem; margin: 0; background-color: rgba({{ $fColor }}, {{ $sColor }}, {{ $sColor }}, .1); color: rgb({{ $fColor }}, {{ $sColor }}, {{ $sColor }});">
                <span>{{ strtoupper(substr($user->email, 0, 1)) }}</span>
            </div>
            <p style="margin: 0; margin-right: .5rem;">{{ $user->first_name }} {{ $user->last_name }}
            @switch($user->pivot->role)
              @case(3) <span>(مدیر)</span> @break
              @case(2) <span>(بازرس)</span> @break
              @case(1) <span>(فعال)</span> @break
              @default <span>(ناظر)</span>
            @endswitch
          </p>
          </li>
        @endforeach
      </ul>
    </div>



  </div>
</div>


<div id="back" onclick="hidecommentActionBox()"></div>
<!-- Modal: لیست بلاگ‌های دسته -->
<div id="categoryBlogsOverlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.45); z-index:1100;"></div>

<div id="categoryBlogsModal" style="
  display:none; position:fixed; inset:0; z-index:1110;
  display:flex; align-items:center; justify-content:center;">
  <div style="
    width: min(700px, 92vw);
    max-height: 80vh;
    background:#fff; border-radius:12px; overflow:hidden;
    direction: rtl; box-shadow:0 10px 30px rgba(0,0,0,.2);
  ">
    <div style="display:flex; align-items:center; justify-content:space-between; padding: .8rem 1rem; background:#f6f6f6;">
      <strong id="catModalTitle" style="font-size:1rem">لیست پست‌ها</strong>
      <button id="closeCatModal" style="border:none; background:transparent; font-size:1.2rem; line-height:1;">✖</button>
    </div>
    <div id="catModalBody" style="padding: .6rem 1rem; overflow:auto; max-height: calc(80vh - 52px);">
      <div id="catLoading" style="padding:1rem; text-align:center;">در حال بارگذاری...</div>
      <ul id="catList" style="list-style:none; margin:0; padding:0; display:none;"></ul>
      <div id="catEmpty" style="display:none; text-align:center; padding:1rem;">پستی در این دسته یافت نشد.</div>
    </div>
  </div>
</div>

<!-- Global Floating Options Menu -->
<div id="global-options-menu" dir="rtl" style="display:none; position:fixed; z-index: 9999; min-width: 160px; background:#fff; border:1px solid #ddd; border-radius:8px; box-shadow:0 6px 18px rgba(0,0,0,.12);">
  <ul style="list-style:none; margin:0; padding:6px 0; font-size:14px;">
    
    <li data-action="reply" style="padding:10px 12px; cursor:pointer; display:flex; align-items:center; gap:8px;">
      <i class="fa-solid fa-reply"></i> پاسخ
    </li>
    
    <li data-action="like" style="padding:10px 12px; cursor:pointer; display:flex; align-items:center; gap:8px;">
      <i class="fas fa-thumbs-up"></i> لایک
      <span id="gom-like" style="margin-right:auto; font-size:12px; opacity:.8;"></span>
    </li>
    
    <li data-action="dislike" style="padding:10px 12px; cursor:pointer; display:flex; align-items:center; gap:8px;">
      <i class="fas fa-thumbs-down"></i> دیسلایک
      <span id="gom-dislike" style="margin-right:auto; font-size:12px; opacity:.8;"></span>
    </li>
    
    <li data-action="report" style="padding:10px 12px; cursor:pointer; display:flex; align-items:center; gap:8px;">
      <i class="fas fa-flag"></i> گزارش
      <span id="gom-flag" style="margin-right:auto; font-size:12px; opacity:.8;"></span>
    </li>
    

    <li data-static="time" style="padding:10px 12px; display:flex; align-items:center; gap:8px; cursor:default;">
      <i class="fa-regular fa-clock"></i> —
    </li>
    
    <li data-action="edit" style="padding:10px 12px; cursor:pointer; display:none; align-items:center; gap:8px;">
      <i class="fa-solid fa-pen-to-square"></i> ویرایش
    </li>
    
    <li data-action="delete" style="padding:10px 12px; cursor:pointer; color:#d33; display:none; align-items:center; gap:8px;">
      <i class="fa-solid fa-trash"></i> حذف
    </li>
    
  </ul>
</div>

<script>

  
function sendReaction(type) {
    blogId = '{{ $blog->id }}';
    container = document.querySelector('.reaction-buttons')
    console.log('ok')
  $.ajax({
    url: `/blogs/${blogId}/react`,
    method: 'POST',
    data: {
      type: type,
      _token: document.querySelector('meta[name="csrf-token"]').content
    },
    success: function (data) {
      if (data.status === 'success') {
        // بروزرسانی تعداد لایک/دیسلایک
        $(container).find('.like-count').text(data.likes);
        $(container).find('.dislike-count').text(data.dislikes);

        // تغییر کلاس‌ برای حالت فعال یا غیرفعال
        const likeBtn = $(container).find('.btn-like');
        const dislikeBtn = $(container).find('.btn-dislike');

        if (type === '1') {
          likeBtn.toggleClass('active');
          dislikeBtn.removeClass('active');
        } else {
          dislikeBtn.toggleClass('active');
          likeBtn.removeClass('active');
        }
      } else {
        showErrorAlert(data.message || 'خطا در ثبت واکنش');
      }
    },
    error: function () {
      showErrorAlert('❌ خطا در ارتباط با سرور');
    }
  });
}

function reportMessage(messageId) {
    const reason = prompt('لطفاً دلیل گزارش این پیام را وارد کنید:');
    if (reason) {
        fetch(`/groups/messages/${messageId}/report`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('پیام با موفقیت گزارش شد.');
            } else {
                alert('خطا در گزارش پیام.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('خطا در گزارش پیام.');
        });
    }
}
(function(){
  let currentMsgId = null;
  const menu = document.getElementById('global-options-menu');

  // گرفتن CSRF
  function getCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
  }

  // واکنش‌ها — توابع موجود خودت را صدا بزن
  function doAction(action) {
      console.log(currentMsgId)
    if (!currentMsgId) return;
    if (action === 'reply') { replyToSelectedComment(currentMsgId); }
    if (action === 'like')  { reactToComment('like', currentMsgId); }
    if (action === 'dislike'){ reactToComment('dislike', currentMsgId); }
    if (action === 'edit')  { editComment(currentMsgId); }
    if (action === 'report')  { reportMessage(currentMsgId); }
    if (action === 'delete'){ deleteComment(currentMsgId); }
    hideMenu();
  }

  // رویدادهای آیتم‌های منو (event delegation)
  menu.addEventListener('click', function(e){
    const li = e.target.closest('li[data-action]');
    if (!li) return;
    doAction(li.getAttribute('data-action'));
  });

  // خارج از منو کلیک شد → بستن
  document.addEventListener('click', function(e){
    if (!menu.contains(e.target) && !e.target.closest('.options-btn')) hideMenu();
  });
  window.addEventListener('resize', repositionMenu);
  window.addEventListener('scroll',  repositionMenu, true);

  function hideMenu(){ menu.style.display='none'; currentMsgId=null; }
  function showMenu(){ menu.style.display='block'; }
  function repositionMenu(){
    const anchor = document.querySelector(`button.options-btn[data-open-for="${currentMsgId}"]`);
    if (!anchor || menu.style.display==='none') return;
    placeMenuNear(anchor);
  }

  function placeMenuNear(anchor){
    const rect = anchor.getBoundingClientRect();
    const padding = 8;
    // چون RTL هستی، منو را سمت چپ دکمه قرار می‌دهیم
    let left = Math.max(padding, rect.left - menu.offsetWidth - 6);
    let top  = Math.max(padding, rect.top + rect.height/2 - menu.offsetHeight/2);

    // اگر از صفحه بیرون زد، فیکس کن
    const vw = window.innerWidth, vh = window.innerHeight;
    if (left < padding) left = rect.right + 6; // اگر جا نبود از سمت راست دکمه
    if (top + menu.offsetHeight > vh - padding) top = vh - padding - menu.offsetHeight;
    if (top < padding) top = padding;

    menu.style.left = left + 'px';
    menu.style.top  = top  + 'px';
  }

  // API عمومی برای دکمه‌ها
  window.openGlobalMenu = function(event, id){
    event.stopPropagation();
    currentMsgId = id;

    // برای پیدا کردن دوباره دکمه جهت reposition
    const btn = event.currentTarget;
    btn.setAttribute('data-open-for', String(id));

    // داده‌ها را از DOM پیام بخوان (مثل قبل)
    const bubble = document.getElementById(`msg-${id}`);
    const isMine = !bubble.classList.contains('other'); // یا هر منطقی که داری
    const likeEl = document.getElementById(`like-count-${id}`);
    const dislikeEl = document.getElementById(`dislike-count-${id}`);
    const timeLi = menu.querySelector('li[data-static="time"]');

    // نمایش/عدم نمایش ویرایش/حذف
    menu.querySelector('li[data-action="edit"]').style.display   = isMine ? 'flex' : 'none';
    menu.querySelector('li[data-action="delete"]').style.display = isMine ? 'flex' : 'none';

    // ست کردن شمارنده‌ها
    menu.querySelector('#gom-like').textContent    = likeEl ? likeEl.textContent.trim() : '0';
    menu.querySelector('#gom-dislike').textContent = dislikeEl ? dislikeEl.textContent.trim() : '0';

    // زمان (از همون آیتم ثابت داخل هر پیام برداریم اگر هست)
    const timeInside = bubble.querySelector('.fa-regular.fa-clock')?.parentElement?.innerText;
    timeLi.textContent = timeInside ? timeInside.replace(/^.*?:\s*/, '') : '—';

    // نمایش و موقعیت‌دهی کنار دکمه
    showMenu();
    placeMenuNear(btn);
  };

  // این دو تابع همون‌هایی هستند که از قبل داشتی؛ فقط از global menu استفاده می‌کنیم
  window.editComment  = window.editComment  || function(id){ /* کد فعلی خودت */ };
  window.deleteComment= window.deleteComment|| function(id){ /* کد فعلی خودت */ };
  window.replyToSelectedComment = window.replyToSelectedComment || function(id){ /* کد فعلی خودت */ };
  window.reactToComment = window.reactToComment || function(type,id){ /* کد فعلی خودت */ };

})();
</script>

<script>
(function() {
    
  function openCatModal() {
    $('#categoryBlogsOverlay').fadeIn(120);
    $('#categoryBlogsModal').fadeIn(120);
    $('body').css('overflow','hidden');
  }
  function closeCatModal() {
    $('#categoryBlogsModal').fadeOut(120);
    $('#categoryBlogsOverlay').fadeOut(120, function(){
      $('body').css('overflow','');
    });
  }
  closeCatModal()

  // بستن
  $(document).on('click', '#closeCatModal, #categoryBlogsOverlay', closeCatModal);
  $(document).on('keydown', function(e){ if (e.key === 'Escape') closeCatModal(); });

  // باز کردن + AJAX
  $(document).on('click', '.open-category-blogs', function(e) {
      
    e.preventDefault();
    e.stopPropagation();

    var ajaxUrl = $(this).data('url');          // URL آماده از data-url
    console.log(ajaxUrl)
    var groupId = $(this).data('group-id') || '';
    if (!ajaxUrl) return;

    // ریست UI
    $('#catList').empty().hide();
    $('#catEmpty').hide();
    $('#catLoading').show();
    $('#catModalTitle').text('در حال بارگذاری...');
    openCatModal();

    $.ajax({
      url: ajaxUrl,
      method: 'GET',
      data: { group_id: groupId },
      dataType: 'json',
      headers: { 'Accept': 'application/json' },
      cache: false,
      timeout: 15000 // 15s
    })
    .done(function(res){
      try {
        $('#catModalTitle').text('دسته: ' + (res?.category?.name || '—') + ' (' + (res?.count ?? 0) + ')');
        var items = Array.isArray(res?.items) ? res.items : [];
        $('#catLoading').hide();

        if (!items.length) { $('#catEmpty').show(); return; }

        var $list = $('#catList').show();
        items.forEach(function(item) {
          var $li = $('<li/>').css({
            padding: '.75rem .5rem',
            borderBottom: '1px solid #eee',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            gap: '.5rem'
          });

          var $left = $('<div/>').css({display:'flex', flexDirection:'column', gap:'.25rem'});
          var $title = $('<a/>', { href: item.url, text: item.title, title: item.title })
            .css({ color:'#0d6efd', textDecoration:'none', fontWeight:'600' })
            .hover(function(){ $(this).css('text-decoration','underline'); },
                   function(){ $(this).css('text-decoration','none'); });

          var $date = $('<small/>', { text: item.date }).css({ color:'#666' });
          $left.append($title, $date);

          var $go = $('<a/>', { href: item.url, text: 'مشاهده' })
            .css({ padding: '.35rem .6rem', borderRadius: '8px', border: '1px solid #ddd', textDecoration:'none' });

          $li.append($left, $go);
          $list.append($li);
        });
      } catch (err) {
        console.error('Parse/render error:', err);
        $('#catLoading').hide();
        $('#catEmpty').show().text('خطا در پردازش داده‌ها.');
      }
    })
    .fail(function(xhr, status, err){
      console.error('AJAX fail:', status, err, xhr?.status, xhr?.responseText);
      $('#catLoading').hide();
      // اگر ریدایرکت به لاگین شده باشد یا HTML برگشته:
      $('#catEmpty').show().text('خطا در دریافت لیست پست‌ها.');
    })
    .always(function(){
      // اگر به هر دلیلی هنوز لودینگ باز بود، جمعش کن
      if ($('#catLoading').is(':visible')) {
        $('#catLoading').hide();
        $('#catEmpty').show().text('عدم دریافت پاسخ از سرور.');
      }
    });
  });
})();
</script>

@endsection

@section('scripts')



@push('scripts')
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<script>
(function () {
  // فقط روی مسیرهای /groups/comment/{id}
  var onThisPage = /^\/?groups\/comment\/\d+\/?$/.test(location.pathname);
  if (!onThisPage) return;

  // سلکتور باکس — اگه فرق داره عوضش کن
  var SELECTOR = '#box-widget-icon';

  // تاخیر (میلی‌ثانیه). مثلا 1500 یعنی 1.5 ثانیه
  var DELAY_MS = 0; 

  function disableOrHide($el) {
    setTimeout(function () {
      // اگه فرم‌المنت (input/button) هست:
      $el.prop('disabled', true);

      // برای هر نوع المنت — یکی رو انتخاب کن:
      // 1) کامل مخفی:
      $el.css('display', 'none');

      // یا 2) فقط غیرفعال ظاهری:
      // $el.css({ 'pointer-events': 'none', 'opacity': 0.5 });
      // $el.attr('aria-disabled', 'true');

    }, DELAY_MS);
  }

  // اگر از قبل تو DOM بود
  var $existing = $(SELECTOR);
  if ($existing.length) disableOrHide($existing);

  // منتظر inject شدن بمون
  var observer = new MutationObserver(function () {
    var $target = $(SELECTOR);
    if ($target.length) {
      disableOrHide($target);
      observer.disconnect();
    }
  });

  observer.observe(document.body, { childList: true, subtree: true });

  // قطع ایمن بعد از 10 ثانیه (اختیاری)
  setTimeout(function(){ observer.disconnect(); }, 10000);
})();

    
        CKEDITOR.replace('message_editor', {
            
                height: 60,
    removePlugins: 'toolbar',
    on: {
        instanceReady: function(evt) {
            const body = evt.editor.document.getBody();
            body.setStyle('margin', '.5rem 1rem');
            body.setStyle('font-family', 'system-ui');
            body.setStyle('line-height', '.4');
        }
    },


        language: 'fa',
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

    function syncEditor() {
        for (var i in CKEDITOR.instances) {
            CKEDITOR.instances[i].updateElement();
        }

        // اگه با JS می‌خوای فرم رو ارسال کنی، بعدش پاکش کن
        setTimeout(() => {
            CKEDITOR.instances['message_editor'].setData('');
        }, 500); // کمی تأخیر برای اینکه فرم ارسال بشه

        return true;
    }

</script> 
@endpush

<script>
  const blogID = {{ $blog->id }};

</script>
<script src="{{ asset('js/comment.chat.js') }}" defer></script>

@endsection
