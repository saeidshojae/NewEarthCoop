<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\Election;
use App\Models\Group;
use App\Models\Reaction;
use Illuminate\Http\Request;
use App\Models\PollVote;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    public function index(){
        $blogs = Blog::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $comments = Comment::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $reactions = Reaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $polls = PollVote::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        $elections = Vote::where('voter_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('history.index', compact('blogs', 'comments', 'reactions', 'polls', 'elections'));
    }

    public function election(){


        $currentElections = Election::with(['group', 'yourVotes.user'])
        ->whereHas('group.users', function ($query) {
            $query->whereKey(auth()->id());  // به جای where('users.id', ...)
        })
        ->where('starts_at', '<=', now())
        ->where('ends_at', '>=', now())
        ->get();
    

        return view('history.election', compact('currentElections'));
    }

    public function poll(){
        return view('history.poll');
    }
}
