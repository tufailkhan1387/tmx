<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectAttachment;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Notification;

class ProjectAttachmentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|max:2048',
        ]);

        $project_id = $request->project_id;

        if ($request->file('file')) {
            $file       = $request->file('file');
            $file_name  = time() . '_' . uniqid('', true) . '.' . $file->getClientOriginalExtension();
            $org_name   = $file->getClientOriginalName();
            $request->file('file')->storeAs('public/projects_file/', $file_name);

            $file_data = new ProjectAttachment();

            $file_data['project_id']    = $project_id;
            $file_data['file_name']     = $org_name;
            $file_data['path']          = $file_name;
            $file_data['created_by']    = Auth::id();

            $file_data->save();

            // Notification
            $project = Project::with('users')->find($project_id);
            
            $notification = new Notification();

            $notification['project_id']    = $request->project_id;
            $notification['title']      = 'New Attachment';
            $notification['message']    = 'A new attachment is added by '. Auth::user()->name;
            $notification['created_by'] = Auth::id();

            if (Auth::id() == $project->created_by) {
                // If user is creator, notify the assigned user
                $notification['user_id'] = $project->users[0]->id;
            } else {
                // If user is assigned user, notify the creator
                $notification['user_id'] = $project->created_by;
            }

            $notification->save();

            if($file_data){
                return response()->json(['success' => 'File uploaded'], 200);
            }
        }
    }
}
