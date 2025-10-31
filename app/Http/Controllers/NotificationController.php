<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    
 public function fetch()
{
    $notifications = auth()->user()->notifications()->latest()->take(10)->get();

    return response()->json($notifications);
}



    public function markAsRead($id)
        {
            $notification = \App\Models\Notification::where('id', $id)
                ->where('user_id', auth()->id())
                ->first();

            if ($notification) {
                $notification->update(['is_read' => true]);
            }

    return response()->json(['success' => true]);
}

}
