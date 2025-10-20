<?php

namespace App\Http\Controllers\Group;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Message;
use App\Models\PinnedMessage;
use App\Models\ReportedMessage;
use App\Models\Blog;
use App\Models\Poll;
use Illuminate\Support\Str;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'message' => 'nullable|string|max:2000',
            'group_id' => 'required|exists:groups,id',
            'parent_id' => 'nullable',
            'file' => 'nullable|file|max:20480', // 20MB max file size
            'voice_message' => 'nullable|string',
        ]);
        

        $group = Group::findOrFail($request->group_id);

        $group->update(['last_activity_at' => now()]);
        $user = auth()->user();
        $groupUserRole = GroupUser::where('group_id', $group->id)->where('user_id', $user->id)->value('role');

// ✅ جایگزین کن
$member = $group->users()->whereKey($user->id)->exists();

if (!$member || ($groupUserRole === 0 && $group->is_open == 0)) {
    return response()->json(['status' => 'error', 'message' => 'شما مجوز کافی را ندارید.'], 403);
}


        $messageData = [
            'user_id' => $user->id,
            'group_id' => $group->id,
            'message' => $request->message,
            'parent_id' => $request->parent_id,
        ];

        $message = Message::create($messageData);

        $response = [
            'status' => 'success',
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'message' => $message->message,
                'created_at' => $message->created_at->format('H:i'),
                'sender' => $user->first_name . ' ' . $user->last_name,
                'parent_id' => $message->parent_id,
                'file_path' => $message->file_path,
                'file_type' => $message->file_type,
                'file_name' => $message->file_name,
            ]
        ];
        
        if ($message->parent_id) {
            $parentId = $message->parent_id;
        
            if (Str::startsWith($parentId, 'poll-')) {
                $id = (int) Str::after($parentId, 'poll-');
                $parent = Poll::with('user')->find($id);
                $response['message']['parent_sender'] = $parent->user->first_name . ' ' . $parent->user->last_name;
                $response['message']['parent_content'] = $parent->title ?? $parent->question ?? '';
            } elseif (Str::startsWith($parentId, 'post-')) {
                $id = (int) Str::after($parentId, 'post-');
                $parent = Blog::with('user')->find($id);
                $response['message']['parent_sender'] = $parent->user->first_name . ' ' . $parent->user->last_name;
                $response['message']['parent_content'] = $parent->title ?? '';
            } else {
                    $parentMessage = Message::with('user')->find($message->parent_id);
                    $response['message']['parent_sender'] = $parentMessage->user->first_name . ' ' . $parentMessage->user->last_name;
                    $response['message']['parent_content'] = $parentMessage->message;
            }
        }



        return response()->json($response);
    }

    public function edit(Request $request, Message $message)
    {

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message->message = $request->content;
        $message->save();
        $message->update([
            'message' => $request->content,
            'edited' => true,
            'edited_by' => auth()->user()->id

        ]);

        return response(['message' => 'پیام با موفقیت ویرایش شد']);
    }
    
    public function delete(Request $request, Message $message)
    {
        if(isset($_GET['admin'])){
            if($message->removed_by == null){
                $message->removed_by = $_GET['admin'];
                $message->save();
                        return back()->with('success', 'پیام با موفقیت از جانب مدیر حذف شد');

            }else{
                $message->removed_by = null;
                $message->save();
                        return back()->with('success', 'پیام با موفقیت بازگردانی شد');

            }
        }else{
            $message->delete();
            return back()->with('success', 'پیام با موفقیت حذف شد');
        }
    }

    public function pin(Message $message)
    {
        $group = $message->group;
        $user = auth()->user();
        $groupUserRole = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->value('role');

        // Only managers can pin messages
        if ($groupUserRole !== 3) {
            return response()->json(['status' => 'error', 'message' => 'شما مجوز کافی را ندارید.'], 403);
        }

        // Check if message is already pinned
        if (PinnedMessage::where('message_id', $message->id)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'این پیام قبلاً پین شده است.'], 400);
        }

        PinnedMessage::create([
            'message_id' => $message->id,
            'group_id' => $group->id,
            'pinned_by' => $user->id
        ]);

        return response()->json(['status' => 'success', 'message' => 'پیام با موفقیت پین شد.']);
    }

    public function unpin(Message $message)
    {
        $group = $message->group;
        $user = auth()->user();
        $groupUserRole = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->value('role');

        // Only managers can unpin messages
        if ($groupUserRole !== 3) {
            return response()->json(['status' => 'error', 'message' => 'شما مجوز کافی را ندارید.'], 403);
        }

        PinnedMessage::where('message_id', $message->id)->delete();

        return response()->json(['status' => 'success', 'message' => 'پیام با موفقیت از حالت پین خارج شد.']);
    }

    public function report(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        

        ReportedMessage::create([
            'message_id' => $id,
            'reported_by' => auth()->id(),
            'reason' => $validated['reason']
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'پیام با موفقیت گزارش شد.'
        ]);
    }
    
    
    
    
    public function search(Request $request, Group $group)
    {
        
        $q       = trim((string) $request->get('q', ''));
        $limit   = (int) min(max((int)$request->get('limit', 20), 5), 50);
        $page    = (int) max((int)$request->get('page', 1), 1);
        $offset  = ($page - 1) * $limit;

        if ($q === '') {
            return response()->json([
                'items'    => [],
                'page'     => $page,
                'has_more' => false,
            ]);
        }

        // نرمال‌سازی ساده فارسی/عربی + اعداد
        $norm = static function(string $t) {
            $map = [
                'ي'=>'ی','ك'=>'ک','ة'=>'ه','ئ'=>'ی','‌'=>' ','‏'=>' ',
                '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4','۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
            ];
            return strtr($t, $map);
        };
        $qNorm = $norm($q);
        $like  = '%'.str_replace(['%','_'], ['\%','\_'], $qNorm).'%';

        // --- Messages: بدون هیچ رابطه‌ای با Blog ---
        // Messages بدون with()
// Messages
$messages = \App\Models\Message::query()
    ->leftJoin('users', 'users.id', '=', 'messages.user_id')
    ->where('messages.group_id', $group->id)
    ->where('messages.message', 'like', $like)
    ->orderByDesc('messages.id')
    ->skip($offset)->take($limit)
    ->get([
        'messages.id',
        'messages.message',
        'messages.created_at',
        'users.first_name',
        'users.last_name',
    ])
    ->map(function($row) use ($qNorm) {
        $sender = trim(($row->first_name ?? '').' '.($row->last_name ?? '')) ?: 'کاربر';
        return [
            'type'    => 'message',
            'id'      => $row->id,
            'title'   => $sender,
            'snippet' => $this->makeSnippet($row->message ?? '', $qNorm),
            'date'    => optional($row->created_at)->format('Y-m-d H:i'),
            'url'     => '#msg-'.$row->id,
        ];
    })
    ->values()
    ->toBase(); // 👈 حتماً به Base Collection تبدیل کن

// Blogs
$blogs = \App\Models\Blog::query()
    ->where('group_id', $group->id)
    ->where(function($w) use ($like){
        $w->where('title','like',$like)->orWhere('content','like',$like);
    })
    ->orderByDesc('id')
    ->skip($offset)->take($limit)
    ->get(['id','title','content','created_at'])
    ->map(fn($b) => [
        'type'=>'post',
        'id'=>$b->id,
        'title'=>$b->title ?: 'پست',
        'snippet'=>$this->makeSnippet($b->content ?? '', $qNorm),
        'date'=>optional($b->created_at)->format('Y-m-d H:i'),
        'url'=>'#blog-'.$b->id,
    ])
    ->values()
    ->toBase(); // 👈

// Polls
$polls = \App\Models\Poll::query()
    ->where('group_id', $group->id)
    ->where(function($w) use ($like){
        $w->where('question','like',$like);
    })
    ->orderByDesc('id')
    ->skip($offset)->take($limit)
    ->get(['id','question','created_at'])
    ->map(function($p) use ($qNorm){
        $text = $p->question ?: $p->question;
        return [
            'type'=>'poll',
            'id'=>$p->id,
            'title'=>$p->question ?: 'نظرسنجی',
            'snippet'=>$this->makeSnippet($text ?? '', $qNorm),
            'date'=>optional($p->created_at)->format('Y-m-d H:i'),
            'url'=>'#poll-'.$p->id,
        ];
    })
    ->values()
    ->toBase(); // 👈

// ادغام امن (همه Base Collection هستند)
$items  = $messages->merge($blogs)->merge($polls)->sortByDesc('date')->values();
$slice  = $items->slice(0, $limit)->values();
$hasMore = $items->count() > $limit;


        return response()->json([
            'items'    => $slice,
            'page'     => $page,
            'has_more' => $hasMore,
        ]);
    }

    private function makeSnippet(string $htmlOrText, string $needle, int $radius = 40): string
    {
        $plain = trim(strip_tags($htmlOrText));
        $plain = preg_replace('/\s+/u', ' ', $plain);
        $pos = mb_stripos($plain, $needle);
        if ($pos === false) {
            return mb_substr($plain, 0, 80).'…';
        }
        $start = max(0, $pos - $radius);
        $len   = mb_strlen($needle);
        $before = mb_substr($plain, $start, $pos - $start);
        $match  = mb_substr($plain, $pos, $len);
        $after  = mb_substr($plain, $pos + $len, $radius);
        $prefix = $start > 0 ? '…' : '';
        $suffix = ($pos + $len + $radius) < mb_strlen($plain) ? '…' : '';
        return $prefix.$before.'<mark>'.$match.'</mark>'.$after.$suffix;
    }

}
