<div class="message-row {{ $item->user_id === auth()->id() ? 'you' : 'other' }}"
     data-message-id="{{ $item->id }}" id="msg-{{ $item->id }}">
    @php
        $sender   = optional($group->users)->firstWhere('id', $item->user_id);
        $isMine   = $item->user_id === auth()->id();
        $initials = $sender && $sender->first_name
            ? mb_substr($sender->first_name, 0, 1)
            : 'U';
    @endphp

    {{-- آواتار --}}
    @if($item->user_id !== optional(auth()->user())->id)
        <a href="{{ route('profile.member.show', $item->user_id) }}">
            <div class="avatar">{{ $initials }}</div>
        </a>
    @endif

    <div class="message-bubble"
         data-message-id="{{ $item->id }}" style="max-width: 65%;"
         data-edit-url="{{ route('groups.messages.edit', $item->id) }}"
         data-delete-url="{{ route('groups.messages.delete', $item->id) }}"
         data-report-url="{{ route('messages.report', $item->id) }}"
         data-content-raw="{{ e(strip_tags($item->content ?? '')) }}">

        {{-- نام فرستنده --}}
        @if(!$isMine && $sender)
            <a href="{{ route('profile.member.show', $item->user_id) }}">
                <strong class="message-sender">
                    {{ $sender->first_name ?? '' }} {{ $sender->last_name ?? '' }}
                </strong>
            </a>
        @endif

        {{-- حساب حذف‌شده --}}
        @if(!$item->user)
            <div class="message-sender" style="margin-left:.4rem;margin-bottom:.5rem">
                <a href="#" style="color:blue;font-weight:900">حساب حذف شده</a>
            </div>
        @endif

        {{-- پیش‌نمایش پاسخ --}}
        @if ($item->parent_id)
            @php
                $pid         = $item->parent_id;
                $replySender = '';
                $replyText   = '';
                $link        = null;

                if (\Illuminate\Support\Str::startsWith($pid, 'poll-')) {
                    $id     = (int) \Illuminate\Support\Str::after($pid, 'poll-');
                    $parent = \App\Models\Poll::with('user')->find($id);

                    $replySender = trim((optional($parent->user)->first_name ?? '') . ' ' . (optional($parent->user)->last_name ?? ''));
                    $replyText   = $parent->title ?? $parent->question ?? 'Poll';
                    $link        = $parent ? '#poll-' . $id : null;

                } elseif (\Illuminate\Support\Str::startsWith($pid, 'post-')) {
                    $id     = (int) \Illuminate\Support\Str::after($pid, 'post-');
                    $parent = \App\Models\Blog::with('user')->find($id);

                    $replySender = trim((optional($parent->user)->first_name ?? '') . ' ' . (optional($parent->user)->last_name ?? ''));
                    $replyText   = $parent->title ?? 'Post';
                    $link        = $parent ? '#blog-' . $id : null;

                } else {
                    $parent      = $item->parent ?? null;
                    $replySender = trim((optional(optional($parent)->user)->first_name ?? '') . ' ' . (optional(optional($parent)->user)->last_name ?? ''));
                    $replyText   = $parent->message ?? '';
                    $link        = $parent ? '#msg-' . $parent->id : null;
                }

                $replyText = \Illuminate\Support\Str::limit(trim((string) $replyText), 80);
            @endphp

            <div class="reply-preview">
                @if($replySender)
                    <div class="reply-sender">{{ $replySender }}</div>
                @endif
                <div class="reply-text">
                    @if($link)
                        <a href="{{ $link }}">{!! $replyText !!}</a>
                    @else
                        {!! $replyText !!}
                    @endif
                </div>
            </div>
        @endif

        {{-- متن پیام --}} 
        <p class="message-content">{!! $item->content ?? '' !!}</p>

        {{-- منوی سه‌نقطه --}}
        <details class="menu-wrapper {{ $isMine ? 'left' : 'right' }}">
            <summary class="menu-trigger">⋮</summary>
            <div class="menu-dropdown"> 
                <button type="button"
                        onclick="replyToMessage('{{ $item->id }}', `{{ trim(($sender->first_name ?? '') . ' ' . ($sender->last_name ?? '')) }}`, `{!! $item->content ?? '' !!}`)"
                        class="menu-item btn-rep">
                    <i class="fas fa-reply me-2"></i> پاسخ
                </button>

                @unless($isMine)
                    <button type="button" class="menu-item btn-report">
                        <i class="fas fa-flag me-2"></i> گزارش
                    </button>
                @endunless

                @if($isMine)
                    <button type="button" class="menu-item btn-edit">
                        <i class="fas fa-edit me-2"></i> ویرایش
                    </button>
                    <button type="button" class="menu-item btn-delete text-danger">
                        <i class="fas fa-trash me-2"></i> حذف
                    </button>
                @endif

                <div class="menu-meta-time">
                    {{ verta(optional($item->created_at)->format('Y-m-d H:i:s')) }}
                </div>
            </div>
        </details>
    </div>
</div>
