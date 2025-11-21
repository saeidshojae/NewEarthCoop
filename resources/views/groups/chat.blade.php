@extends('layouts.unified')

@section('title', $group->name . ' - گفت‌وگوی گروه')

@section('head-tag')

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// مطمئن شو Select2 بعد از jQuery لود شده
if (typeof jQuery !== 'undefined') {
  jQuery.fn.select2.defaults.set('language', {
    noResults: function() { return "نتیجه‌ای یافت نشد"; },
    searching: function() { return "در حال جستجو..."; }
  });
}
</script>

<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>



<!-- CSRF Token (برای Ajax) -->
<meta name="csrf-token" content="{{ csrf_token() }}">


  <link rel="stylesheet" href="{{ asset('Css/group-chat.css') }}">
<script>
document.addEventListener("DOMContentLoaded", function () {
  const csrf = '{{ csrf_token() }}';

  // --- Helpers: ایجاد/نمایش/مخفی‌کردن overlay ---
  function ensureOverlay(){
    let el = document.getElementById('global-loading');
    if(!el){
      el = document.createElement('div');
      el.id = 'global-loading';
      el.className = 'loading-overlay';
      el.innerHTML = '<div class="spinner"></div>';
      document.body.appendChild(el);
    }
    return el;
  }
  const overlay = ensureOverlay();
  const showOverlay = ()=> overlay.classList.add('show');
  const hideOverlay = ()=> overlay.classList.remove('show');

  // کلیدکردن روی یک دکمه: حالت لودینگ محلی
  function setBtnLoading(btn, on=true){
    if(!btn) return;
    if(on){ btn.classList.add('btn-loading'); btn.disabled = true; }
    else  { btn.classList.remove('btn-loading'); btn.disabled = false; }
  }

  // پوشش‌دهنده‌ی عمومی برای fetch با چرخنده
  async function withSpinner(fn, {global=true, btn=null}={}){
    try{
      if(global) showOverlay();
      if(btn) setBtnLoading(btn, true);
      return await fn();
    } finally {
      if(global) hideOverlay();
      if(btn) setBtnLoading(btn, false);
    }
  }

  document.querySelectorAll(".message-bubble").forEach(bubble => {
    const id         = bubble.dataset.messageId;
    const editUrl    = bubble.dataset.editUrl;
    const deleteUrl  = bubble.dataset.deleteUrl;
    const reportUrl  = bubble.dataset.reportUrl;

    if (!id) { console.warn('message id missing', bubble); return; }


    // حذف
    bubble.querySelector(".btn-delete")?.addEventListener("click", async (e) => {
      const btn = e.currentTarget;
      if (!confirm("آیا از حذف پیام مطمئن هستید؟")) return;

      await withSpinner( async () => {
        const res = await fetch(deleteUrl, {
          method: "GET", // اگر روت resource داری: "DELETE" و URL بدون /delete
          headers: {
            "X-CSRF-TOKEN": csrf,
            "Accept": "application/json"
          },
          credentials: "same-origin"
        });

        let data = {};
        try { data = await res.json(); } catch(e){}

        if (res.ok && (data.status === 'success' || !data.status)) {
          location.reload()
        } else {
          alert(data.message || `خطا در حذف پیام (status ${res.status})`);
        }
      }, {global:true, btn});
    });

    // گزارش
    bubble.querySelector(".btn-report")?.addEventListener("click", async (e) => {
      const btn = e.currentTarget;
      const reason = prompt("دلیل گزارش را وارد کنید:");
      if (!reason) return;

      await withSpinner( async () => {
        const res = await fetch(reportUrl, {
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": csrf,
            "Accept": "application/json",
            "Content-Type": "application/json"
          },
          credentials: "same-origin",
          body: JSON.stringify({ reason })
        });

        let data = {};
        try { data = await res.json(); } catch(e) {}
        alert(data.message || (res.ok ? "گزارش ثبت شد" : `خطا در ثبت گزارش (status ${res.status})`));
      }, {global:true, btn});
    });
  });
});
</script>



  <script>
      const groupId = {{ $group->id }};
      const authUserId = {{ auth()->id() }};
      window.groupId = groupId;
      window.authUserId = authUserId;
      const manageCount = {{ $groupSetting ? $groupSetting->manager_count : 0 }};  
      const inspectorCount = {{ $groupSetting ? $groupSetting->inspector_count : 0 }};
  </script>
  <script src="{{ asset('js/group-chat.js') }}" defer></script>
  <script src="{{ asset('js/chat-features.js') }}" defer></script>
  <script src="{{ asset('js/voice-recorder.js') }}" defer></script>
  <style>
  .stat-chip {
    display: flex;
    flex-direction: column;
    gap: .35rem;
    padding: 1rem 1.1rem;
    border-radius: 1.5rem;
    background: rgba(236, 253, 245, 0.8);
    border: 1px solid rgba(16, 185, 129, 0.18);
    box-shadow: 0 18px 48px -30px rgba(16, 185, 129, 0.5);
  }

  .stat-chip__label {
    font-size: .75rem;
    font-weight: 600;
    color: #047857;
  }

  .stat-chip__value {
    font-size: 1.25rem;
    font-weight: 800;
    color: #0f4c3a;
  }

  .chat-scroll-btn {
    position: absolute;
    bottom: 1.75rem;
    left: 1.75rem;
    z-index: 30;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    border: none;
    background: linear-gradient(145deg, #34d399 0%, #10b981 100%);
    color: #fff;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 18px 40px -24px rgba(16, 185, 129, 0.55);
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
  }

  .chat-scroll-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 24px 55px -28px rgba(13, 148, 136, 0.6);
    background: linear-gradient(145deg, #0f766e 0%, #0d9488 100%);
  }

  .group-info-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.35);
    backdrop-filter: blur(2px);
    z-index: 900;
    opacity: 0;
    transition: opacity .3s ease;
  }

  .group-info-backdrop--visible {
    opacity: 1;
  }

  .menu-item {
  display: inline-flex;
  align-items: center;
  gap: .25rem;
  font-size: 0.875rem;
  padding: 4px 8px;
  border: none;
  background: transparent;
  cursor: pointer;
  color: #444;
  transition: color .2s;
}

.menu-item .icon {
  width: 14px;
  height: 14px;
}

.menu-item:hover { color: #0d6efd; }
.menu-item.text-danger { color: #dc3545; }
.menu-item.text-danger:hover { color: #bb2d3b; }

.message-bubble{position:relative;max-width:68ch;padding:10px 12px;margin:8px 0;margin-top: 0;padding-top: .2rem !important;border-radius: 12px 2px 12px 12px;}
.message-bubble.you{background:#eef6ff;margin-left:auto}
.message-bubble.other{background:#f6f6f6;margin-right:auto;}

.message-header{display:flex;align-items:center;gap:8px}
.message-sender{font-size:.9rem;color:blue; padding: 0;margin-left: 1.5rem;}
.message-content{margin:.35rem 0 .25rem;line-height:1.6}

.message-menu{margin-inline-start:auto;position:relative}
.menu-trigger{cursor:pointer;list-style:none;border:none;background:transparent;font-size:20px;line-height:1}
.message-menu>summary::-webkit-details-marker{display:none}

.menu-dropdown{
  position:absolute; inset-inline-end:0; top:22px;
  background:#fff; border:1px solid #e4e4e7; border-radius:10px;
  min-width:190px; box-shadow:0 8px 24px rgba(0,0,0,.08); padding:6px; z-index:10
}
/* تمام‌صفحه */
.loading-overlay{
  position: fixed; inset: 0; background: rgba(0,0,0,.25);
  display: none; align-items: center; justify-content: center; z-index: 9999;
}
.loading-overlay.show{ display:flex; }
.spinner{
  width: 48px; height: 48px; border: 4px solid #fff; border-top-color: transparent;
  border-radius: 50%; animation: spin .9s linear infinite;
}
@keyframes spin{ to{ transform: rotate(360deg); } }

/* حالت دکمه درحال پردازش */
.btn-loading{ position: relative; pointer-events: none; opacity: .7; }
.btn-loading::after{
  content: ""; position: absolute; right: .5rem; top: 50%; transform: translateY(-50%);
  width: 16px; height:16px; border:2px solid currentColor; border-top-color: transparent;
  border-radius:50%; animation: spin .8s linear infinite;
}
.menu-item{
  width:100%; display:flex; align-items:center; gap:8px;
  text-align:inherit; background:transparent; border:0;
  padding:8px 10px; border-radius:8px; cursor:pointer; font-size:.92rem
}
.menu-item:hover{background:#f5f5f5}
.menu-item svg{flex:0 0 auto}
.menu-meta-time{
  margin-top:6px; padding:8px 10px; font-size:.78rem; color:#777; border-top:1px solid #eee
}
.message-row {
  display: flex;
  align-items: flex-start;
  margin: 10px 0;
  gap: 8px;
}
.message-row.you { flex-direction: row-reverse; }

.avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: #d1d5db; color: #333;
  font-weight: 600; font-size: 0.9rem;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

.message-bubble {
  position: relative;
  max-width: 100%;
  background: #f6f6f6;
  padding: 8px 12px;
  border-radius: 12px;
}
.message-row.you .message-bubble { background: #eef6ff;     border-radius: 2px 12px 12px 12px;}

.message-sender {
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 4px;
  display: block;
}

.message-content { margin: 0; font-size: 0.95rem; line-height: 1.4; }

/* ریپلای شبیه تلگرام */
.reply-preview {
  border-left: 3px solid #3b82f6; /* رنگ آبی تلگرامی */
  padding-left: 6px;
  margin-bottom: 4px;
  font-size: 0.8rem;
  background: rgba(59,130,246,0.08);
  border-radius: 4px;
      padding: .2rem .4rem;
}
.reply-sender { font-weight: 600; color: #1e40af; font-size: 0.78rem; }
.reply-text { color: #333; font-size: 0.75rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* منوی سه‌نقطه */
.menu-wrapper {
  position: absolute;
  top: 4px;
}
.menu-wrapper.right { left: .3rem; }   /* برای دیگران */
.menu-wrapper.left { right: .2rem; }     /* برای خودم */

.menu-trigger {
  border: none; background: transparent;
  font-size: 16px; cursor: pointer;
  padding: 0; line-height: 1;
}
.menu-wrapper > summary::-webkit-details-marker { display: none; }

.menu-dropdown {
  position: absolute; top: 20px; left: 0;
  background: #fff; border: 1px solid #e4e4e7;
  border-radius: 8px; min-width: 120px; padding: 4px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1); z-index: 10;
}
.menu-item {
  display: block; width: 100%;
  padding: 6px 8px; background: transparent; border: none;
  text-align: right; font-size: 0.85rem; cursor: pointer;
}
.menu-item:hover { background: #f5f5f5; }
.menu-meta-time {
  margin-top: 4px; padding: 6px 8px;
  font-size: 0.7rem; color: #777;
  border-top: 1px solid #eee;
}




      #categoryBlogsModal{
            display: flex;
    justify-content: center;
    }
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
      
      #cke_1_top{
          display: none;
      }
            #cke_2_top{
          display: none;
      }
                  .cke_top{
          display: none !important ;
      }
      .cke_bottom{
                    display: none !important ;

      }
      #cke_2_bottom{
                    display: none;

      }
            #cke_3_bottom{
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
      .cke_contents{
                  overflow: auto;
        height: 5rem !important;
        width: 100%;
      }
      
      .cke_editable{
          margin: .4rem !important;
      }
      #postFormBox{
          height: auto;
      }
      
/* سرچ‌باکس */
.gc-searchbar{
  position: relative; display:flex; align-items:center; gap:.5rem;
  background:#fff; border:1px solid #e4e4e7; border-radius:10px;
  padding:.4rem .6rem; width:min(520px, 98%); direction: rtl;
}
.gc-searchbar > i{ opacity:.6; }
#gc-search-input{
  flex:1; border:0; outline:0; font:inherit; background:transparent; direction: rtl;
}
#gc-search-clear{
  border:0; background:transparent; cursor:pointer; opacity:.6;
}
.gc-search-dropdown{
  position:absolute; inset-inline-start:0; top:110%;
  width:100%; background:#fff; border:1px solid #e4e4e7; border-radius:10px;
  box-shadow:0 10px 24px rgba(0,0,0,.08); padding:.3rem; z-index:30;
}
.gc-search-status{
  font-size:.85rem; color:#666; padding:.4rem .5rem;
}
.gc-search-list{ list-style:none; margin:0; padding:0; max-height:50vh; overflow:auto; }
.gc-search-item{
  display:flex; gap:.6rem; padding:.55rem .6rem; border-radius:8px; cursor:pointer; direction: rtl;
}
.gc-search-item:hover, .gc-search-item.active{ background:#f6f7fb; }
.gc-search-item .type{
  font-size:.75rem; opacity:.7; min-width:70px; text-align:center; padding:.1rem .3rem; border:1px solid #eee; border-radius:999px;
}
.gc-search-item .meta{
  display:flex; flex-direction:column; gap:.15rem; min-width:0;
}
.gc-search-item .title{ font-weight:600; font-size:.9rem; color:#1f2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.gc-search-item .snip{ font-size:.85rem; color:#374151; line-height:1.3; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; }
.gc-search-item mark{ background:#ffe58f; }
.gc-search-more{
  width:100%; padding:.45rem .7rem; border:1px solid #e5e7eb; background:#f9fafb; border-radius:8px; cursor:pointer; margin-top:.4rem;
}
@media (max-width: 768px){
  .gc-searchbar{ width:100%; }
}
/* آیکن جستجو در هدر */
.btn-chat-icon{
  width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;
  border-radius:8px; background:transparent; cursor:pointer;
}
.btn-chat-icon:hover{ background:#f3f4f6; }
.btn-chat-icon.searching i{ position:relative; }
.btn-chat-icon.searching i::after{
  content:""; position:absolute; inset:auto auto -2px -2px; width:14px; height:14px;
  border:2px solid currentColor; border-top-color:transparent; border-radius:50%;
  animation:spin .8s linear infinite;
}

/* کانتینر سرچ زیر هدر */
.chat-header{ position:relative; }
.gc-search-wrap{
  position:absolute; inset-inline-end:0; top:100%; margin-top:8px; z-index:50; right: 1rem !important;
  width:min(560px, 92vw);
}

/* خود سرچ‌بار و دراپ‌داون (از کد قبلی‌ات) */
.gc-searchbar{
  position:relative; display:flex; align-items:center; gap:.5rem;
  background:#fff; border:1px solid #e4e4e7; border-radius:10px; padding:.4rem .6rem;
  direction:rtl; box-shadow:0 10px 24px rgba(0,0,0,.08);
}
.gc-searchbar>i{ opacity:.6; }
#gc-search-input{ flex:1; border:0; outline:0; font:inherit; background:transparent; direction:rtl; }
#gc-search-clear{ border:0; background:transparent; cursor:pointer; opacity:.6; }

.gc-search-dropdown{
  position:absolute; inset-inline-start:0; top:110%;
  width:100%; background:#fff; border:1px solid #e4e4e7; border-radius:10px;
  box-shadow:0 10px 24px rgba(0,0,0,.08); padding:.3rem; z-index:30;
}
.gc-search-status{ font-size:.85rem; color:#666; padding:.4rem .5rem; display:none; align-items:center; gap:.4rem; }
.gc-spin{ width:16px; height:16px; border:2px solid #bbb; border-top-color:transparent; border-radius:50%; display:inline-block; animation:spin .8s linear infinite; vertical-align:middle; }

.gc-search-list{ list-style:none; margin:0; padding:0; max-height:50vh; overflow:auto; }
.gc-search-item{ display:flex; gap:.6rem; padding:.55rem .6rem; border-radius:8px; cursor:pointer; direction:rtl; }
.gc-search-item:hover, .gc-search-item.active{ background:#f6f7fb; }
.gc-search-item .type{ font-size:.75rem; opacity:.7; min-width:70px; text-align:center; padding:.1rem .3rem; border:1px solid #eee; border-radius:999px; }
.gc-search-item .meta{ display:flex; flex-direction:column; gap:.15rem; min-width:0; }
.gc-search-item .title{ font-weight:600; font-size:.9rem; color:#1f2937; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.gc-search-item .snip{ font-size:.85rem; color:#374151; line-height:1.3; display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; }
.gc-search-item mark{ background:#ffe58f; }
.gc-search-more{ width:100%; padding:.45rem .7rem; border:1px solid #e5e7eb; background:#f9fafb; border-radius:8px; cursor:pointer; margin-top:.4rem; }

@keyframes spin{ to{ transform:rotate(360deg); } }
.chat-header{ position:relative; }
.gc-search-wrap{
  position:absolute; inset-inline-end:0; top:100%; margin-top:8px;
  width:min(560px, 92vw); z-index: 2000; /* از dropdown بالاتر باشه */
}
.btn-chat-icon{
  width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;
  border-radius:8px; background:transparent; cursor:pointer;
}
.btn-chat-icon:hover{ background:#f3f4f6; }
.gc-searchbar{
  position:relative; display:flex; align-items:center; gap:.5rem;
  background:#fff; border:1px solid #e4e4e7; border-radius:10px; padding:.4rem .6rem;
  direction:rtl; box-shadow:0 10px 24px rgba(0,0,0,.08);
}
.gc-search-dropdown{
  position:absolute; inset-inline-start:0; top:110%;
  width:100%; background:#fff; border:1px solid #e4e4e7; border-radius:10px;
  box-shadow:0 10px 24px rgba(0,0,0,.08); padding:.3rem; z-index: 2100;
}
.gc-search-status{ display:none; align-items:center; gap:.4rem; }
.gc-spin{ width:16px; height:16px; border:2px solid #bbb; border-top-color:transparent; border-radius:50%; animation:spin .8s linear infinite; }
@keyframes spin{ to{ transform: rotate(360deg); } }

  </style>
  
@endsection

@section('content')
  @php
      $memberCount = $group->userCount();
      $guestCount = $group->guestsCount();
      $blogCount = \App\Models\Blog::where('group_id', $group->id)->count();
      $pollCount = $group->polls()->count();
      $pivotUser = $group->users()->where('users.id', auth()->id())->first();
      $roleTitle = match((int)($pivotUser?->pivot?->role ?? -1)) {
          0 => 'ناظر',
          1 => 'فعال',
          2 => 'بازرس',
          3 => 'مدیر',
          4 => 'مهمان',
          5 => 'فعال ۲',
          default => 'عضو'
      };
      $membershipStatusLabel = (int)($pivotUser?->pivot?->status ?? 0) === 1 ? 'فعال' : 'غیرفعال';
      $checkBlockElection = \App\Models\Block::where('user_id', auth()->id())->where('position', 'election')->first();
      $electionAvailable = ($election ?? null) && optional($groupSetting)->election_status == 1;
      $canParticipateElection = $electionAvailable && !$checkBlockElection && optional(auth()->user())->status == 1;
  @endphp
  <div class="container mx-auto max-w-7xl px-4 pt-6 pb-8 space-y-10" style="direction: rtl;">
    <section class="bg-white border border-emerald-100 rounded-3xl shadow-sm px-5 py-6 relative overflow-hidden">
      <div class="absolute inset-0 pointer-events-none bg-gradient-to-l from-emerald-50/50 via-transparent to-transparent"></div>
      <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex items-center gap-5">
          <div class="w-16 h-16 rounded-3xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl font-black shadow-inner">
            @if($group->avatar)
              <img src="{{ asset('images/groups/' . $group->avatar) }}" alt="{{ $group->name }}" class="w-full h-full object-cover rounded-3xl">
            @else
              {{ Str::upper(Str::substr($group->name, 0, 2)) }}
            @endif
          </div>
          <div class="space-y-2">
            <div class="flex items-center gap-3 flex-wrap">
              <h1 class="text-2xl lg:text-3xl font-black text-slate-900">{{ $group->name }}</h1>
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-600 text-sm font-semibold">
                <i class="fas fa-user-shield"></i>{{ $roleTitle }}
              </span>
              <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-sm">
                <i class="fas fa-wave-square"></i>{{ $membershipStatusLabel }}
              </span>
            </div>
            <div class="flex flex-wrap items-center gap-3 text-sm text-slate-600">
              <span class="inline-flex items-center gap-2">
                <i class="fas fa-users text-emerald-500"></i>{{ $memberCount }} عضو
              </span>
              @if($guestCount > 0)
                <span class="inline-flex items-center gap-2">
                  <i class="fas fa-user-clock text-emerald-500"></i>{{ $guestCount }} مهمان
                </span>
              @endif
              @if($group->location_level)
                <span class="inline-flex items-center gap-2">
                  <i class="fas fa-map-marker-alt text-emerald-500"></i>{{ $group->location_level }}
                </span>
              @endif
              <span class="inline-flex items-center gap-2">
                <i class="fas fa-calendar-check text-emerald-500"></i>{{ verta($group->created_at)->format('Y/m/d') }}
              </span>
            </div>
            @if(!empty($group->description))
              <p class="text-sm text-slate-500 leading-relaxed max-w-2xl">
                {{ Str::limit(strip_tags($group->description), 180) }}
              </p>
            @endif
          </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 justify-start lg:justify-end">
          <button type="button"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition lg:hidden"
                  onclick="openGroupInfo()">
            <i class="fas fa-layer-group"></i>
            پنل گروه
          </button>
          @if(($yourRole ?? 0) !== 5)
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-500 text-white shadow-sm hover:bg-emerald-600 transition"
                    onclick="openBlogBox()">
              <i class="far fa-pen-to-square"></i>
              ایجاد پست
            </button>
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-600 hover:bg-emerald-200 transition"
                    onclick="openPollBox()">
              <i class="fas fa-chart-simple"></i>
              ساخت نظرسنجی
            </button>
          @endif
          @if($electionAvailable)
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl {{ $canParticipateElection ? 'bg-indigo-500 text-white shadow-sm hover:bg-indigo-600 transition' : 'bg-slate-100 text-slate-500 cursor-not-allowed' }}"
                    @if($canParticipateElection) onclick="openElectionBox()" @else disabled @endif>
              <i class="fas fa-vote-yea"></i>
              {{ $canParticipateElection ? 'شرکت در انتخابات' : 'انتخابات فعال' }}
            </button>
          @endif
          @if(in_array($yourRole ?? 0, [2,3]))
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition"
                    onclick="openElection2Box()">
              <i class="fas fa-ballot-check text-emerald-500"></i>
              افزودن انتخابات
            </button>
          @endif
          @if(($yourRole ?? 0) == 3)
            <button type="button"
                    id="manage-members-btn"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-blue-200 text-blue-600 hover:bg-blue-50 transition"
                    onclick="if(typeof window.showManageMembersModal === 'function') { window.showManageMembersModal(); } else { console.error('showManageMembersModal not found'); alert('تابع مدیریت اعضا یافت نشد. لطفاً صفحه را رفرش کنید.'); }">
              <i class="fas fa-users-cog"></i>
              مدیریت اعضا
            </button>
            <button type="button"
                    id="manage-reports-btn"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-orange-200 text-orange-600 hover:bg-orange-50 transition relative"
                    onclick="if(typeof window.showManageReportsModal === 'function') { window.showManageReportsModal(); } else { console.error('showManageReportsModal not found'); alert('تابع مدیریت گزارش‌ها یافت نشد. لطفاً صفحه را رفرش کنید.'); }">
              <i class="fas fa-flag"></i>
              گزارش‌ها
              <span id="reports-badge" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center" style="display: none;">0</span>
            </button>
          @endif
          <button type="button"
                  id="group-settings-btn"
                  class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-emerald-200 text-emerald-600 hover:bg-emerald-50 transition"
                  onclick="showGroupSettingsModal()">
            <i class="fas fa-cog"></i>
            تنظیمات
          </button>
          <a href="{{ route('groups.logout', $group->id) }}"
             class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl border border-red-100 text-red-500 hover:bg-red-50 transition">
            <i class="fas fa-door-open"></i>
            خروج از گروه
          </a>
        </div>
      </div>
      <div class="relative z-10 mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="stat-chip">
          <span class="stat-chip__label">پیام‌های سنجاق‌شده</span>
          <span class="stat-chip__value">{{ $pinnedMessages->count() }}</span>
        </div>
        <div class="stat-chip">
          <span class="stat-chip__label">پست‌ها</span>
          <span class="stat-chip__value">{{ $blogCount }}</span>
        </div>
        <div class="stat-chip">
          <span class="stat-chip__label">نظرسنجی‌ها</span>
          <span class="stat-chip__value">{{ $pollCount }}</span>
        </div>
        <div class="stat-chip">
          <span class="stat-chip__label">آخرین فعالیت</span>
          <span class="stat-chip__value">{{ verta($group->updated_at)->formatDifference() }}</span>
        </div>
      </div>
    </section>
  
  @include('groups.modals.group_edit_form', compact('group'))
  @php use Illuminate\Support\Str; @endphp
<div class="loading-overlay" id="global-loading">
  <div class="spinner"></div>
</div>

  
  <div class="grid gap-8 lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)] items-start">
    <div class="space-y-6">
      @if ($pinnedMessages->count() > 0)
        <div class="bg-white border border-emerald-100 rounded-3xl shadow-sm">
          <div class="pinned-messages">
      @foreach($pinnedMessages as $pinnedMessage)
        <a class="pin" href="{{ $pinnedMessage->message->message == 'اطلاعیه پین شده' ? '#msg-' .$pinnedMessage->message->id : '#msg-' . $pinnedMessage->message->id  }}">
          <div>
                  <b>پیام سنجاق‌شده</b>
                  <p>{!! Str::limit(strip_tags($pinnedMessage->message->message), 120, '...') !!}</p>
          </div>
          <i class="fas fa-thumbtack"></i>
        </a>
      @endforeach
          </div>
    </div>     
    @endif
   
      <div class="chat-wrapper">
        <div class="chat-body" id="chat-box">
      @foreach($combined as $item)
        @include('groups.partials.' . $item->type, compact('item', 'group', 'userVote'))
      @endforeach
        </div>
        <button id="scroll-toggle-btn" class="chat-scroll-btn">
          <i class="fas fa-arrow-down"></i>
        </button>
    </div>
    
    @php
        $checkBlockMessage = \App\Models\Block::where('user_id', auth()->user()->id)->where('position', 'message')->first();
        $checkBlockPost = \App\Models\Block::where('user_id', auth()->user()->id)->where('position', 'post')->first();
        $checkBlockPoll = \App\Models\Block::where('user_id', auth()->user()->id)->where('position', 'poll')->first();
    @endphp
    
      <div class="bg-white border border-emerald-100 rounded-3xl shadow-sm p-5">
        @if ($yourRole === 0 && $group->is_open == 0)
          <p class="text-red-500">
            شما مجاز به ارسال پیام در گروه نیستید.
          </p>
        @elseif (auth()->user()->status == 0 || auth()->user()->first_name == null || auth()->user()->last_name == null)
          <p class="text-amber-600">
            به دلیل کامل نبودن اطلاعات کاربری امکان ارسال پیام را ندارید، از
            <a href='{{ route('profile.edit') }}' class="text-emerald-600 underline">این قسمت</a>
            اقدام به وارد کردن اطلاعات کنید.
          </p>
    @else
          <form id="chatForm"
                class="chat-input d-flex flex-column flex-md-row align-items-stretch gap-3 p-3"
                onsubmit="return syncEditor();"
      method="POST"
      action="{{ route('groups.messages.store') }}"
                enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="group_id" value="{{ $group->id }}">
    <input type="hidden" name="parent_id" id="parent_id" value="">
    <input type="file"
           name="voice_message"
           id="voice-file-input"
           accept="audio/*"
           class="d-none">

            <div class="flex-fill">
    @if ($checkBlockMessage != null)
                    <div class="chat-block-message text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-4 px-3 py-3">
                        شما از جانب مدیریت برای عملیات ارسال پیام مسدود شده‌اید، جهت رفع مسدودیت با مدیریت در ارتباط باشید.
                    </div>
    @else
                    <textarea class="form-control chat-textarea rounded-4 border-0"
                              name="message"
                              placeholder="پیام خود را بنویسید..."
                              id="message_editor"
                              rows="4"
                              style="resize: vertical;"></textarea>

                    <div id="voice-file-preview"
                         class="voice-file-preview d-flex align-items-center gap-2 py-2 px-3 rounded-3 mt-2"
                         style="display: none !important; background: #e3f2fd; border: 1px solid #90caf9; direction: rtl;">
                        <i class="fas fa-file-audio text-primary" style="font-size: 1.2rem;"></i>
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-semibold small mb-0 text-truncate" id="voice-file-name" style="color: #1976d2;"></div>
                            <small class="text-muted" id="voice-file-size" style="font-size: 0.75rem;"></small>
                        </div>
                        <button type="button"
                                class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                id="voice-file-remove"
                                style="font-size: 0.8rem; white-space: nowrap;">
                            <i class="fas fa-times"></i> حذف
                        </button>
                    </div>
    @endif
            </div>

            <div class="d-flex align-items-center gap-2 justify-content-end">
    @if($yourRole != 5)
                    <div class="position-relative chat-action-trigger">
                        <button type="button"
                                id="chatCreateToggle"
                                class="chat-tool-btn">
                            <i class="fas fa-plus"></i>
        </button>
        <div id="createMenu"
                             style="display: none;"
                             class="chat-tool-menu rounded-4 border shadow-lg bg-white overflow-hidden">
                            @if ($checkBlockPost != null)
                                <span class="chat-tool-menu__item text-danger">شما برای عملیات ایجاد پست مسدود شده‌اید</span>
            @else
                                <button type="button"
                                        class="chat-tool-menu__item"
                                        id="create-post-btn">
                                    <i class="far fa-edit text-success"></i>
                                    ایجاد پست
                                </button>
            @endif
            
                            @if ($checkBlockPoll != null)
                                <span class="chat-tool-menu__item text-danger">شما برای عملیات ایجاد نظرسنجی مسدود شده‌اید</span>
            @else
                                <button type="button"
                                        class="chat-tool-menu__item"
                                        id="create-poll-btn">
                                    <i class="fas fa-chart-simple text-success"></i>
                                    ایجاد نظرسنجی
                                </button>
            @endif

                                <button type="button"
                                        id="audio-upload-trigger"
                                        class="chat-tool-menu__item">
                                    <i class="fas fa-file-audio text-success"></i>
                                    ارسال فایل صوتی
                                </button>
        </div>
    </div>
    @endif

                <button type="submit"
                        class="btn btn-success rounded-4 px-4 px-md-5 d-flex align-items-center justify-content-center gap-2 chat-submit-btn">
                    <span class="fw-semibold">ارسال پیام</span>
        <i class="fas fa-paper-plane"></i>
    </button>
            </div>
</form>
        @endif
      </div>
    </div>

    <aside class="space-y-6 lg:pl-2">
      @include('groups.partials.group_info_panel', compact('group'))
    </aside>
  </div>

  <div id="groupInfoBackdrop" class="group-info-backdrop hidden"></div>
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
<script>
function togglePollMenu(pollId) {
    const menu = document.getElementById('poll-menu-' + pollId);
    if (!menu) return;
    if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
    } else {
        menu.style.display = 'none';
    }
}
</script>
<script>
function showEditPollBox(pollId) {
    // مثال: نمایش یک باکس ویرایش
    const editBox = document.getElementById('edit-poll-box-' + pollId);
    if (!editBox) return;
    editBox.style.display = editBox.style.display === 'none' || editBox.style.display === '' ? 'block' : 'none';
}
</script>
<script>
function confirmDelete(event, url) {
    event.preventDefault();
    if (confirm('آیا مطمئن هستید که می‌خواهید این آیتم را حذف کنید؟')) {
        window.location.href = url; // یا با AJAX حذف کن
    }
}
</script>
<!-- Edit Modal -->
<div id="editModal" class="edit-modal hidden" aria-hidden="true">
  <div class="edit-modal__backdrop"></div>
  <div class="edit-modal__panel" role="dialog" aria-modal="true" aria-labelledby="editModalTitle">
    <div class="edit-modal__header">
      <h3 id="editModalTitle">ویرایش پیام</h3>
      <button type="button" class="edit-close" aria-label="بستن">×</button>
    </div>
    <div class="edit-modal__body">
      <textarea id="editText" rows="6" class="edit-textarea" placeholder="متن پیام..."></textarea>
    </div>
    <div class="edit-modal__footer">
      <button type="button" class="btn btn-primary save-edit">ذخیره</button>
            <button type="button" class="btn cancel-edit " style='    background-color: #c24545 !important;'>لغو</button>
    </div>
  </div>
</div>

<style>
.edit-modal.hidden { display:none; }
.edit-modal { position:fixed; inset:0; z-index:9999; }
.edit-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.35); }
.edit-modal__panel {direction: rtl;
  position:relative; margin:5vh auto 0; max-width:640px; width:clamp(320px, 90vw, 640px);
  background:#fff; border-radius:12px; padding:1rem; box-shadow:0 20px 60px rgba(0,0,0,.15);
}
.edit-modal__header { display:flex; align-items:center; justify-content:space-between; gap:.5rem; }
.edit-modal__body { margin-top:.5rem; }
.edit-textarea { width:100%; padding:.75rem; border:1px solid #e5e7eb; border-radius:10px; font:inherit; }
.edit-modal__footer { display:flex; justify-content:flex-end; gap:.5rem; margin-top:.75rem; }
.btn { padding:.5rem .9rem; border-radius:8px; border:1px solid #e5e7eb; background:#f8fafc; cursor:pointer; }
.btn-primary { background:#2563eb; color:#fff; border-color:#2563eb; }
.edit-close { background:transparent; border:none; font-size:1.25rem; cursor:pointer; line-height:1; }
</style>

<script>
(function(){
  // اگر CSRF را در <meta name="csrf-token" content="..."> داری:
  const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

  const modal      = document.getElementById('editModal');
  const textarea   = document.getElementById('editText');
  const btnSave    = modal.querySelector('.save-edit');
  const btnCancel  = modal.querySelector('.cancel-edit');
  const btnClose   = modal.querySelector('.edit-close');
  const backdrop   = modal.querySelector('.edit-modal__backdrop');

  // متغیرهای وضعیت جاری ویرایش
  let currentBubble = null;   // عنصر .message-bubble
  let currentUrl    = null;   // آدرس PATCH
  let currentId     = null;   // message-id

  // هندلر کلیک روی "ویرایش"
  document.addEventListener('click', function(e){
    const editBtn = e.target.closest('.btn-edit');
    if (!editBtn) return;

    const bubble = editBtn.closest('.message-bubble');
    if (!bubble) return;

    currentBubble = bubble;
    currentUrl    = bubble.dataset.editUrl;
    currentId     = bubble.dataset.messageId;

    // متن فعلی برای پر کردن باکس:
    // 1) اگر data-content-raw داشت، همونو بگیر
    // 2) وگرنه از DOM (message-content) بخون و HTML رو به متن تبدیل کن
    const raw = bubble.dataset.contentRaw || htmlToPlain(bubble.querySelector('.message-content')?.innerHTML || "");

    textarea.value = raw;
    openModal();
  });

  // ذخیره (ارسال PATCH)
  btnSave.addEventListener('click', async function () {
  const newText = textarea.value.trim();

  if (!currentBubble || !currentUrl) {
    closeModal();
    return;
  }

  // چرخنده و دکمه
  const overlay = document.getElementById('global-loading');
  const showOverlay = () => overlay && overlay.classList.add('show');
  const hideOverlay = () => overlay && overlay.classList.remove('show');
  const setBtnLoading = (on=true) => {
    btnSave.disabled = on;
    btnSave.classList.toggle('btn-loading', on);
  };

  try {
    showOverlay();
    setBtnLoading(true);

    // اگر روتت دقیقاً POST می‌پذیره (بدون شبیه‌سازی PATCH)، همین کافیه:
    const res = await fetch(currentUrl, {
      method: 'POST', // مطابق کنترلرت
      headers: {
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      credentials: 'same-origin',
      body: JSON.stringify({ content: newText })
    });

    if (!res.ok) {
      // تلاش برای خواندن پیام خطا
      let msg = 'خطا در ذخیره‌سازی.';
      try {
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) {
          const j = await res.json();
          if (j?.message) msg = j.message;
        } else {
          const t = await res.text();
          if (t) msg = t.slice(0, 200);
        }
      } catch (_) {}
      alert(msg);
      return;
    }

    // موفق: مودال را ببند و صفحه را رفرش کن
    closeModal();
    // اگر منوی سه‌نقطه باز است، ببند
    const details = currentBubble.closest('details.menu-wrapper[open]');
    if (details) details.removeAttribute('open');

    location.reload(); // طبق خواسته‌ت ریلود

  } catch (err) {
    console.error(err);
        location.reload(); // طبق خواسته‌ت ریلود

  } finally {
    hideOverlay();
    setBtnLoading(false);
  }
});


  // بستن مودال
  [btnCancel, btnClose, backdrop].forEach(el => el.addEventListener('click', closeModal));
  document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });

  function openModal(){
    modal.classList.remove('hidden');
    textarea.focus();
    // مکان‌نما آخر متن:
    const val = textarea.value; textarea.setSelectionRange(val.length, val.length);
  }
  function closeModal(){
    modal.classList.add('hidden');
    textarea.value = '';
    currentBubble = null; currentUrl = null; currentId = null;
  }
  function htmlToPlain(html){
    const tmp = document.createElement('div'); tmp.innerHTML = html;
    return (tmp.textContent || tmp.innerText || '').trim();
  }
})();
</script>

<script>
const chatBox = document.getElementById('chat-box');
const STORAGE_KEY = 'chatScroll';
// جلوگیری از رفتار پیش‌فرض مرورگر
if ('scrollRestoration' in history) {
  history.scrollRestoration = 'manual';
}
// بازیابی بعد از لود
window.addEventListener('DOMContentLoaded', () => {
  const savedScroll = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
  console.log("Restored scroll:", savedScroll);
  setTimeout(() => {
    chatBox.scrollTop = savedScroll;
  }, 50);
});


// ذخیره اسکرول هنگام ترک صفحه
window.addEventListener('beforeunload', () => {
    sessionStorage.setItem(STORAGE_KEY, chatBox.scrollTop);
});

// ذخیره اسکرول در حین حرکت (اختیاری)
chatBox.addEventListener('scroll', () => {
    sessionStorage.setItem(STORAGE_KEY, chatBox.scrollTop);
});


(function () {
  function openCatModal() {
    $('#categoryBlogsOverlay').fadeIn(120);
    $('#categoryBlogsModal').fadeIn(120);
    $('body').css('overflow', 'hidden');
  }

  function closeCatModal() {
    $('#categoryBlogsModal').fadeOut(120);
    $('#categoryBlogsOverlay').fadeOut(120, function () {
      $('body').css('overflow', '');
    });
  }

  // اول بسته باشه
  closeCatModal();

  // بستن با کلیک یا Esc
  $(document).on('click', '#closeCatModal, #categoryBlogsOverlay', closeCatModal);
  $(document).on('keydown', function (e) {
    if (e.key === 'Escape') closeCatModal();
  });

  // باز کردن مدال
  document.querySelectorAll('.open-category-blogs').forEach(openCategory => {
    openCategory.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const ajaxUrl = $(this).data('url');
      const groupId = $(this).data('group-id') || '';

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
        .done(function (res) {
          try {
            $('#catModalTitle').text(
              'دسته: ' +
                (res?.category?.name || '—') +
                ' (' +
                (res?.count ?? 0) +
                ')'
            );

            const items = Array.isArray(res?.items) ? res.items : [];
            $('#catLoading').hide();

            if (!items.length) {
              $('#catEmpty').show();
              return;
            }

            const $list = $('#catList').show();

            items.forEach(function (item) {
              const $li = $('<li/>').css({
                padding: '.75rem .5rem',
                borderBottom: '1px solid #eee',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                gap: '.5rem'
              });

              const $left = $('<div/>').css({
                display: 'flex',
                flexDirection: 'column',
                gap: '.25rem'
              });

              const $title = $('<a/>', {
                href: item.url,
                text: item.title,
                title: item.title
              })
                .css({
                  color: '#0d6efd',
                  textDecoration: 'none',
                  fontWeight: '600'
                })
                .hover(
                  function () {
                    $(this).css('text-decoration', 'underline');
                  },
                  function () {
                    $(this).css('text-decoration', 'none');
                  }
                );

              const $date = $('<small/>', { text: item.date }).css({
                color: '#666'
              });

              $left.append($title, $date);

              const $go = $('<a/>', {
                href: item.url,
                text: 'مشاهده'
              }).css({
                padding: '.35rem .6rem',
                borderRadius: '8px',
                border: '1px solid #ddd',
                textDecoration: 'none'
              });

              $li.append($left, $go);
              $list.append($li);
            });
          } catch (err) {
            console.error('Parse/render error:', err);
            $('#catLoading').hide();
            $('#catEmpty').show().text('خطا در پردازش داده‌ها.');
          }
        })
        .fail(function (xhr, status, err) {
          console.error('AJAX fail:', status, err, xhr?.status, xhr?.responseText);
          $('#catLoading').hide();
          $('#catEmpty').show().text('خطا در دریافت لیست پست‌ها.');
        })
        .always(function () {
          if ($('#catLoading').is(':visible')) {
            $('#catLoading').hide();
            $('#catEmpty').show().text('عدم دریافت پاسخ از سرور.');
          }
        });
    });
  });
})();



  const scrollBtn = document.getElementById('scroll-toggle-btn');
  const scrollIcon = scrollBtn?.querySelector('i');

  function updateScrollBtn() {
    if (!chatBox || !scrollIcon || !scrollBtn) return;
    if (chatBox.scrollTop + chatBox.clientHeight >= chatBox.scrollHeight - 50) {
      // پایین چت → فلش بالا
      scrollIcon.classList.remove('fa-arrow-down');
      scrollIcon.classList.add('fa-arrow-up');
      scrollBtn.setAttribute('data-direction', 'up');
    } else if (chatBox.scrollTop <= 50) {
      // بالای چت → فلش پایین
      scrollIcon.classList.remove('fa-arrow-up');
      scrollIcon.classList.add('fa-arrow-down');
      scrollBtn.setAttribute('data-direction', 'down');
    }
  }

  if (chatBox && scrollBtn) {
    chatBox.addEventListener('scroll', updateScrollBtn);
    document.addEventListener('DOMContentLoaded', updateScrollBtn);

    scrollBtn.addEventListener('click', () => {
      if (!chatBox) return;
      if (scrollBtn.getAttribute('data-direction') === 'up') {
        chatBox.scrollTo({ top: 0, behavior: 'smooth' });
      } else {
        chatBox.scrollTo({ top: chatBox.scrollHeight, behavior: 'smooth' });
      }
    });
  }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const plusButton = document.getElementById('chatCreateToggle');
        const menu = document.getElementById('createMenu');
        const triggerWrapper = plusButton?.closest('.chat-action-trigger');
            const audioUploadTrigger = document.getElementById('audio-upload-trigger');
            const voiceFileInput = document.getElementById('voice-file-input');

        const toggleMenu = (visible) => {
            if (!menu) {
                return;
            }
            const shouldShow = typeof visible === 'boolean'
                ? visible
                : (menu.style.display === 'none' || menu.style.display === '');
            menu.style.display = shouldShow ? 'block' : 'none';
        };

        plusButton?.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            toggleMenu();
        });

        document.addEventListener('click', function (e) {
            if (!menu) {
                return;
            }
            const clickedInsideTrigger = triggerWrapper?.contains(e.target);
            const clickedInsideMenu = menu.contains(e.target);
            if (!clickedInsideTrigger && !clickedInsideMenu) {
                toggleMenu(false);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                toggleMenu(false);
            }
        });

        menu?.querySelectorAll('button').forEach(function (actionButton) {
            // اگر دکمه onclick دارد (مثل openBlogBox, openPollBox)، منو را ببند اما event را متوقف نکن
            if (actionButton.onclick || actionButton.getAttribute('onclick')) {
                actionButton.addEventListener('click', function (e) {
                    // فقط منو را ببند، event را متوقف نکن تا onclick اجرا شود
                    toggleMenu(false);
                });
            } else {
                // برای دکمه‌های دیگر (مثل audio-upload-trigger) که event handler جداگانه دارند
                actionButton.addEventListener('click', function () {
                    toggleMenu(false);
                });
            }
        });

        audioUploadTrigger?.addEventListener('click', function (e) {
            e.preventDefault();
            toggleMenu(false);
            voiceFileInput?.click();
        });

        // Handle create post button
        const createPostBtn = document.getElementById('create-post-btn');
        if (createPostBtn) {
            createPostBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu(false);
                // کمی تأخیر برای بستن منو قبل از باز کردن modal
                setTimeout(function() {
                    // بررسی وجود تابع در scope global
                    if (typeof window.openBlogBox === 'function') {
                        window.openBlogBox();
                    } else if (typeof openBlogBox === 'function') {
                        openBlogBox();
                    } else {
                        console.error('openBlogBox function not found. Available functions:', Object.keys(window).filter(k => k.includes('Blog') || k.includes('Poll')));
                    }
                }, 150);
            });
        }

        // Handle create poll button
        const createPollBtn = document.getElementById('create-poll-btn');
        if (createPollBtn) {
            createPollBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleMenu(false);
                // کمی تأخیر برای بستن منو قبل از باز کردن modal
                setTimeout(function() {
                    // بررسی وجود تابع در scope global
                    if (typeof window.openPollBox === 'function') {
                        window.openPollBox();
                    } else if (typeof openPollBox === 'function') {
                        openPollBox();
                    } else {
                        console.error('openPollBox function not found. Available functions:', Object.keys(window).filter(k => k.includes('Blog') || k.includes('Poll')));
                    }
                }, 150);
            });
        }

    });
</script>
  </div>
  </div>
  @include('groups.modals.election_form', compact('group'))
  @include('groups.modals.post_form', compact('group', 'categories'))
  @include('groups.modals.poll_form', compact('group'))
  
  @if($electionAvailable && isset($election) && $election)
    <div id="electionVotingOverlay" class="election-voting-overlay" style="display: none;">
      <div class="election-voting-overlay__backdrop" onclick="closeElectionBox()"></div>
      @include('groups.modals.election_modal', compact('group', 'election', 'selectedVotesInspector', 'selectedVotesManager', 'managersSorted', 'inspectorsSorted', 'managerCounts', 'inspectorCounts', 'groupSetting'))
    </div>
  @endif
  
  @if (session()->has('success'))
    <script>
      alert('{{ session()->get('success') }}')    
    </script>      
  @endif


@push('scripts')
<script>
    CKEDITOR.replace('post_editor', {
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

const elementPin = document.querySelector(".pinned-messages");
if (elementPin) elementPin.scrollTop = elementPin.scrollHeight;

  function openGroupEdit() {
    document.getElementById('groupEditFormBox').style.display = 'block';
    document.getElementById('back').style.display = 'block';
  }

  function cancelGroupEdit() {
    document.getElementById('groupEditFormBox').style.display = 'none';
    document.getElementById('back').style.display = 'none';
  }

  function closeAllModals() {
    document.getElementById('groupEditFormBox').style.display = 'none';
    document.getElementById('back').style.display = 'none';
    // ... existing modal close code ...
  }
</script>

<style>
    .recording-indicator {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #dc3545;
    }
    
    .recording-dot {
      width: 8px;
      height: 8px;
      background-color: #dc3545;
      border-radius: 50%;
      animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
      0% { transform: scale(1); opacity: 1; }
      50% { transform: scale(1.2); opacity: 0.7; }
      100% { transform: scale(1); opacity: 1; }
    }

    /* Voice Recorder Styles */
    #voice-record-btn {
      transition: all 0.3s ease;
    }

    #voice-record-btn:hover {
      transform: scale(1.05);
    }

    #voice-record-btn:active {
      transform: scale(0.95);
    }

    #voice-recording-modal {
      animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    #waveform-canvas {
      image-rendering: pixelated;
    }

    .pinned-messages {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      padding: 0.5rem;
      background: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
    }

    .pin {
      direction: rtl;
      background-color: #fff;
      display: flex;
      align-items: start;
      justify-content: space-between;
      padding: .5rem;
      border-radius: 0.5rem;
      box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      text-decoration: none;
      color: inherit;
    }

    .pin:hover {
      background-color: #f8f9fa;
    }

    .pin p {
      margin: 0;
      color: #666;
    }

    .pin i {
      font-size: 1.5rem;
      opacity: .7;
      color: #dc3545;
    }

    .reply-indicator {
        position: fixed;
        bottom: 60px;
        left: 0;
        right: 0;
        background: #f8f9fa;
        padding: 10px;
        border-top: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
        direction: rtl;
        width: calc(100% - 400px) !important;
    }
    #electionRedirect{
        width: 100% !important;
            background-color: #fffce9;

    }
    @media (max-width: 767px) {
      .reply-indicator{
        width: 100% !important;
      }
 

    }
        .chat-footer{
        
            width: 100%
    }
        @media (min-width: 767px) {

               .chat-footer{
        
            width: calc(100% - 25rem);
    }
      .election-card{
    width: calc(90% - 400px) !important;
    left: 5%;
    }
    }
    
.election-card{
    width: 100%;
    background-color: #fff;
      }
      
    .reply-info {
        display: flex;
        align-items: center;
        gap: 10px;
        direction: rtl;
    }

    .reply-content {
        color: #666;
        font-size: 0.9em;
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        direction: rtl;
    }

    .btn-cancel-reply {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 5px;
        margin-left: 10px;
    }

    .btn-cancel-reply:hover {
        color: #c82333;
    }

    .reply-box {
        direction: rtl;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        padding: 4px 8px;
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
        cursor: pointer;
    }

    .reply-box:hover {
        background: rgba(0, 0, 0, 0.1);
    }

    .reply-box .group-avatar {
        flex-shrink: 0;
    }

    .reply-box .group-avatar span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
    }

    .reply-box .reply-content {
        flex-grow: 1;
        overflow: hidden;
    }

    .reply-box .reply-sender {
        font-weight: bold;
        font-size: 0.9rem;
        margin-bottom: 2px;
    }

    .reply-box .reply-text {
        font-size: 0.8rem;
        color: #666;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

</style>
<script>
document.addEventListener('DOMContentLoaded', function(){
  // --- باز/بسته‌شدن پنل ---
  const wrap  = document.getElementById('gc-search-wrap');
  const input = document.getElementById('gc-search-input');
  const btn   = document.getElementById('btn-chat-search');
  const dd    = document.getElementById('gc-search-dd');
  const statusEl = dd?.querySelector('.gc-search-status');

  if (!wrap || !input || !btn || !dd) {
    // Search DOM elements may not exist on all pages - this is expected
    // console.warn('Search DOM missing', {wrap, input, btn, dd});
    return;
  }

  function openSearch(){ wrap.hidden = false; btn.setAttribute('aria-expanded','true'); setTimeout(()=>input.focus(), 10); }
  function closeSearch(){ wrap.hidden = true;  btn.setAttribute('aria-expanded','false'); }
  function toggleSearch(){ wrap.hidden ? openSearch() : closeSearch(); }

  btn.addEventListener('click', (e)=>{ e.stopPropagation(); toggleSearch(); });
  document.addEventListener('click', (e)=>{ const inside = e.target.closest('#gc-search-wrap') || e.target.closest('#btn-chat-search'); if (!inside) closeSearch(); });
  wrap.addEventListener('keydown', (e)=>{ if (e.key==='Escape'){ closeSearch(); btn.focus(); } });

  // هوک برای اسپینر آیکن/استاتوس
  window.__setSearching = function(on){
    statusEl.style.display = on ? 'flex' : 'none';
    btn.classList.toggle('searching', !!on);
  };
  window.__ensureSearchOpen = function(){ if (wrap.hidden) openSearch(); };

  // --- سرچ AJAX ---
  const csrf      = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
  const groupId   = {{ $group->id }};
  const listEl    = dd.querySelector('.gc-search-list');
  const moreBtn   = dd.querySelector('.gc-search-more');
  const clearBtn  = document.getElementById('gc-search-clear');

  let page = 1, loading = false, lastQuery = '', items = [], activeIndex = -1, hasMore = false;
  const openDD  = ()=> dd.hidden = false;
  const closeDD = ()=> { dd.hidden = true; activeIndex = -1; updateActive(); };

  function debounce(fn, ms=300){ let t; return (...a)=>{ clearTimeout(t); t=setTimeout(()=>fn(...a), ms); } }
  function setStatus(txt){ statusEl.textContent = txt; statusEl.style.display = txt ? 'flex' : 'none'; }
  function setMore(vis){ moreBtn.hidden = !vis; }

  function renderList(){
    listEl.innerHTML = '';
    items.forEach((it, idx) => {
      const li   = document.createElement('li');
      li.className = 'gc-search-item' + (idx===activeIndex ? ' active' : '');
      li.dataset.index = idx;

      const type = document.createElement('div');
      type.className = 'type';
      type.textContent = it.type === 'message' ? 'پیام' : it.type === 'post' ? 'پست' : 'نظرسنجی';

      const meta  = document.createElement('div'); meta.className = 'meta';
      const title = document.createElement('div'); title.className = 'title'; title.textContent = it.title || (it.type==='post' ? 'پست' : it.type==='poll' ? 'نظرسنجی' : 'کاربر');
      const snip  = document.createElement('div'); snip.className  = 'snip';  snip.innerHTML = it.snippet || '';
      const date  = document.createElement('small'); date.style.color = '#6b7280'; date.textContent = it.date || '';
      meta.appendChild(title); meta.appendChild(snip); meta.appendChild(date);
      li.appendChild(type); li.appendChild(meta);
      li.addEventListener('click', ()=> goTo(it));
      listEl.appendChild(li);
    });
  }
  function updateActive(){ listEl.querySelectorAll('.gc-search-item').forEach((el,i)=> el.classList.toggle('active', i===activeIndex)); }
  function goTo(it){ closeDD(); if (!it?.url) return; location.hash = it.url; }

  async function fetchPage(reset=false){
    if (loading) return;
    loading = true;
    window.__setSearching(true);
    setStatus('در حال جستجو…');
    if (reset) { items = []; page = 1; listEl.innerHTML=''; setMore(false); }

    try{
      const url = new URL(`{{ url('/groups') }}/${groupId}/search`, window.location.origin);
      url.searchParams.set('q', lastQuery);
      url.searchParams.set('page', page);
      url.searchParams.set('limit', 20);

      const res = await fetch(url.toString(), {
        method: 'GET',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        credentials: 'same-origin'
      });

      if (!res.ok){
        // برای کمک به دیباگ
        const txt = await res.text();
        console.error('Search HTTP Error', res.status, txt);
        throw new Error('HTTP '+res.status);
      }

      const data = await res.json();
      const newItems = Array.isArray(data?.items) ? data.items : [];
      hasMore = !!data?.has_more;

      items = items.concat(newItems);
      renderList();
      setStatus(newItems.length ? '' : (page===1 ? 'چیزی پیدا نشد.' : ''));
      setMore(hasMore);
      openDD();
      window.__ensureSearchOpen();
    } catch(e){
      console.error('Search fetch error', e);
      setStatus('خطا در دریافت نتایج');
      openDD();
      window.__ensureSearchOpen();
    } finally{
      loading = false;
      window.__setSearching(false);
    }
  }

  const onInput = debounce(()=>{
    const q = input.value.trim();
    if (!q){ closeDD(); return; }
    lastQuery = q;
    fetchPage(true);
  }, 300);

  input.addEventListener('input', onInput);
  input.addEventListener('focus', ()=>{
    if (items.length) openDD();
    else if (input.value.trim()){ lastQuery = input.value.trim(); fetchPage(true); }
  });
  clearBtn.addEventListener('click', ()=>{
    input.value=''; closeDD(); items=[]; listEl.innerHTML='';
  });
  moreBtn.addEventListener('click', ()=>{
    if (!hasMore) return; page += 1; fetchPage(false);
  });

  // بستن dropdown با کلیک بیرون از سرچ‌بار
  document.addEventListener('click', (e)=>{
    const box = e.target.closest('.gc-searchbar');
    if (!box) closeDD();
  });

  // ناوبری کیبورد
  input.addEventListener('keydown', (e)=>{
    if (dd.hidden) return;
    const max = items.length - 1;
    if (e.key === 'ArrowDown'){ e.preventDefault(); activeIndex = activeIndex < max ? activeIndex+1 : 0; updateActive(); }
    else if (e.key === 'ArrowUp'){ e.preventDefault(); activeIndex = activeIndex > 0 ? activeIndex-1 : max; updateActive(); }
    else if (e.key === 'Enter'){ e.preventDefault(); if (activeIndex>=0) goTo(items[activeIndex]); }
    else if (e.key === 'Escape'){ closeDD(); }
  });
});
</script>

<script>

  // Add pin/unpin functionality
  function pinMessage(messageId) {
    fetch(`/groups/messages/${messageId}/pin`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        location.reload();
      } else {
        alert(data.message);
      }
    });
  }

  function unpinMessage(messageId) {
    fetch(`/groups/messages/${messageId}/unpin`, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.status === 'success') {
        location.reload();
      } else {
        alert(data.message);
      }
    });
  }
</script>

<script>
document.addEventListener('click', function (e) {
  // همه‌ی منوهای باز
  document.querySelectorAll('details.menu-wrapper[open]').forEach(function (d) {
    // اگر کلیک بیرون از همین منو بوده، ببند
    if (!d.contains(e.target)) d.removeAttribute('open');
  });
});

// جلوگیری از بسته‌شدن هنگام کلیک داخل منو
document.addEventListener('click', function (e) {
  const dropdown = e.target.closest('.menu-dropdown');
  if (dropdown) {
    e.stopPropagation();
  }
});

// با ESC هم ببند
document.addEventListener('keydown', function (e) {
  if (e.key === 'Escape') {
    document.querySelectorAll('details.menu-wrapper[open]').forEach(d => d.removeAttribute('open'));
  }
});
</script>

{{-- Modal مدیریت اعضا - همیشه در DOM باشد اما فقط برای مدیران قابل مشاهده --}}
<div id="manageMembersModal" class="modal-shell" style="display: none;" dir="rtl" onclick="handleModalClick(event, 'manageMembersModal')">
    <div class="modal-shell__dialog" onclick="event.stopPropagation()">
        <div class="modal-shell__header">
            <h3 class="modal-shell__title">
                <i class="fas fa-users-cog me-2 text-blue-500"></i>
                مدیریت اعضای گروه
            </h3>
            <button type="button" class="modal-shell__close" onclick="closeManageMembersModal()">×</button>
        </div>

        <div class="modal-shell__form">
            <div id="members-loading" class="text-center py-8" style="display: none;">
                <i class="fas fa-spinner fa-spin text-2xl text-blue-500"></i>
                <p class="mt-2 text-slate-600">در حال بارگذاری...</p>
            </div>

            <div id="members-error" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4" style="display: none;">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span id="members-error-text"></span>
            </div>

            <div id="members-list" style="max-height: 400px; overflow-y: auto; padding: 0.5rem 0; min-height: 100px; display: block; visibility: visible;">
                <!-- لیست اعضا اینجا نمایش داده می‌شود -->
            </div>
        </div>
    </div>
</div>

{{-- Modal مدیریت گزارش‌ها --}}
@if(($yourRole ?? 0) == 3)
<div id="manageReportsModal" class="modal-shell" style="display: none;" dir="rtl" onclick="handleModalClick(event, 'manageReportsModal')">
    <div class="modal-shell__dialog" onclick="event.stopPropagation()" style="max-width: 900px; width: 90vw;">
        <div class="modal-shell__header">
            <h3 class="modal-shell__title">
                <i class="fas fa-flag me-2 text-orange-500"></i>
                مدیریت گزارش‌های پیام
            </h3>
            <button type="button" class="modal-shell__close" onclick="closeManageReportsModal()">×</button>
        </div>

        <div class="modal-shell__form">
            <div id="reports-loading" class="text-center py-8" style="display: none;">
                <i class="fas fa-spinner fa-spin text-2xl text-orange-500"></i>
                <p class="mt-2 text-slate-600">در حال بارگذاری...</p>
            </div>

            <div id="reports-error" class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4" style="display: none;">
                <i class="fas fa-exclamation-circle ml-2"></i>
                <span id="reports-error-text"></span>
            </div>

            <div id="reports-list" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- لیست گزارش‌ها اینجا نمایش داده می‌شود -->
            </div>
        </div>
    </div>
</div>
@endif

</div>
@endsection
