<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // Show all notifications for the authenticated user
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // Return unread notifications count (JSON)
    public function unreadCount()
    {
        $count = Auth::user()->unreadNotifications()->count();
        return response()->json(['count' => $count]);
    }

    // Return latest notifications (JSON)
    public function latest()
    {
        $items = Auth::user()->notifications()->latest()->take(10)->get();
        return response()->json($items);
    }

    // Mark a single notification as read
    public function markAsRead(string $id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return back()->with('success', 'اعلان خوانده شد.');
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'همه اعلان‌ها خوانده شد.');
    }

    // Delete a single notification
    public function destroy(string $id)
    {
        Auth::user()->notifications()->where('id', $id)->delete();
        return back()->with('success', 'اعلان حذف شد.');
    }

    // Delete all read notifications
    public function deleteAllRead()
    {
        Auth::user()->readNotifications()->delete();
        return back()->with('success', 'اعلان‌های خوانده شده حذف شد.');
    }
}
