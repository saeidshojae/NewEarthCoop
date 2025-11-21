@php
    $groups = $groups ?? collect();
    $icon = $icon ?? 'fas fa-users';
    $emptyMessage = $emptyMessage ?? 'هیچ گروهی یافت نشد';
    $filters = $filters ?? [];
    $filterTarget = $tableId ?? ('table_' . \Illuminate\Support\Str::uuid()->toString());
    $levelKey = $levelKey ?? null;
    $type = $type ?? null;
    $roleLabels = [
        0 => 'ناظر',
        1 => 'فعال',
        2 => 'بازرس',
        3 => 'مدیر',
        4 => 'مهمان',
        5 => 'فعال ۲',
    ];
    $currentUserId = auth()->id();
@endphp

@if(!empty($filters))
    <div class="filter-buttons" data-target="{{ $filterTarget }}">
        @foreach($filters as $value => $label)
            <button class="filter-button {{ $loop->first ? 'active' : '' }}" data-filter="{{ $value }}">
                {{ $label }}
            </button>
        @endforeach
    </div>
@endif

<div class="data-table-container">
    <table class="data-table" id="{{ $filterTarget }}">
        <thead>
            <tr>
                <th></th>
                <th>نام مجمع</th>
                <th>سمت</th>
                <th>وضعیت</th>
                <th>تعداد اعضاء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($groups as $group)
                @php
                    $pivot = $group->pivot ?? \App\Models\GroupUser::where('group_id', $group->id)
                        ->where('user_id', $currentUserId)
                        ->first();
                    if (!$pivot) {
                        continue;
                    }

                    $pivotRole = isset($pivot->role) ? (int) $pivot->role : null;
                    $roleText = $roleLabels[$pivotRole] ?? 'عضو';

                    $locationApproved = true;
                    if ($group->address_id !== null) {
                        $level = $group->location_level;
                        if (!in_array($level, ['continent', 'country', 'province', 'county', 'section', 'city'], true)) {
                            $modelMap = [
                                'region' => \App\Models\Region::class,
                                'neighborhood' => \App\Models\Neighborhood::class,
                                'street' => \App\Models\Street::class,
                                'alley' => \App\Models\Alley::class,
                            ];
                            $model = $modelMap[$level] ?? null;
                            if ($model) {
                                $instance = $model::find($group->address_id);
                                if ($instance && (int)($instance->status ?? 1) === 0) {
                                    $locationApproved = false;
                                }
                            }
                        }
                    }

                    $specialtyApproved = true;
                    if (($group->specialty && (int)($group->specialty->status ?? 1) === 0) ||
                        ($group->experience && (int)($group->experience->status ?? 1) === 0)) {
                        $specialtyApproved = false;
                    }

                    $isActiveMembership = (int)($pivot->status ?? 1) === 1;
                    $pendingApproval = !$locationApproved || !$specialtyApproved;

                    if (!$isActiveMembership) {
                        $statusClass = 'status-badge inactive';
                        $statusLabel = 'غیرفعال';
                    } elseif ($pendingApproval) {
                        $statusClass = 'status-badge pending';
                        $statusLabel = 'در انتظار تأیید';
                    } else {
                        $statusClass = 'status-badge active-status';
                        $statusLabel = 'فعال';
                    }

                    $canAccess = $statusClass === 'status-badge active-status';

                    $filterValue = 'all';
                    if ($levelKey) {
                        $rawValue = data_get($group, $levelKey);
                        $filterValue = $rawValue ? \Illuminate\Support\Str::lower($rawValue) : 'all';
                    }
                @endphp
                <tr @if($levelKey) data-filter-value="{{ $filterValue }}" @endif>
                    <td><i class="{{ $icon }} table-icon"></i></td>
                    <td>
                        @if($canAccess)
                            <a href="{{ route('groups.show', $group) }}"
                               class="text-earth-green hover:text-dark-green transition font-medium">
                                {{ $group->name }}
                            </a>
                        @else
                            <span class="text-gray-500">
                                {{ $group->name }}
                                @if($statusClass === 'status-badge pending')
                                    (در انتظار تأیید)
                                @endif
                            </span>
                        @endif
                    </td>
                    <td>{{ $roleText }}</td>
                    <td><span class="{{ $statusClass }}">{{ $statusLabel }}</span></td>
                    <td>{{ $group->users()->count() }} عضو</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        {{ $emptyMessage }}
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
