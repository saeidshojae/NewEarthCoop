<div class="chat-header d-flex justify-content-between align-items-center" style="position:relative;">
  <div class="d-flex align-items-center gap-2" style="flex-direction: row-reverse;">
    <div class="group-avatar">
      @if($group->avatar)
        <img src="{{ asset('images/groups/' . $group->avatar) }}" alt="{{ $group->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
      @else
        <span>{{ strtoupper(substr($group->name, 0, 1)) }}</span>
      @endif
    </div>
    <div class="group-info text-end">
      <h4 style="cursor: pointer;" onclick="openGroupInfo()">{{ $group->name }} </h4>
      <p style="display:flex;flex-direction:row-reverse;">{{ $group->userCount()}} <span style="margin-right: 5px;"> عضو </span></p>
    </div>
  </div>

  <div style="display:flex; align-items:center; flex-direction:row-reverse; gap:.25rem">
         
        <button class="border-0" type="button" onclick="openElectionBox()" style='background-color: transparent; cursor: pointer; padding: 0;    margin-top: -.7rem;'>
         <img src='{{ asset("/images/ballot.png") }}' style='    width: 1.3rem;'>
        </button>
        
    <!-- فقط یک آیکن جستجو -->
    <button id="btn-chat-search" class="border-0 btn-chat-icon" type="button" aria-expanded="false" aria-controls="gc-search-wrap" title="جستجو" style='    padding: 0;
    margin-top: -0.1rem;'>
      <i class="fas fa-magnifying-glass"></i>
    </button>

    <div class="dropdown" style="position:relative; top:-2px;">
      <button class="border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-ellipsis-v"></i>
      </button>


      
      <ul class="dropdown-menu dropdown-menu-end text-end">
        {{-- منوی شما بدون تغییر --}}
        @if($group->location_level != 10)
          @if ($yourRole === 1 OR $yourRole === 3 OR $yourRole === 2)
            @if ($yourRole === 3 OR $yourRole === 2)
              <li><a class="dropdown-item" onclick="openGroupEdit()" href="#">ویرایش گروه</a></li>
              <li><a class="dropdown-item" id="addUserButton" href="#">اضافه کردن کاربر مهمان به گروه</a></li>
              <li><a class="dropdown-item" id="addChatRequestButton" href="#">درخواست چت به مدیران</a></li>
              <li><a class="dropdown-item" onclick="openElection2Box()" href="#">➕ افزودن انتخابات</a></li>
              <li><a class="dropdown-item" href="{{ route('groups.open', $group) }}">{{ $group->is_open == 0 ? 'فعال کردن نشست' : 'غیرفعال کردن نشست' }}</a></li>
            @endif
          @endif
          <li><a class="dropdown-item" href="{{ route('groups.logout', $group->id) }}">❌ خروج از گروه</a></li>
        @else
          <li><a class="dropdown-item" href="#" onclick="openChatSearch()">🔍 جستجو در چت</a></li>
          <li><a class="dropdown-item" href="#" onclick="clearChatHistory()">🗑️ پاک کردن تاریخچه چت</a></li>
          <li><a class="dropdown-item" href="#" onclick="deleteChat()">❌ حذف چت</a></li>
          <li><a class="dropdown-item" href="#" onclick="reportUser()">🚩 گزارش و ریپورت کاربر</a></li>
        @endif
      </ul>
    </div>
  </div>

  <!-- پنل سرچ؛ یک‌بار و پیش‌فرض بسته -->
  <div id="gc-search-wrap" class="gc-search-wrap" hidden>
    <div class="gc-searchbar">
      <i class="fa fa-magnifying-glass"></i>
      <input id="gc-search-input" type="text" placeholder="جستجو در پیام‌ها، پست‌ها و نظرسنجی‌ها…" autocomplete="off" />
      <button id="gc-search-clear" title="پاک‌کردن"><i class="fa fa-xmark"></i></button>

      <div id="gc-search-dd" class="gc-search-dropdown" hidden>
        <div class="gc-search-status" style="display:none"><span class="gc-spin"></span> در حال جستجو…</div>
        <ul class="gc-search-list"></ul>
        <button class="gc-search-more" hidden>نتایج بیشتر</button>
      </div>
    </div>
  </div>
</div>
