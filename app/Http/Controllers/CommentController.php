<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use App\Models\Task;
use App\Models\User;
use App\Services\PushNotificationService;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'comment' => 'required',
        ]);

        $comment = new Comment();

        $comment['user_id'] = Auth::id();
        $comment['task_id'] = $request->task_id;
        $comment['comment'] = $request->comment;
        $comment['is_private'] = 2;


        $comment->save();


        // Notification
        $task = Task::with('users')->find($request->task_id);
        $notification = new Notification();
        $notification['task_id']    = $request->task_id;
        $notification['title']      = 'New Comment';
        $notification['message']    = 'A new comment is added by ' . Auth::user()->name;
        $notification['created_by'] = Auth::id();

        if (Auth::id() == $task->created_by) {
            // If the commenter is the creator, notify the assigned user
            $notification['user_id'] = $task->users[0]->id;
        } else {
            // If the commenter is the assigned user, notify the creator
            $notification['user_id'] = $task->created_by;
        }

        $user = User::where('id',$notification['user_id'])->select('fcm_token')->first();
        sendNotification($user->fcm_token, 'New Comment', 'A new comment is added by ' . Auth::user()->name);
        $notification->save();

        // Notification for tagged user
        if ($request->userId) {
            $task = Task::with('users')->find($request->task_id);
            $tagged_user_notificaton = new Notification();
            $tagged_user_notificaton['task_id']    = $request->task_id;
            $tagged_user_notificaton['title']      = 'New Comment';
            $tagged_user_notificaton['message']    = 'You are tagged by ' . Auth::user()->name;
            $tagged_user_notificaton['created_by'] = Auth::id();
            $tagged_user_notificaton['user_id'] = $request->userId;
            $tagged_user_notificaton->save();

            // push notification to tagged user
            $tagged_user_id = [$request->userId];
            $tagged_user_post = [
                'notification_message' => 'You are tagged in comment',
                'url' => route('tasks.show', ['id' => base64_encode($notification['task_id'])])
            ];

            $push_notification_to_tagged_user = new PushNotificationService();
            $push_notification_to_tagged_user->send($tagged_user_post, $tagged_user_id);
            $user = User::where('id',$tagged_user_notificaton['user_id'])->select('fcm_token')->first();
            sendNotification($user->fcm_token, 'New Comment', 'You are tagged by ' . Auth::user()->name);
        }


        // push notification
        $msg_post = [
            'notification_message' => 'A new comment is added',
            'url' => route('tasks.show', ['id' => base64_encode($notification['task_id'])])
        ];

        $user_ids = [$notification['user_id']];

        $push_notification = new PushNotificationService();
        $push_notification->send($msg_post, $user_ids);


        return redirect()->route('tasks.show', ['id' => base64_encode($request->task_id)])->with('success', 'Comment added successfully');
    }

    public function store_images(Request $request)
    {
       
        $request->validate([
            'file' => 'required|max:10240',
        ]);

        $comment = new Comment();
        $comment['user_id'] = Auth::id();
        $comment['task_id'] = $request->task_id;
        if(!$request->comment){
            $comment['comment'] = 'file';
        }
        else{
            $comment['comment'] = $request->comment;
        }
        $comment['is_private'] = 2;
        $comment->save();
        // Notification
        $task = Task::with('users')->find($request->task_id);
        $notification = new Notification();
        $notification['task_id']    = $request->task_id;
        $notification['title']      = 'New Comment';
        $notification['message']    = 'A new comment is added by ' . Auth::user()->name;
        $notification['created_by'] = Auth::id();

        if (Auth::id() == $task->created_by) {
            // If the commenter is the creator, notify the assigned user
            $notification['user_id'] = $task->users[0]->id;
        } else {
            // If the commenter is the assigned user, notify the creator
            $notification['user_id'] = $task->created_by;
        }
        $notification->save();
        // push notification
        $msg_post = [
            'notification_message' => 'A new comment is added',
            'url' => route('tasks.show', ['id' => base64_encode($notification['task_id'])])
        ];

        $user_ids = [$notification['user_id']];

        $push_notification = new PushNotificationService();
        $push_notification->send($msg_post, $user_ids);
        $task_id = $request->task_id;

        if ($request->file('file')) {
            $file       = $request->file('file');
            $file_name  = time() . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
            $org_name   = $file->getClientOriginalName();
            $request->file('file')->storeAs('public/comment_images/', $file_name);

            $file_data = new CommentImage();

            $file_data['comment_id']       = $comment->id;
            $file_data['file_name']     = $org_name;
            $file_data['path']          = $file_name;
            $file_data['created_by']    = Auth::id();

            $file_data->save();

            // Notification
            $task = Task::with('users')->find($task_id);
            
            $notification = new Notification();

            $notification['task_id']    = $request->task_id;
            $notification['title']      = 'New Attachment';
            $notification['message']    = 'A new attachment is added by '. Auth::user()->name;
            $notification['created_by'] = Auth::id();

            if (Auth::id() == $task->created_by) {
                // If user is creator, notify the assigned user
                $notification['user_id'] = $task->users[0]->id;
            } else {
                // If user is assigned user, notify the creator
                $notification['user_id'] = $task->created_by;
            }

            $notification->save();

            if($file_data){
                return response()->json(['success' => 'File uploaded'], 200);
            }
        }
    }
}
