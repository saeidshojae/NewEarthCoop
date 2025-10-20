<table class="table table-bordered">
    @foreach($groups as $group)
        @php
            $isFrozen = ($user->address->status ?? null) != 1 || ($user->experience_status ?? null) != 1 || ($user->occupational_status ?? null) != 1;
            $pivot = \App\Models\GroupUser::where('group_id', $group->id)
                ->where('user_id', auth()->user()->id)
                ->first();
            $memberRole = $pivot ? match($pivot->role) {
                0 => 'ناظر',
                1 => 'فعال',
                2 => 'بازرس',
                3 => 'مدیر',
                4 => 'مهمان',
                5 => 'فعال ۲',
            } : null;
            $fColorAvatar = rand(1,255);
            $sColorAvatar = rand(1,255);
            $tColorAvatar = rand(1,255);
        @endphp
        <tr>
            <th style="display:flex;align-items:center;gap:.5rem;">
                <div class="group-avatar">
                    @if($group->avatar)
                        <img src="{{ asset('images/groups/' . $group->avatar) }}" alt="{{ $group->name }}" style="width:3rem;height:2rem;border-radius:2rem;">
                    @else
                        <div class="default-avatar" style="background:linear-gradient(90deg, rgba({{ $fColorAvatar }},{{ $sColorAvatar }},{{ $tColorAvatar }},.8) 0%, rgba({{ $fColorAvatar }},{{ $sColorAvatar }},{{ $tColorAvatar }},.5) 100%);width:3rem;height:2rem;border-radius:2rem;color:#fff;display:flex;align-items:center;justify-content:center;">
                            {{ mb_substr($group->name, 0, 2) }}
                        </div>
                    @endif
                </div>
                <a href="{{ !$isFrozen ? route('groups.chat', $group->id) : '#' }}"
                   style="text-decoration:none;color:#333;{{ $isFrozen ? 'opacity:.5;' : '' }}">
                    {{ $group->name }}
                    @if($memberRole)
                        <div style="text-align:right;font-weight:400;">{{ $memberRole }}</div>
                    @endif
                </a>
            </th>
        </tr>
    @endforeach
</table>
