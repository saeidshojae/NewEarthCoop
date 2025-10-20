<style>
  /* مطمئن شو مودال بالاتر از هر چیز دیگه‌ست */
  .modal { z-index: 200001 !important; }
  .modal-backdrop { z-index: 1 !important; }
    #topVotesModal{
        z-index: 200002 !important;
    }
  /* اگر برای election-box z-index بزرگی دادی، خنثی‌ش کن: */
  .election-box.election-card {
  }

.tab-content
{
    display: block !important;
}
</style>

<div class="election-box election-card" style="display: none; ">
  @php 
    $groupId = isset($election) && $election ? ($election->group_id ?? null) : null;
    if (!$groupId && isset($group) && $group) {
      $groupId = $group->id;
    }
    $groupUser = null;
    if ($groupId) {
      $groupUser = \App\Models\GroupUser::where('group_id', $groupId)
        ->where('user_id', auth()->id())
        ->first();
    }
  @endphp 
    
  @if(!$groupUser || $groupUser->role == 0 || $groupUser->role == 4)
        <h4>شما مجاز به شرکت در انتخابات نیستید</h4>
        <p>شا در این گروه دارای نقش ناظر میباشید</p>
    @else
    
  @if(isset($election) && $election && $election->second_finish_time == null)
    
    <h4>فرم انتخابات</h4>
    
    @else 
    
     <h4>فرم مجدد انتخابات</h4>
     
     @endif
    <form action="{{ route('vote', $group) }}" method="POST" id="electionForm">
        @csrf
  @if (isset($election) && $election)

        <div id="countdownText" style="irection: rtl !important; text-align: center; width: 100%;"></div>
        <div style="background: #eee; border-radius: 1rem; overflow: hidden;width: 100%; margin: 1rem 0;">
          <div id="progressBar"></div>
        </div>
        
          <script>
              const startsAt = new Date("{{ $election->starts_at ?? '' }}").getTime();
              const endsAt = new Date("{{ $election->ends_at ?? '' }}").getTime();
              const countdownText = document.getElementById('countdownText');
              const progressBar = document.getElementById('progressBar');
              const timer = setInterval(() => {
                const now = new Date().getTime();
                const total = endsAt - startsAt;
                const elapsed = now - startsAt;
                const remaining = endsAt - now;

                // درصد پیشرفت
                const progress = Math.min(100, (elapsed / total) * 100);
                document.querySelector('#progressBar').style.width = progress + '%'

                // محاسبه زمان باقی‌مانده
                if (remaining <= 0) {
                  clearInterval(timer);
                  countdownText.innerHTML = "⏰ انتخابات به پایان رسید";
                  progressBar.style.width = '100%';
                // همهٔ فیلدها را disabled کن
                const formElements = electionForm.querySelectorAll('input, select, textarea, button');
                // formElements.forEach(el => el.disabled = true);
                let electionId = "{{ $election->id ?? '' }}";
                console.log('pokl')
                finishElectionAjax(electionId);

                return;
                }
                const days = Math.floor(remaining / (1000 * 60 * 60 * 24));
                const hours = Math.floor((remaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((remaining % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((remaining % (1000 * 60)) / 1000);
        
                document.querySelector('#countdownText').innerHTML = `${days} روز ${hours} ساعت ${minutes} دقیقه ${seconds} ثانیه باقی مانده تا اتمام انتخابات`;
              }, 1000);
        
              function finishElectionAjax(electionId) {
                    $.ajax({
                        url: '/finish-election/' + electionId,
                        type: 'POST',
                        dataType: 'json',
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                        election_id: electionId
                        },
                        success: function(data) {
                        console.log('نتیجه:', data);
                        // در صورت نیاز:
                        // window.location.href = '/somewhere';
                        },
                        error: function(xhr, status, error) {
                        console.error('خطا در ارسال درخواست پایان انتخابات:', error);
                        }
                    });
                }



          </script>
        
        
          @endif
          
        @php
        
        $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level)->first();
        
        if($group->specialty_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_job')->first();
        }elseif($group->experience_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_experience')->first();
        }elseif($group->age_group_id != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_age')->first();
        }elseif($group->gender != null){
            $groupSetting = \App\Models\GroupSetting::where('level', $group->location_level . '_gender')->first();
        }
        @endphp
<button type="button"
        class="btn btn-outline-primary mb-2 btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#candidatesModal">
  مشاهده رزومه کاندیدها
</button>

<button type="button"
        class="btn btn-outline-info mb-2"
        data-bs-toggle="modal"
        data-bs-target="#guidelineModal" style='    background-color: #0dcaf0;'>
  شیوه‌نامه انتخابات
</button>

@if($election && $election->second_finish_time)
  <button type="button" style='background-color: #198754'
          class="btn btn-outline-success mb-2"
          data-bs-toggle="modal"
          data-bs-target="#topVotesModal">
    نمایش بیشترین آرا
  </button>
@endif

<div class="modal fade" id="topVotesModal" tabindex="-1" aria-labelledby="topVotesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" style="direction: rtl; text-align: right;">
      <div class="modal-header" style="display:flex; justify-content:space-between;">
        <h5 class="modal-title" id="topVotesModalLabel">لیست آرا (مرتب‌شده)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>

      <div class="modal-body">
          <ul class="nav nav-tabs" role="tablist" style="margin-bottom:1rem;">
  <li class="nav-item">
    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#managers-pane" type="button" role="tab">
      هیات‌مدیره
    </button>
  </li>
  <li class="nav-item">
    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#inspectors-pane" type="button" role="tab">
      بازرس
    </button>
  </li>
</ul>

<!-- این خط باید دقیقا tab-content باشد -->
<div class="tab-content">
  <div class="tab-pane fade show active" id="managers-pane" role="tabpanel">
    <div class="table-responsive">
              <table class="table table-striped table-hover align-middle">
                <thead>
                  <tr>
                    <th style="width:60px">#</th>
                    <th>نام</th>
                    <th style="white-space:nowrap">تعداد رأی</th>
                    <th style="width:160px">اقدامات</th>
                  </tr>
                </thead>
                <tbody>
  @foreach($managersSorted as $i => $u)
    @php
      $name = is_array($u) ? ($u['name'] ?? (trim(($u['first_name'] ?? '').' '.($u['last_name'] ?? '')) ?: '—'))
                           : (trim(($u->name ?? '') ?: ( ($u->first_name ?? '').' '.($u->last_name ?? '') )) ?: '—');

      $votes = is_array($u) ? ($u['manager_votes'] ?? $u['votes'] ?? 0)
                            : ($u->manager_votes ?? $u->votes ?? 0);

      $id = is_array($u) ? ($u['id'] ?? null) : ($u->id ?? null);
    @endphp
    @if($votes != 0)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $name }}</td>
      <td>{{ (int)$votes }}</td>
      <td>
        @if($id)
          <a href="{{ url('/profile-member/'.$id) }}" target="_blank"
             class="btn btn-sm btn-outline-primary btn-primary">مشاهده پروفایل</a>
        @endif
      </td>
    </tr>
    @endif
  @endforeach
</tbody>

              </table>
            </div>
  </div>

  <div class="tab-pane fade" id="inspectors-pane" role="tabpanel">
    <div class="table-responsive">
              <table class="table table-striped table-hover align-middle">
                <thead>
                  <tr>
                    <th style="width:60px">#</th>
                    <th>نام</th>
                    <th style="white-space:nowrap">تعداد رأی</th>
                    <th style="width:160px">اقدامات</th>
                  </tr>
                </thead>
               <tbody>
  @foreach($inspectorsSorted as $i => $u)
    @php
      $name = is_array($u) ? ($u['name'] ?? (trim(($u['first_name'] ?? '').' '.($u['last_name'] ?? '')) ?: '—'))
                           : (trim(($u->name ?? '') ?: ( ($u->first_name ?? '').' '.($u->last_name ?? '') )) ?: '—');

      $votes = is_array($u) ? ($u['inspector_votes'] ?? $u['votes'] ?? 0)
                            : ($u->inspector_votes ?? $u->votes ?? 0);

      $id = is_array($u) ? ($u['id'] ?? null) : ($u->id ?? null);
    @endphp
    @if($votes != 0)
    <tr>
      <td>{{ $i+1 }}</td>
      <td>{{ $name }}</td>
      <td>{{ (int)$votes }}</td>
      <td>
        @if($id)
          <a href="{{ url('/profile-member/'.$id) }}" target="_blank"
             class="btn btn-sm btn-outline-primary btn-primary">مشاهده پروفایل</a>
        @endif
      </td>
    </tr>
     @endif
  @endforeach
</tbody>

              </table>
            </div>
  </div>
</div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="guidelineModal" tabindex="-1" aria-labelledby="guidelineModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" style="direction: rtl; text-align: right;">
      <div class="modal-header" style="display:flex; justify-content:space-between;">
        <h5 class="modal-title" id="guidelineModalLabel">شیوه‌نامه انتخابات</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body" style="line-height:1.8;">
        <p>
          🔹 <strong>بند ۱:</strong> هر عضو فقط می‌تواند تا سقف تعداد مشخص‌شده در آیین‌نامه رأی دهد.
        </p>
        <p>
          🔹 <strong>بند ۲:</strong> رأی‌ها محرمانه هستند و تنها نتایج نهایی منتشر خواهد شد.
        </p>
        <p>
          🔹 <strong>بند ۳:</strong> هرگونه تخلف در روند رأی‌گیری طبق قوانین گروه بررسی خواهد شد.
        </p>
        <p>
          🔹 <strong>بند ۴:</strong> اعضا موظف به مطالعه کامل این شیوه‌نامه قبل از شرکت در انتخابات هستند.
        </p>
        <p class="text-muted">
          (اینجا می‌تونی متن کامل شیوه‌نامه‌ی واقعی خودتون رو بذاری یا از دیتابیس بخونی)
        </p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="candidatesModal" tabindex="-1" aria-labelledby="candidatesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content" style="direction: rtl; text-align: right;">
      <div class="modal-header" style='    display: flex;
    justify-content: space-between;'>
        <h5 class="modal-title" id="candidatesModalLabel">رزومه کاندیدها</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex align-items-center gap-2 mb-3">
          <input type="text" id="candidateSearch" class="form-control" placeholder="جستجو بر اساس نام...">

        </div>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle" id="candidatesTable">
            <thead>
              <tr>
                <th style="width:60px">#</th>
                <th>نام</th>
                <th style="white-space:nowrap">رأی مدیر</th>
                <th style="white-space:nowrap">رأی بازرس</th>
                <th style="width:160px">اقدامات</th>
              </tr>
            </thead>
            <tbody><!-- با JS پر می‌شود --></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>

<div class="form-group mt-3">
    <label>
        هیات مدیره (حداکثر {{ $groupSetting ? $groupSetting->manager_count : 0 }} نفر را انتخاب کنید)
    </label>
  @php
    $rolesByUser = \App\Models\GroupUser::where('group_id', $group->id)->pluck('role','user_id');
  @endphp
  <select id="manager_vote" name="manager[]" multiple class="form-control">
    @foreach ($group->users as $user)
      @php $role = $rolesByUser[$user->id] ?? null; @endphp
      @if(($role !== 4) && ($role !== 0))
                <option value="{{ $user->id }}"
                        @if (in_array($user->id, $selectedVotesManager)) selected @endif>
                    {{ $user->first_name . ' ' . $user->last_name }}
                    ({{ $managerCounts[$user->id] ?? 0 }} رأی)
                </option>
            @endif
        @endforeach
    </select>
</div>

<div class="form-group">
    <label>
        بازرس ها (حداکثر {{ $groupSetting ? $groupSetting->inspector_count : 0 }} نفر را انتخاب کنید)
    </label>
  <select id="inspector_vote" name="inspector[]" multiple class="form-control">
    @foreach ($group->users as $user)
      @php $role = $rolesByUser[$user->id] ?? null; @endphp
      @if(($role !== 4) && ($role !== 0))
                <option value="{{ $user->id }}"
                        @if (in_array($user->id, $selectedVotesInspector)) selected @endif>
                    {{ $user->first_name . ' ' . $user->last_name }}
                    ({{ $inspectorCounts[$user->id] ?? 0 }} رأی)
                </option>
            @endif
        @endforeach
    </select>
</div>


                  <input type="submit" value="ثبت" class="btn btn-warning w-100 mt-3" style='    background-color: #ffd900;'>

    </form>
    
    @endif
</div>
@php
$allOptions = $group->users->map(function ($u) use ($managerCounts, $inspectorCounts) {
    return [
        'id' => $u->id,
        'text' => trim($u->first_name . ' ' . $u->last_name),
        'role' => $u->pivot->role ?? $u->role,
        'manager_votes'   => (int)($managerCounts[$u->id] ?? 0),
        'inspector_votes' => (int)($inspectorCounts[$u->id] ?? 0),
    ];
});
@endphp

<script>
$(function(){
  $('#candidatesModal').appendTo('body'); // مهم
});
$(function(){
  $('#candidatesModal').appendTo('body');
  $('#guidelineModal').appendTo('body'); // مهم برای شیوه‌نامه
});

</script>

<script>
  // اگر روت مشخص داری، می‌تونی این تابع را با route() از Blade پر کنی
  function profileUrlOf(id){
      // مثال ساده:
      return '/profile-member/' + id; 
      // یا اگر روت داری:
      // return "{{ url('/users') }}/" + id;
  }

  (function(){
    // از Blade:
    const allOptions = @json($allOptions);

    // فقط کاندیدهای فعال (role == 1)
    function candidatesBase(){
      return allOptions.filter(u => String(u.role) === '1');
    }

    function renderTable(rows){
      const $tbody = $('#candidatesTable tbody');
      $tbody.empty();
      if(!rows.length){
        $tbody.append('<tr><td colspan="5" class="text-center text-muted">چیزی یافت نشد</td></tr>');
        return;
      }
      rows.forEach((u, idx) => {
        const tr = `
          <tr>
            <td>${idx+1}</td>
            <td>${u.text}</td>
            <td>${u.manager_votes ?? 0}</td>
            <td>${u.inspector_votes ?? 0}</td>
            <td>
              <a href="${profileUrlOf(u.id)}" target="_blank" class="btn btn-sm btn-outline-primary btn-primary">
                مشاهده پروفایل
              </a>
            </td>
          </tr>
        `;
        $tbody.append(tr);
      });
    }

    function applyFilters(){
      const q = ($('#candidateSearch').val() || '').trim();
      const f = $('#candidateFilter').val(); // all | manager | inspector

      let list = candidatesBase();

      if (q.length){
        const qn = q.toLowerCase();
        list = list.filter(u => (u.text || '').toLowerCase().includes(qn));
      }

      if (f === 'manager'){
        // فقط کسانی که حداقل یک رأی مدیر دارند
        list = list.filter(u => Number(u.manager_votes || 0) > 0);
      } else if (f === 'inspector'){
        list = list.filter(u => Number(u.inspector_votes || 0) > 0);
      }

      renderTable(list);
    }

    // باز کردن مودال و پر کردن جدول
    $('#openCandidatesModal').on('click', function(){
      // ریست فیلترها
      $('#candidateSearch').val('');
      $('#candidateFilter').val('all');

      applyFilters();


    });

    // سرچ و فیلتر زنده
    $('#candidateSearch').on('input', applyFilters);
    $('#candidateFilter').on('change', applyFilters);

    // اگر رأی‌ها را جای دیگری زنده آپدیت می‌کنی، بعد از بروزرسانی:
    // - آرایه allOptions را آپدیت کن
    // - اگر مودال باز است، applyFilters() را صدا بزن
    // مثال:
    // allOptions.forEach(o => { o.manager_votes = newCounts.manager[o.id] || 0; ... });
    // if ($('#candidatesModal').hasClass('show')) applyFilters();

  })();
</script>


<script>
function refill($el, context, selectedList, otherSelected){
    $el.empty();
    allOptions.forEach(user => {
        if (user.role == 1 && !otherSelected.includes(String(user.id))) {
            const votes = context === 'manager' ? user.manager_votes : user.inspector_votes;
            const label = `${user.text} (${votes} رأی)`;   // <-- اینجا رأی را اضافه کردیم
            const opt = new Option(label, user.id, false, selectedList.includes(String(user.id)));
            $el.append(opt);
        }
    });
    // اگر Select2 قبلاً فعال شده، برای رفرش متن‌ها:
    if ($el.data('select2')) {
        $el.trigger('change.select2');
    }
}

refill($inspector, 'inspector', $inspector.val()||[], selectedManagers);
refill($manager,   'manager',   $manager.val()||[],   selectedInspectors);
$('#manager_vote').select2('destroy');
$('#inspector_vote').select2('destroy');

// (در این فاصله اگر لازم است optionها را با متن جدید set کنی، انجام بده)

$('#manager_vote').select2({ dir:"rtl", placeholder:"انتخاب مدیر" });
$('#inspector_vote').select2({ dir:"rtl", placeholder:"انتخاب بازرس" });

    $(document).ready(function () {
        const $inspector = $('#inspector_vote');
        const $manager = $('#manager_vote');
    
const allOptions = @json($allOptions);


        
        console.log(allOptions)
        
        function updateSelectBoxes() {
            const selectedInspectors = $inspector.val() || [];
            const selectedManagers = $manager.val() || [];
    
          // بروزرسانی لیست بازرس‌ها
            $inspector.empty();
            allOptions.forEach(user => {
                if (user.role == 1 && !selectedManagers.includes(user.id.toString())) {
                    const newOption = new Option(
                        user.text,
                        user.id,
                        selectedInspectors.includes(user.id.toString()),
                        selectedInspectors.includes(user.id.toString())
                    );
                    $inspector.append(newOption);
                }
            });
            
            // بروزرسانی لیست مدیرها
            $manager.empty();
            allOptions.forEach(user => {
                if (user.role == 1 && !selectedInspectors.includes(user.id.toString())) {
                    const newOption = new Option(
                        user.text,
                        user.id,
                        selectedManagers.includes(user.id.toString()),
                        selectedManagers.includes(user.id.toString())
                    );
                    $manager.append(newOption);
                }
            });

    
            $inspector.trigger('change.select2');
            $manager.trigger('change.select2');
        }
    
        $inspector.select2({ dir: "rtl", placeholder: "انتخاب بازرس" });
        $manager.select2({ dir: "rtl", placeholder: "انتخاب مدیر" });
    
        $inspector.on('change', updateSelectBoxes);
        $manager.on('change', updateSelectBoxes);
    
        updateSelectBoxes(); // بار اول
    });
    </script>

    