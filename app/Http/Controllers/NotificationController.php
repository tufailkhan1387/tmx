<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function list()
    {
        $data['notifications'] = Notification::where('user_id', Auth::id())->get();
        return view('notifications.list', $data);
    }

    public function read($id)
    {
        $id = base64_decode($id);
        $notification = Notification::find($id);
        $post_data['is_read'] = 1;
        $notification->update($post_data);

        return redirect()->route('tasks.show', base64_encode($notification->task_id));
    }

    public function read_all()
    {
        Notification::where('user_id', Auth::id())->update(['is_read' => 1]);
        
        return response()->json(['status' => 'success']);
    }
}
