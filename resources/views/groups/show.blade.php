@extends('layouts.unified')

@section('title', $group->name . ' - جزئیات گروه')

@php
    use App\Models\Region;
    use App\Models\Neighborhood;
    use App\Models\Street;
    use App\Models\Alley;

    $pivot = $group->users()
        ->where('users.id', auth()->id())
        ->first();

    $roleLabels = [
        0 => 'ناظر',
        1 => 'فعال',
        2 => 'بازرس',
        3 => 'مدیر',
        4 => 'مهمان',
        5 => 'فعال ۲',
    ];

    $roleValue = $pivot?->pivot?->role;
    $roleLabel = $roleLabels[$roleValue] ?? 'عضو';

    $locationApproved = true;
    if ($group->address_id !== null) {
        $level = $group->location_level;
        if (!in_array($level, ['continent', 'country', 'province', 'county', 'section', 'city'], true)) {
            $modelMap = [
                'region' => Region::class,
                'neighborhood' => Neighborhood::class,
                'street' => Street::class,
                'alley' => Alley::class,
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

    $membershipStatus = (int)($pivot?->pivot?->status ?? 1) === 1;
    $pendingApproval = !$locationApproved || !$specialtyApproved;

    if (!$membershipStatus) {
        $statusLabel = 'غیرفعال';
        $statusClass = 'badge-danger';
    } elseif ($pendingApproval) {
        $statusLabel = 'در انتظار تأیید';
        $statusClass = 'badge-warning';
    } else {
        $statusLabel = 'فعال';
        $statusClass = 'badge-success';
    }

    $typeLabel = match ($group->group_type) {
        0, 'general' => 'مجمع عمومی',
        1, 'speciality', 'specialized' => 'تخصصی',
        3 => 'سنی',
        4 => 'جنسیتی',
        default => 'سایر',
    };

    $membersCount = $group->users()->wherePivot('status', 1)->count();
    $recentMessages = $messages->take(5);
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 space-y-8" style="direction: rtl;">
    <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
        <div class="bg-gradient-to-l from-emerald-500 to-emerald-700 text-white px-6 py-10">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-black mb-2">{{ $group->name }}</h1>
                    <p class="text-sm text-emerald-100 flex items-center gap-2">
                        <i class="fas fa-layer-group"></i>
                        گروه {{ $typeLabel }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 bg-emerald-600/40 backdrop-blur px-4 py-2 rounded-full text-sm">
                        <i class="fas fa-user-shield text-lg"></i>
                        نقش شما: <strong>{{ $roleLabel }}</strong>
                    </span>
                    <span class="inline-flex items-center gap-2 bg-emerald-600/40 backdrop-blur px-4 py-2 rounded-full text-sm">
                        <i class="fas fa-signal text-lg"></i>
                        وضعیت عضویت: <strong>{{ $statusLabel }}</strong>
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-8 grid gap-6 md:grid-cols-3">
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-emerald-600">اعضای فعال</span>
                    <i class="fas fa-users text-emerald-500"></i>
                </div>
                <p class="text-3xl font-extrabold text-emerald-700">{{ number_format($membersCount) }}</p>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-emerald-600">تخصص / مهارت</span>
                    <i class="fas fa-lightbulb text-emerald-500"></i>
                </div>
                <p class="text-base text-emerald-800">
                    {{ $group->specialty?->name ?? ($group->experience?->name ?? '---') }}
                </p>
            </div>

            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-emerald-600">اقدامات سریع</span>
                    <i class="fas fa-bolt text-emerald-500"></i>
                </div>
                <div class="flex flex-col gap-2 text-sm">
                    <a href="{{ route('groups.chat', $group) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        <i class="fas fa-comments"></i>
                        ورود به گفت‌وگو
                    </a>
                    <a href="{{ route('groups.logout', $group) }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 border border-emerald-200 text-emerald-600 rounded-xl hover:bg-emerald-100 transition">
                        <i class="fas fa-sign-out-alt"></i>
                        خروج از گروه
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if($recentMessages->isNotEmpty())
        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">آخرین فعالیت‌های گروه</h2>
                <a href="{{ route('groups.chat', $group) }}" class="text-sm text-emerald-600 hover:text-emerald-700">
                    مشاهده گفت‌وگو
                </a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($recentMessages as $message)
                    <div class="px-6 py-4 flex gap-4">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            {{ mb_substr($message->user->full_name ?? $message->user->name ?? '؟', 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-gray-800">
                                    {{ $message->user->full_name ?? $message->user->name ?? 'کاربر' }}
                                </span>
                                <span class="text-xs text-gray-400">
                                    {{ verta($message->created_at)->format('Y/m/d H:i') }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 leading-relaxed">
                                {{ \Illuminate\Support\Str::limit(strip_tags($message->message), 160) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">سایر گروه‌های مجمع عمومی</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($generalGroups->take(6) as $item)
                    <li>
                        <a href="{{ route('groups.show', $item) }}"
                           class="flex items-center justify-between px-6 py-4 hover:bg-emerald-50 transition">
                            <span class="text-sm font-medium text-gray-700">{{ $item->name }}</span>
                            <i class="fas fa-chevron-left text-xs text-gray-400"></i>
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-4 text-sm text-gray-400">گروهی ثبت نشده است.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-700">گروه‌های تخصصی پیشنهادی</h3>
            </div>
            <ul class="divide-y divide-gray-100">
                @forelse($specializedGroups->take(6) as $item)
                    <li>
                        <a href="{{ route('groups.show', $item) }}"
                           class="flex items-center justify-between px-6 py-4 hover:bg-emerald-50 transition">
                            <span class="text-sm font-medium text-gray-700">{{ $item->name }}</span>
                            <i class="fas fa-chevron-left text-xs text-gray-400"></i>
                        </a>
                    </li>
                @empty
                    <li class="px-6 py-4 text-sm text-gray-400">گروه تخصصی فعالی وجود ندارد.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

