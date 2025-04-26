<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Later you can filter by audience (ex: only show "student" notifications for students)
        $notifications = Notification::latest()->take(10)->get();

        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return response()->json(['message' => 'Notification marked as read']);
    }
}
