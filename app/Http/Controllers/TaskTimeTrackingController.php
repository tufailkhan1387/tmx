<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskTimeTracking;
use Illuminate\Support\Facades\Auth;

class TaskTimeTrackingController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'date'      => 'required',
            'summary'   => 'required',
            'time'  => 'required',
        ]);

        $tracking = new TaskTimeTracking();

        $tracking['user_id'] = Auth::id();
        $tracking['task_id'] = $request->task_id;
        $tracking['summary'] = $request->summary;
        $tracking['date']    = $request->date;
        $tracking['time']    = $request->time;

        $tracking->save();

        return redirect()->route('tasks.show', ['id' => base64_encode($request->task_id)])->with('success', 'Time tracking added successfully');
    }
}
