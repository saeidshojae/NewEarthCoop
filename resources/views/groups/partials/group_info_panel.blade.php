<div id="groupInfoPanel" style="position: fixed; top: 0; width: 100%; max-width: 400px; height: 100vh; background-color: #fff; box-shadow: -2px 0 6px rgba(0,0,0,0.1); z-index: 1000; transition: right 0.3s ease; overflow-y: auto;">
    <style>
                  .chat-body{
              width: calc(100% - 400px) !important;
            }

            .chat-header{
              width: calc(100% - 400px) !important;
            }

            .navbar{
              width: calc(100% - 400px) !important;
            }
            #exitNavbar{
              display: none;
            }
            #groupInfoPanel {
                right: 0;
            }
            #chatForm{
              width: calc(100% - 400px) !important;
            }

            #pollOptionsBox{
              right: auto;
              width: calc(90% - 400px);
              margin-left: 5%;
              direction: rtl
            }
            #postFormBox{
              right: auto;
              width: calc(90% - 400px);
              margin-left: 5%;
              direction: rtl
            }

            #electionOptionsBox{
              right: auto;
              width: calc(90% - 400px);
              margin-left: 5%;
              direction: rtl
            }

        @media (max-width: 767px) {
            #groupInfoPanel {
                right: -100%;
            }

            .chat-body{
              width: 100% !important;
            }

            .chat-header{
              width: 100% !important;
            }

            .navbar{
              width: 100% !important;
            }

            #exitNavbar{
              display: block;
            }
            #chatForm{
              width: 100% !important;
            }

            #pollOptionsBox{
              right: auto;
              width: 90%;
              margin-left: 5%;
            }
            #postFormBox{
              right: auto;
              width: 90%;
              margin-left: 5%;
            }

            #electionOptionsBox{
              right: auto;
              width: 90%;
              margin-left: 5%;
            }
        }

  .groups-list {
    max-width: 800px;
    margin: 0 auto;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }
  
  .group-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.2s;
  }
  
  .group-item:last-child {
    border-bottom: none;
  }
  
  .group-item:hover {
    background-color: #f5f5f5;
  }
  
  .group-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-left: 15px;
    overflow: hidden;
    background: #e0e0e0;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .group-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .default-avatar {
    width: 100%;
    height: 100%;
    background: #3f7d58d1 !important;
    background: linear-gradient(90deg, rgba(63, 125, 88, .8) 0%, rgba(122, 207, 156, .8) 100%) !important;    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    font-weight: bold;
  }
  
  .group-info {
    flex: 1;
  }
  
  .group-main-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
  }
  
  .group-name {
    font-weight: 500;
    font-size: 16px;
    color: #212121;
  }
  
  .group-name a {
    color: #212121;
    text-decoration: none;
  }
  
  .group-members-count {
    color: #757575;
    font-size: 13px;
  }
  
  .group-secondary-info {
    display: flex;
    justify-content: space-between;
    color: #757575;
    font-size: 13px;
  }
  
  .member-role {
    color: #2196F3;
  }
  
  .text-muted {
    color: #757575;
  }
  
  .manager-info {
    color: #757575;
  }
  
  .text-primary {
    color: #2196F3;
    text-decoration: none;
  }
  
  .text-primary:hover {
    text-decoration: underline;
  }

  .tabs{
    overflow: scroll;

  }

  /* مودال */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
}

/* محتوای مودال */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* دکمه بستن */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* استایل مودال */
.modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%; 
    height: 100%; 
    overflow: auto; 
    background-color: rgb(0,0,0); 
    background-color: rgba(0,0,0,0.4); 
    padding-top: 60px;
}

/* محتوای مودال */
.modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    border-radius: 10px;
    max-height: 80vh;
    overflow-y: auto;
}

/* دکمه بستن */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* جعبه جستجو */
.input-group {
    margin-bottom: 1rem;
}

.input-group .form-control {
    padding-right: 2rem;
    border-radius: 0.5rem;
}

/* استایل برای هر مورد در لیست */
.manager-item {
    transition: background-color 0.2s ease;
}

.manager-item:hover {
    background-color: #f0f0f0;
}

.manager-item span {
    font-weight: bold;
}

/* استایل کلی برای دکمه‌ها */
button {
    border-radius: 0.5rem;
}

/* استایل برای اسکرول داخل لیست */
#managerList {
    max-height: 300px;
    overflow-y: auto;
}

.card{
        width: 100%;
    margin-top: .5rem;
}



  </style>
    <div style="padding: 1rem; direction: rtl;">
      <button onclick="closeGroupInfo()" id="exitNavbar" style="float: left; border: none; background: transparent; font-size: 1.2rem; position: absolute; left: 1rem;">✖</button>
      
      <div style="text-align: center; margin-top: 2rem; display: flex; flex-direction: column; align-items: center;">
        <div class="group-avatar" style="width: 6rem; height: 6rem; font-size: 3rem; margin: 0;">
          
          @if($group->avatar)
          <img src="{{ asset('images/groups/' . $group->avatar) }}" alt="{{ $group->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
      @else
      <span>{{ strtoupper(substr($group->location_level, 0, 1)) }}</span>
            @endif
        </div>
        
        <h4 style="margin-top: 1rem;">{{ $group->name }}</h4>
        <p>{{ $group->userCount() }} عضو + {{ $group->guestsCount() }} مهمان</p>
        <p>{{ $group->description }}</p>
        @if($yourRole === 3 OR $yourRole === 2)


            <!-- Modal -->
<div id="userSearchModal" class="modal" style="display: none;">
  <div class="modal-content position-relative">
    <span class="close">&times;</span>
    
    <h2>جستجوی کاربران</h2>

    <input type="text" id="searchUsers" placeholder="کد کاربری، نام، ایمیل یا شماره تماس کاربر..." class="form-control" autocomplete="off">
    <ul id="searchUserResults" class="list-group position-absolute w-100 mt-1" style="z-index: 1000; display: none;"></ul>

    <input type="number" id="hoursUser" placeholder="چند ساعت بتواند در گروه باشد؟" class="form-control mt-3">
     <div class="d-flex gap-2">
    <button id="addUsersToGroup" class="btn btn-success mt-2">افزودن به گروه</button>
            <button type="button" class="btn btn-secondary mt-2" onclick="cancelAddGuests()" style='    background-color: red !important;'>لغو</button>
        </div>
  </div>
</div>

<script>
    function cancelAddGuests(){
        document.querySelector('#userSearchModal').style='display: none'
    }
    
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchUsers');
    const resultBox = document.getElementById('searchUserResults');
    let selectedUserId = null;
    

    
    searchInput.addEventListener('input', function () {
        const query = this.value.trim();
        if (query.length < 2) {
            resultBox.style.display = 'none';
            resultBox.innerHTML = '';
            return;
        }

        fetch(`/users/search?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                resultBox.innerHTML = '';
                if (users.length) {
                    users.forEach(user => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item', 'list-group-item-action');
                        li.textContent = `${user.first_name} ${user.last_name} (${user.email})`;
                        li.addEventListener('click', () => {
                            searchInput.value = user.email;
                            selectedUserId = user.id;
                            resultBox.innerHTML = '';
                            resultBox.style.display = 'none';
                        });
                        resultBox.appendChild(li);
                    });
                    resultBox.style.display = 'block';
                } else {
                    resultBox.innerHTML = '<li class="list-group-item disabled">کاربری یافت نشد</li>';
                    resultBox.style.display = 'block';
                }
            });
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !resultBox.contains(e.target)) {
            resultBox.style.display = 'none';
        }
    });

    document.getElementById('addUsersToGroup').addEventListener('click', function () {
        const hours = document.getElementById('hoursUser').value;
        if (!selectedUserId || !hours) {
            alert("لطفاً کاربر را انتخاب و مدت ساعت را وارد کنید.");
            return;
        }

        // ارسال اطلاعات با AJAX (یا اضافه کردن به فرم یا لیست)
        fetch('/groups/add-user', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({
                user_id: selectedUserId,
                group_id: {{ $group->id }},
                hours: hours
            })
        })
        .then(res => res.json())
        .then(data => {
            alert("کاربر با موفقیت اضافه شد");
            selectedUserId = null;
            searchInput.value = '';
            document.getElementById('hoursUser').value = '';
            // optionally close modal
        });
    });

});
</script>


  <!-- دکمه درخواست چت -->
<!-- Modal Chat Request -->
<div id="chatRequestModal" class="modal" style="display: none;">
  <div class="modal-content">
      <h2>درخواست چت با مدیران دیگر گروه‌ها</h2>

      <!-- جعبه جستجو -->
      <div class="input-group mb-3">
          <input type="text" id="searchManagers" class="form-control" placeholder="جستجوی مدیران..." style="direction: rtl;">
      </div>
        <style>
            .chat-request{
                width: 20% !important;
            }
        </style>
      <!-- لیست مدیران -->
      <ul id="managerList" style="list-style: none; padding: 0; overflow-y: auto; max-height: 300px;">
        @php
            $managers = \App\Models\GroupUser::where('role', 3)->get();
        @endphp
        @foreach ($managers as $manager)
          @if (auth()->user()->id !== $manager->user->id)
              <li class="manager-item" style="border: 1px solid #888888d0; padding: 0 .5rem; border-radius: .5rem; margin: .5rem 0; display: flex; justify-content: space-between; align-items: center;">
                  <span>{{ $manager->user->first_name }} {{ $manager->user->last_name }} ({{ $manager->group->name }})</span>
                  @include('chat_request', ['user' => $manager->user, 'request_to_group' => $manager->group_id])
              </li>
          @endif
        @endforeach
      </ul>
           <div class="d-flex gap-2">
            <button type="button" class="btn btn-secondary mt-2" onclick="cancelManagerChat()" style='    background-color: red !important;'>لغو</button>
        </div>
  </div>
</div>
    <script>
        function cancelManagerChat(){
            document.querySelector('#chatRequestModal').style='display: none'
        }
    </script>
    
    
    

        
     @include('chat_request', ['user' => auth()->user()])
     
        @endif

     @foreach ($group2->elections as $item)
          @include('groups.partials.election', ['item' => $item, 'side' => true])
        @endforeach
      </div>
  
      <hr>
      {{-- Tabs --}}
      <div class="tabs">
        <div class="tab active" data-tab="grooup" style="display: flex">گروه <span>ها</span></div>
        @if($group->location_level != 10)
          <div class="tab" data-tab="members">اعضا</div>
          <div class="tab" data-tab="admins">مدیران</div>
          <div class="tab" data-tab="post">پست</div>
          <div class="tab" data-tab="poll">نظرسنجی</div>
          <div class="tab" data-tab="election">انتخابات</div>
        @endif
      </div>
  
      {{-- Tab Grooup --}}
      <div class="tab-content active" id="grooup" style="padding: .5rem 1rem;">
        <div class="search-box mb-3">
          <div class="input-group">
            <select class="form-select" id="searchType" style="direction: rtl;">
              <option value="name">جستجو در نام گروه</option>
              <option value="content">جستجو در محتوا</option>
            </select>
            <input type="text" id="groupSearch" class="form-control" placeholder="جستجوی گروه..." style="direction: rtl;">
          </div>
        </div>
        <ul style="list-style: none; padding: 0;" id="groupsList">
          
          @foreach (auth()->user()->groups()->orderBy('last_activity_at', 'desc')->get() as $group)
          
          @php
            $currentUser = auth()->id();
            $pivot = \App\Models\GroupUser::where('group_id', $group->id)->where('user_id', $currentUser)->first();
      
            $locationApproved = true;
            if ($group->address_id !== null) {
                $level = $group->location_level;
                if (!in_array($level, ['continent', 'country', 'province', 'county', 'section', 'city'])) {
                    $modelMap = [
                        'region' => \App\Models\Region::class,
                        'neighborhood' => \App\Models\Neighborhood::class,
                        'street' => \App\Models\Street::class,
                        'alley' => \App\Models\Alley::class,
                    ];
                    $model = $modelMap[$level] ?? null;
                    if ($model) {
                        $instance = $model::find($group->address_id);
                        if ($instance && $instance->status == 0) {
                            $locationApproved = false;
                        }
                    }
                }
            }
      
            $specialtyApproved = true;
            if (($group->specialty && $group->specialty->status == 0) ||
                ($group->experience && $group->experience->status == 0)) {
                $specialtyApproved = false;
            }
          @endphp
      
          @if($pivot)
            @php
              $memberRole = match($pivot->role) {
                0 => 'ناظر',
                1 => 'فعال',
                2 => 'بازرس',
                3 => 'مدیر',
                4 => 'مهمان',
                5 => 'فعال ۲',
              };
      
              $inspectors = $group->users->filter(fn($u) => $u->pivot->role == 2);
              $managers = $group->users->filter(fn($u) => $u->pivot->role == 3);
            @endphp
      
            <div class="group-item" data-level="{{ $group->location_level }}" data-group-id="{{ $group->id }}">
              <div class="group-avatar">
                @if($group->avatar)
                  <img src="{{ asset('images/groups/' . $group->avatar) }}" alt="{{ $group->name }}">
                @else
                  <div class="default-avatar">{{ substr($group->name, 0, 2) }}</div>
                @endif
              </div>
              
              <div class="group-info">
                <div class="group-main-info">
                  <div class="group-name">
                    @if($locationApproved && $specialtyApproved && $pivot->status == 1)
                      <a href="{{ route('groups.chat', $group) }}">{{ $group->name }}</a>
                    @else
                      <span class="text-muted">{{ $group->name }} (در انتظار تأیید)</span>
                    @endif
                  </div>
                  <div class="group-members-count">{{ $group->userCount() }} عضو</div>
                </div>

                
                <div class="group-secondary-info">
                  <div class="member-role">
                    @if($pivot->status == 1)
                      <span>{{ $memberRole }}</span>
                    @else
                      <span>
                        خارج شده <a href="{{ route('groups.relogout', $group) }}" class="text-primary">بازگردانی</a>
                      </span>
                    @endif
                  </div>
                </div>

                <div class="group-content" style="display: none;">
                  @php
                    $latestBlogs = $group->blogs()->latest()->take(5)->get();
                    $latestComments = $group->blogs()->with('comments')->get()->pluck('comments')->flatten()->take(5);
                    $latestPolls = $group->polls()->latest()->take(5)->get();
                  @endphp
                  
                  @foreach($latestBlogs as $blog)
                    {{ $blog->title }} {{ $blog->content }}
                  @endforeach
                  
                  @foreach($latestComments as $comment)
                    {{ $comment->message }}
                  @endforeach
                  
                  @foreach($latestPolls as $poll)
                    {{ $poll->title }} {{ $poll->description }}
                  @endforeach
                </div>
              </div>
            </div>
          @endif
          @endforeach
        </ul>
      </div>
     <div class="tab-content" id="members" style="padding: .5rem 1rem;">
  {{-- جعبه جستجو + شمارنده --}}
  <div style="display:flex; gap:.5rem; align-items:center; margin:.5rem 0; direction:rtl;">
    <input id="membersSearch" type="text" class="form-control"
           placeholder="جستجوی عضو (نام، نقش، ایمیل)..."
           style="max-width:420px;">
  </div>

  <ul id="membersList" style="list-style: none; padding: 0;">
    @php
      $userMemberList = \App\Models\GroupUser::where('group_id', $group2->id ?? 0)
                          ->where('status', 1)
                          ->with('user') // برای جلوگیری از N+1
                          ->get();
    @endphp

    @foreach ($userMemberList as $user)
      @php
        $person = $user->user; // ممکنه null باشه
        $first  = $person->first_name ?? '';
        $last   = $person->last_name ?? '';
        $full   = trim($first.' '.$last) ?: '—';
        $email  = $person->email ?? '';
        $initial= strtoupper(substr($email ?: ($group2->name ?? 'U'), 0, 1));

        $fColor = rand(1,255);
        $sColor = rand(1,255);
        $tColor = rand(1,255);

        $memberRole = match((int)($user->role ?? -1)) {
          0 => 'ناظر',
          1 => 'فعال',
          2 => 'بازرس',
          3 => 'مدیر',
          4 => 'مهمان',
          5 => 'فعال ۲',
          default => 'نقش ناشناخته'
        };

        // تاریخ انقضا (اختیاری)
        $expiredHuman = null;
        if (!empty($user->expired)) {
          try { $expiredHuman = \Carbon\Carbon::parse($user->expired)->diffForHumans(); } catch (\Exception $e) {}
        }

        $profileUrl = $person?->id ? route('profile.member.show', $person->id) : '#';
        $isOnline = method_exists($person, 'isOnline') ? (bool)$person->isOnline() : false;
      @endphp

      <li class="member-item"
          data-name="{{ $full }}"
          data-role="{{ $memberRole }}"
          data-email="{{ $email }}"
          style="margin:.5rem 0; display:flex; align-items:center;">

        <div class="group-avatar"
             style="width:2rem; height:2rem; font-size:.7rem; margin:0; position:relative;
                    background-color: rgba({{ $fColor }}, {{ $sColor }}, {{ $tColor }}, .1);
                    color: rgb({{ $fColor }}, {{ $sColor }}, {{ $tColor }});">
          <span>{{ $initial }}</span>
          <div class="online-status"
               style="position:absolute; bottom:-2px; right:-2px; width:10px; height:10px; border-radius:50%;
                      background-color: {{ $isOnline ? '#4CAF50' : '#9E9E9E' }}; border:2px solid #fff;">
          </div>
        </div>

        <a style="margin:0; margin-right:.5rem;" href="{{ $profileUrl }}">
          {{ $full }}
          <span>
            ({{ $memberRole }})
            @if($expiredHuman) {{ $expiredHuman }} @endif
          </span>
        </a>

        @if(($yourRole ?? null) == 3 && in_array((int)($user->role ?? -1), [0,5], true) && $person?->id)
          <div class="group-main-info">
            <a href='{{ route('change-user-role', [ $person->id, $group2->id ]) }}'
               class="group-members-count"
               style="background-color:#459f96; margin-right:.5rem; padding:.1rem 1rem; color:#fff; border-radius:.4rem;">
              تغییر نقش
            </a>
          </div>
        @endif
      </li>
    @endforeach
  </ul>
</div>

{{-- فیلتر زنده اعضا --}}
<script>
(function(){
  const $input = document.getElementById('membersSearch');
  const $list  = document.getElementById('membersList');
  const $count = document.getElementById('membersCount');

  if(!$input || !$list) return;

  const items = Array.from($list.querySelectorAll('.member-item'));

  // نمایش شمارنده اولیه
  updateCount(items.length, items.length);

  // دی‌بونس سریع
  let t = null;
  $input.addEventListener('input', function(){
    clearTimeout(t);
    t = setTimeout(applyFilter, 120);
  });

  function norm(s){
    return (s || '')
      .toString()
      .trim()
      .toLowerCase()
      // نرمال‌سازی ساده حروف فارسی/عربی
      .replace(/[\u064A\u06CC]/g, 'ی')
      .replace(/[\u0643\u06A9]/g, 'ک');
  }

  function applyFilter(){
    const q = norm($input.value);
    let shown = 0;

    if(!q){
      items.forEach(li => li.style.display = '');
      updateCount(items.length, items.length);
      return;
    }

    items.forEach(li => {
      const name  = norm(li.getAttribute('data-name'));
      const role  = norm(li.getAttribute('data-role'));
      const email = norm(li.getAttribute('data-email'));
      const hit = name.includes(q) || role.includes(q) || email.includes(q);
      li.style.display = hit ? '' : 'none';
      if (hit) shown++;
    });

    updateCount(shown, items.length);
  }

  function updateCount(shown, total){
    if($count) $count.textContent = `نمایش ${shown} از ${total}`;
  }
})();
</script>

    
    

                        {{-- Tab Members --}}
            <div class="tab-content" id="admins" style="padding: .5rem 1rem;">
              <ul style="list-style: none; padding: 0;">
                @foreach ($group2->users()->withPivot(['role', 'status'])->whereIn('role', [2, 3])->get() as $userr)
                  @php
                    $fColor = rand(1, 255);
                    $sColor = rand(1, 255);
                    $tColor = rand(1, 255);
                  @endphp
                  <li style="margin: .5rem 0; display: flex; align-items: center;">
                    <div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .7rem; margin: 0; background-color: rgba({{ $fColor }}, {{ $sColor }}, {{ $tColor }}, .1); color: rgb({{ $fColor }}, {{ $sColor }}, {{ $tColor }}); position: relative;">
                      <span>{{ strtoupper(substr($userr->email, 0, 1)) }}</span>
                      <div class="online-status" style="position: absolute; bottom: -2px; right: -2px; width: 10px; height: 10px; border-radius: 50%; background-color: {{ $userr->isOnline() ? '#4CAF50' : '#9E9E9E' }}; border: 2px solid #fff;"></div>
                    </div>
                    <a style="margin: 0; margin-right: .5rem;" href='{{ route('profile.member.show', $userr) }}'>
                      {{ $userr->first_name }} {{ $userr->last_name }}
                      @php
                        $memberRole = match($userr->pivot->role) {
                          0 => 'ناظر',
                          1 => 'فعال',
                          2 => 'بازرس',
                          3 => 'مدیر',
                          4 => 'مهمان',
                          5 => 'فعال ۲',
                        };
                      @endphp
                      <span>({{ $memberRole }})</span>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
  
      {{-- Tab Posts --}}
      <div class="tab-content" id="post" style="padding: .5rem 1rem;">
        <ul style="display: flex; justify-content: space-between; font-size: .8rem; padding: 0; list-style: none;" class="tags">
          <li @if(isset($_GET['filter']) && $_GET['filter'] == 'most-like') style='background-color: #2451666e;' @endif ><a href="{{ route('groups.chat', [$group2->id, 'filter' => 'most-like']) }}">بیشترین لایک</a></li>
          <li @if(isset($_GET['filter']) && $_GET['filter'] == 'most-dislike') style='background-color: #2451666e;' @endif><a href="{{ route('groups.chat', [$group2->id, 'filter' => 'most-dislike']) }}">بیشترین دیسلایک</a></li>
          <li @if(isset($_GET['filter']) && $_GET['filter'] == 'most-comment') style='background-color: #2451666e;' @endif><a href="{{ route('groups.chat', [$group2->id, 'filter' => 'most-comment']) }}">بیشترین نظر</a></li>
          @foreach (\App\Models\Category::all() as $category)
            <li @if(isset($_GET['filter']) && $_GET['filter'] == 'c-' . $category->id) style='background-color: #2451666e;' @endif><a href="{{ route('groups.chat', [$group2->id, 'filter' => 'c-' . $category->id]) }}">{{ 'دسته ' . $category->name }}</a></li>
          @endforeach

        </ul>

        <hr>
@php
  $blogsQuery = \App\Models\Blog::where('group_id', $group2->id)
      ->with(['user.groupUser' => function($query) use ($group) {
          $query->where('group_id', $group->id);
      }, 'comments', 'reactions'])
      ->withCount(['comments', 'likes as likes_count', 'dislikes as dislikes_count']);

  // فیلترها بر اساس query string
  $filter = request()->get('filter');

  if ($filter == 'most-like') {
      $blogsQuery->orderByDesc('likes_count');
  } elseif ($filter == 'most-dislike') {
      $blogsQuery->orderByDesc('dislikes_count');
  } elseif ($filter == 'most-comment') {
      $blogsQuery->orderByDesc('comments_count');
  } elseif (Str::startsWith($filter, 'c-')) {
      $categoryId = (int) Str::after($filter, 'c-');
      $blogsQuery->where('category_id', $categoryId);
  } else {
      $blogsQuery->latest(); // پیش‌فرض: جدیدترین پست‌ها
  }

  $blogs = $blogsQuery->get();
@endphp
        @foreach ($blogs as $item)
             @if ($item->img != null)
    @php
        $type = explode('/', $item->file_type)[0];
    @endphp

    @if($type === 'image')
        <img src="{{ asset('images/blogs/' . $item->img) }}" style="width: 100%">
    @elseif($type === 'video')
        <video controls style="width: 100%">
            <source src="{{ asset('images/blogs/' . $item->img) }}" type="{{ $item->file_type }}">
        </video>
    @elseif($type === 'audio')
        <audio controls>
            <source src="{{ asset('images/blogs/' . $item->img) }}" type="{{ $item->file_type }}">
        </audio>
    @endif
    @endif

          <h3 style="text-align: center">{{ $item->title }}</h3>
          <p style="text-align: center">{!! $item->content !!}</p>
          <div class="d-flex justify-content-between align-items-center">
            <span class="time">{{ $item->created_at->format('H:i') }}</span>
            <a href="{{ route('groups.comment', $item) }}" class="comments-link">
              <i class="fas fa-arrow-right me-2"></i> نظرات
            </a>
          </div>
          <hr>
        @endforeach
      </div>
  
      {{-- Tab Polls --}}
      <div class="tab-content" id="poll" style="padding: .5rem 1rem;">
        @foreach ($group2->polls as $item)
          @include('groups.partials.poll', ['item' => $item, 'userVote' => $userVote])
        @endforeach
      </div>

      <style>
        #electionRedirect{
          width: 100%;
        }
      </style>
      <div class="tab-content" id="election" style="padding: .5rem 1rem;">
        @foreach ($group2->polls as $item)
            @if($item->main_type == 0)  
                  @include('groups.partials.poll', ['item' => $item, 'userVote' => $userVote])
            @endif
        @endforeach
       
      </div>
    </div>
  </div>
  
  @if (isset($_GET['filter']))
    <script>
      document.getElementById('groupInfoPanel').style.right = '0';
      document.querySelector('#post').classList.add('active')
      document.querySelector('#grooup').classList.remove('active')
      document.querySelector('#members').classList.remove('active')
      document.querySelectorAll('.tab').forEach(tab => {
        let dataTab = tab.getAttribute('data-tab')

        if(dataTab == 'post'){
          tab.classList.add('active')
        }else{
          tab.classList.remove('active')
        }
      })
    </script>
@endif

<script>
  // Debounce function to limit API calls
  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Search function
  const performSearch = debounce(function(e) {
    const searchText = e.target.value.toLowerCase();
    const searchType = document.getElementById('searchType').value;
    
    // Show loading indicator
    const groupsList = document.getElementById('groupsList');
    groupsList.innerHTML = '<div style="text-align: center; padding: 1rem;">در حال جستجو...</div>';
    
    // Make API call to search through all groups
    fetch(`/api/groups/search?q=${encodeURIComponent(searchText)}&type=${searchType}`, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      credentials: 'same-origin'
    })
      .then(response => {
        if (response.status === 429) {
          throw new Error('تعداد درخواست‌ها زیاد است. لطفاً چند لحظه صبر کنید.');
        }
        if (response.status === 401) {
          throw new Error('لطفاً ابتدا وارد شوید.');
        }
        if (!response.ok) {
          throw new Error('خطا در ارتباط با سرور. لطفاً دوباره تلاش کنید.');
        }
        return response.json();
      })
      .then(data => {
        groupsList.innerHTML = '';
        
        if (data.groups.length === 0) {
          groupsList.innerHTML = '<div style="text-align: center; padding: 1rem;">نتیجه‌ای یافت نشد</div>';
          return;
        }
        
        data.groups.forEach(group => {
          const groupItem = document.createElement('div');
          groupItem.className = 'group-item';
          groupItem.dataset.groupId = group.id;
          groupItem.dataset.level = group.location_level;
          
          groupItem.innerHTML = `
            <div class="group-avatar">
              ${group.avatar ? 
                `<img src="${group.avatar}" alt="${group.name}">` : 
                `<div class="default-avatar">${group.name.substring(0, 2)}</div>`
              }
            </div>
            
            <div class="group-info">
              <div class="group-main-info">
                <div class="group-name">
                  ${group.is_approved ? 
                    `<a href="/groups/chat/${group.id}">${group.name}</a>` : 
                    `<span class="text-muted">${group.name} (در انتظار تأیید)</span>`
                  }
                </div>
                <div class="group-members-count">${group.members_count} عضو</div>
              </div>
              
              <div class="group-secondary-info">
                <div class="member-role">
                  ${group.status === 1 ? 
                    `<span>${group.role}</span>` : 
                    `<span>خارج شده <a href="/groups/${group.id}/relogout" class="text-primary">بازگردانی</a></span>`
                  }
                </div>
              </div>
              
              <div class="group-content" style="display: none;">
                ${group.content}
              </div>
            </div>
          `;
          
          groupsList.appendChild(groupItem);
        });
      })
      .catch(error => {
        console.error('Error searching groups:', error);
        groupsList.innerHTML = `
          <div style="text-align: center; padding: 1rem; color: red;">
            ${error.message}
            <br>
            <button onclick="performSearch(event)" class="btn btn-sm btn-primary mt-2">تلاش مجدد</button>
          </div>
        `;
      });
  }, 500); // 500ms delay

  // Add event listener with debounced search
  document.getElementById('groupSearch').addEventListener('input', performSearch);

  // Add content elements to group items
  document.querySelectorAll('.group-item').forEach(group => {
    const groupId = group.dataset.groupId;
    if (groupId) {
      fetch(`/api/groups/${groupId}/content`)
        .then(response => response.json())
        .then(data => {
          const contentDiv = document.createElement('div');
          contentDiv.className = 'group-content';
          contentDiv.style.display = 'none';
          contentDiv.textContent = data.content;
          group.appendChild(contentDiv);
        });
    }
  });
  


  document.getElementById("addUserButton").onclick = function() {
    document.getElementById("userSearchModal").style.display = 'block';
};


// بسته کردن مودال
document.querySelector(".close").onclick = function() {
    document.getElementById("userSearchModal").style.display = "none";
};

// بسته کردن مودال زمانی که کلیک خارج از مودال انجام می‌شود
window.onclick = function(event) {
    if (event.target == document.getElementById("userSearchModal")) {
        document.getElementById("userSearchModal").style.display = "none";
    }
};

document.getElementById("addUsersToGroup").onclick = function() {
    let userInfo = document.querySelector("#searchUsers").value;
    let hours = document.querySelector("#hoursUser").value;

    if(hours == ""){
        alert("لطفا ساعات مدنظر را وارد کنید");
        return;
    }

    if(userInfo == ""){
        alert("لطفا کاربر مدنظر را وارد کنید");
        return;
    }

    fetch(`/add-users-to-group`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ userInfo: userInfo, groupId: {{ $group2->id }}, hours: hours})
    }).then(response => response.json())
      .then(data => alert(data.message));
};

// نمایش مودال درخواست چت
document.getElementById("addChatRequestButton").onclick = function() {
    document.getElementById("chatRequestModal").style.display = 'block';
};

// بستن مودال
document.querySelector(".close").onclick = function() {
    document.getElementById("chatRequestModal").style.display = "none";
};

// بستن مودال زمانی که کلیک خارج از مودال انجام می‌شود
window.onclick = function(event) {
    if (event.target == document.getElementById("chatRequestModal")) {
        document.getElementById("chatRequestModal").style.display = "none";
    }
};


// جستجو در میان مدیران
document.getElementById("searchManagers").addEventListener("input", function() {
    let query = this.value.toLowerCase();
    let managerItems = document.querySelectorAll(".manager-item");

    managerItems.forEach(item => {
        let managerName = item.querySelector("span").textContent.toLowerCase();
        
        if (managerName.includes(query)) {
            item.style.display = "flex"; // نمایش مورد
        } else {
            item.style.display = "none"; // مخفی کردن مورد
        }
    });
});

// نمایش مودال درخواست چت
document.getElementById("addChatRequestButton").onclick = function() {
    document.getElementById("chatRequestModal").style.display = 'block';
};

// بستن مودال
document.querySelector(".close").onclick = function() {
    document.getElementById("chatRequestModal").style.display = "none";
};

// بستن مودال زمانی که کلیک خارج از مودال انجام می‌شود
window.onclick = function(event) {
    if (event.target == document.getElementById("chatRequestModal")) {
        document.getElementById("chatRequestModal").style.display = "none";
    }
};

</script>
