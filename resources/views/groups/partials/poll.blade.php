@php
    $delegation = \App\Models\Delegation::where('poll_id', $item->id)
        ->where('user_id', auth()->id())
        ->first();

    // Generate consistent colors based on user ID (safe)
    $ownerId = optional($item->user)->id ?? 0;
    $hue = fmod($ownerId * 137.508, 360); // Golden angle
    $saturation = 70;
    $lightness = 85;
    $backgroundColor = "hsl({$hue}, {$saturation}%, {$lightness}%)";
    $textColor = "hsl({$hue}, {$saturation}%, 30%)";
@endphp

<div style='display: flex;      margin-right: -1rem;
    @if (($item->created_by ?? null) != auth()->id()) justify-content: start; padding: 0 8px; 
    @else justify-content: end; @endif' 
    id='poll-{{ $item->id }}'>

    @if (($item->created_by ?? null) != auth()->id())
        @if ($item->user)
            <a href='{{ route('profile.member.show', $item->user) }}' style='margin-left: .5rem; height: 2rem'>
                @if ($item->user->avatar == null)
                    <div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .6rem; margin: 0; background-color: {{ $backgroundColor }}; color: {{ $textColor }};">
                        <span>{{ mb_substr($item->user->first_name, 0, 1) }} {{ mb_substr($item->user->last_name, 0, 1) }}</span>
                    </div>
                @else
                    <img alt="تصویر پروفایل" class="rounded-circle" width="32" height="32" src="{{ asset('/images/users/avatars/' . $item->user->avatar) }}">
                @endif
            </a>
        @else
            <div class="group-avatar" style="width: 2rem; height: 2rem; font-size: .6rem; margin-left: .5rem; background: #eee; color: #666;">
                <span>؟ ؟</span>
            </div>
        @endif
    @endif

<div class="poll-card" id="poll-{{ $item->id }}" style='margin: 0; position: relative; margin-bottom: 1rem; background: #ecf7f7; padding-top: .5rem'>
    @if (($item->created_by ?? null) != auth()->id() && $item->user)
        <div class="message-sender" style="margin-left: .4rem">
            <a href='{{ route('profile.member.show', $item->user) }}' style='color: blue; font-weight: 900'>
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
    <h3 style='text-align: center; margin-bottom: 2rem'>
        <img src='{{ asset('/images/poll-icon.png') }}'>
        {{ $item->main_type == 0 ? 'انتخاب' : 'نظرسنجی' }}
    </h3>

<div style="position: absolute; top: 0; @if($item->created_by == auth()->user()->id) right: .3rem @else left: .3rem @endif;">
    <!-- دکمه ⋮ ساده -->
    <button type="button" id="dropdownMenuButton{{ $item->id }}" 
            data-bs-toggle="dropdown" aria-expanded="false"
            style="border: none; background: transparent; font-size: 20px; cursor: pointer; padding: 0;">
        ⋮
    </button>

    @if (($item->created_by ?? null) == auth()->id())

    <!-- منوی dropdown -->
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
                <li><a class="dropdown-item" href="#" onclick="replyToMessage('poll-{{ $item->id }}', '', 'نظرسنجی: {!! $item->question !!}')"><i class="fas fa-reply"></i> پاسخ</a></li>

        <li>
            <a class="dropdown-item" href="#" onclick="showEditPollBox({{ $item->id }})">
                <i class="fas fa-edit"></i> ویرایش
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('groups.poll.delete', [$group, $item->id]) }}" style='    color: #c42e2e;'
               onclick="return confirm('آیا مطمئن هستید که می‌خواهید این poll را حذف کنید؟');">
               <i class="fas fa-trash"></i> حذف
            </a>
        </li>
    </ul>
    
    @else 
        <!-- منوی dropdown -->
    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $item->id }}">
        <li><a class="dropdown-item" href="#" onclick="replyToMessage('poll-{{ $item->id }}', '', 'نظرسنجی: {!! $item->question !!}')"><i class="fas fa-reply"></i> پاسخ</a></li>

        <li><a class="dropdown-item text-danger" href="#" onclick="reportMessage({{ $item->id }})"><i class="fas fa-flag"></i> گزارش</a></li>

    </ul>
    
    @endif 

</div>


   <div style="display: flex; justify-content: space-between; align-items: start;">
       <ul style='list-style-type: none; width: 100%; padding: 0;'><b>
           <li style='margin: .5rem 0; display: flex; justify-content: space-between;'>نوع: 
               <span>{{ $item->real_type == 1 ? 'تخصصی (' . optional($item->skill)->name . ')' : 'عمومی' }}</span>
           </li>
           <li style='margin: .5rem 0; display: flex; justify-content: space-between;'>تاریخ ایجاد: 
               <span>{{ verta($item->created_at)->format('Y/m/d') }}</span>
           </li>
           <li style='margin: .5rem 0;'>
               <p class="poll-type" style='margin: 0; color: #000; display: flex; justify-content: space-between;'>
                    زمان باقی‌مانده: 
                    <span class="poll-timer" data-expires="{{ \Carbon\Carbon::parse($item->expires_at)->toIso8601String() }}" style='color: #000'>
                        در حال محاسبه...
                    </span>
               </p>
           </li>

           @if ($item->real_type == 1)
               <li style="cursor: pointer; color: blue; display: flex; justify-content: space-between;" onclick="toggleSkillList({{ $item->id }})">
                   <span style='color: #000'>تفویض:</span>
                   لیست متخصصین {{ optional($item->skill)->name }}
               </li>
           @endif
           <br>
           <li style='margin: .5rem 0;'>سوال: <span style='margin-right: .5rem'>{{ $item->question }}</span></li>
       </b></ul>
   </div>

   @if ($item->skill_id)
       @php
           $memberIds = $group->users()->pluck('users.id')->toArray();
           $specialGroupIds = \App\Models\Group::where('experience_id', $item->skill_id)->pluck('groups.id')->toArray();
           $specialityUsers = \App\Models\GroupUser::whereIn('user_id', $memberIds)
               ->whereIn('group_id', $specialGroupIds)
               ->get()
               ->unique('user_id');
       @endphp
       <div id="skill-list-{{ $item->id }}" class="skill-list">
           <h3>لیست متخصصین این دسته</h3>
           <table class="table table-light">
               <thead>
                   <tr>
                       <th>نام</th>
                       <th>وضعیت</th>
                       <th>عملیات</th>
                   </tr>
               </thead>
               <tbody>
                   @foreach ($specialityUsers as $user)
                       @php $u = $user->user; @endphp
                       @if ($u && $u->id != auth()->id())
                           @php
                               $checkDelegation = \App\Models\Delegation::where('poll_id', $item->id)
                                   ->where('expert_id', $u->id)
                                   ->where('user_id', auth()->id())
                                   ->first();
                           @endphp
                           <tr>
                               <td><a href='{{ route('profile.member.show', $u) }}'>{{ $u->fullName() }}</a></td>
                               <td class="{{ $checkDelegation ? 'text-success' : 'text-danger' }}">
                                   {{ $checkDelegation ? 'تفویض شده' : 'تفویض نشده' }}
                               </td>
                               <td>
                                   <a href="{{ route('groups.delegation', [$item->id, $u->id]) }}" class="btn btn-warning">
                                       {{ $checkDelegation ? 'برداشتن تفویض' : 'تفویض به این کاربر' }}
                                   </a>
                               </td>
                           </tr>
                       @endif
                   @endforeach
               </tbody>
           </table>
       </div>
   @endif
<div id="edit-poll-box-{{ $item->id }}" style="display: none; margin-top: .5rem; padding: .5rem; border: 1px solid #ccc; border-radius: 4px;">
    <!-- فرم ویرایش اینجا -->
    <form action="{{ route('groups.poll.update', [$group, $item->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="question" value="{{ $item->question }}" class="form-control mb-2">
        <button type="submit" class="btn btn-primary btn-sm">ذخیره</button>
    </form>
</div>

   @php $isExpired = $item->expires_at && \Carbon\Carbon::parse($item->expires_at)->isPast(); @endphp
   <div class="poll-options" @if($delegation || $isExpired) style="opacity:.5;pointer-events:none;" @endif>
       @php    
           $totalVotes = $item->votes->count(); 
           $delegationCount = \App\Models\Delegation::where('poll_id', $item->id)->count();
           $totalVotes += $delegationCount;
       @endphp

       @foreach($item->options as $option)
           @php
               $totalVotesWeighted = 0;
               $votesForOption = 0;
               foreach ($item->votes as $vote) {
                   $delegationCount = \App\Models\Delegation::where('poll_id', $item->id)
                       ->where('expert_id', $vote->user_id)
                       ->count();
                   $voteWeight = 1 + $delegationCount;
                   $totalVotesWeighted += $voteWeight;
                   if ($vote->option_id === $option->id) {
                       $votesForOption += $voteWeight;
                   }
               }
               $percent = $totalVotesWeighted ? round(($votesForOption / $totalVotesWeighted) * 100) : 0;
               $isVoted = isset($userVote) && $userVote && $userVote->option_id == $option->id;
           @endphp
           <div class="poll-option" data-option-id="{{ $option->id }}" data-poll-id="{{ $item->id }}" onclick="submitVote(this)">
               <span class="poll-percent">{{ $percent }}%</span>
               <span class="poll-text">{{ $option->text }}</span>
               <span class="poll-dot">{{ $isVoted ? '●' : '○' }}</span>
           </div>
       @endforeach

       <div class="poll-footer">
           {{ $totalVotes ? "$totalVotes رأی" : 'هنوز رأی ثبت نشده' }}
       </div>
   </div>
</div>

</div>
