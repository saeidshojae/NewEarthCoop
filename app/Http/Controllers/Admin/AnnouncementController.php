<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $announcements = Announcement::all();
        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->validate([
            'title' => 'required',
            'content' => 'required',
            'group_level' => 'required'
        ]);
        
        $thisLevelGroups = \App\Models\Group::where('location_level', $inputs['group_level'])->get();
        foreach($thisLevelGroups as $group){
            $newMessage = \App\Models\Message::create(['group_id' => $group->id, 'user_id' => auth()->user()->id, 'message' => 'اطلاعیه پین شده']);
            \App\Models\PinnedMessage::create(['message_id' => $newMessage->id, 'group_id' => $group->id, 'pinned_by' => auth()->user()->id]);
        }

        Announcement::create($inputs);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $ann = Announcement::findOrFail($id);
        $ann->delete();
        return back();
    }
}
