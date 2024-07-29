<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view-projects|create-projects|update-projects|delete-projects', ['only' => ['index','store']]);
        $this->middleware('permission:create-projects', ['only' => ['create','store']]);
        $this->middleware('permission:update-projects', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-projects', ['only' => ['destroy']]);
    }

    public function index()
    {
        if (system_role()) {
            $data['projects'] = Project::with('company:id,name')->where('is_enable', 1)->orderBy('company_id')->get();
        } else {
            $data['projects'] = Project::where('is_enable', 1)->where('company_id', user_company_id())->orderBy('id')->get();
        }
        return view('projects.list', $data);
    }

    public function create()
    {
        $user = Auth::user();
        $department_id = $user->department_id;

        if($department_id){
            $data['departments'] = Department::where('is_enable', 1)->where('id', $department_id)->where('company_id', user_company_id())->get();
        }
        else{
            $data['departments'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->get();
        }

        // $data['departments'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->orderBy('id')->get();
        $data['status'] = config('constants.PROJECT_STATUS_LIST');
        return view('projects.create', $data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $project = new Project();
        
        $project['name']        = $request->name;
        $project['description'] = $request->description ?? null;
        $project['plan']        = $request->project_plan ?? null;
        $project['department_id'] = $request->department_id ?? null;
        $project['ref_url']     = $request->ref_url ?? null;
        $project['deadline']    = $request->deadline ?? null;
        $project['company_id']  = user_company_id();
        $project['status']      = $request->status;
        $project['created_by']  = Auth::id();
        
        $response = $project->save();
    
        return redirect()->route('projects.list')->with('success','Project created successfully');
    }

    public function edit($id)
    {
        $user = Auth::user();
        $department_id = $user->department_id;

        if($department_id){
            $data['departments'] = Department::where('is_enable', 1)->where('id', $department_id)->where('company_id', user_company_id())->get();
        }
        else{
            $data['departments'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->get();
        }

        $data['status'] = config('constants.PROJECT_STATUS_LIST');
        $data['project'] = Project::find($id);
        // $data['departments'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->orderBy('id')->get();
        return view('projects.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $post_data['name']          = $request->name;
        $post_data['description']   = $request->description;
        $post_data['plan']          = $request->project_plan;
        $post_data['department_id'] = $request->department_id;
        $post_data['ref_url']       = $request->ref_url;
        $post_data['deadline']      = $request->deadline;
        $post_data['status']        = $request->status;
        $post_data['updated_by']    = Auth::id();

        $project = Project::find($request->id);
        $response = $project->update($post_data);
    
        return redirect()->route('projects.list')->with('success','Data updated successfully');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete(); // Soft delete

        return redirect()->route('projects.list')->with('success', 'Record deleted successfully.');
    }

    public function show($id)
    {
        $project_id = base64_decode($id);
        $data['project'] = Project::with('comments.user', 'attachments')->find($project_id);
        $data['ref_url'] = explode('*', $data['project']->ref_url);

        // $raw_urls = explode('*', $data['project']->ref_url);
        // $data['ref_url'] = array_map(function($url) {
        //     if (!preg_match('/^(http:\/\/|https:\/\/)/', $url)) {
        //         return 'https://' . $url;
        //     }
        //     return $url;
        // }, $raw_urls);

        return view('projects.show', $data);
    }

    public function soft_delete_functions($id)
    {
        // Soft delete a record
        $record = Project::find($id);
        $record->delete();

        // Querying soft deleted records
        $allRecords = Project::withTrashed()->get(); // Includes soft deleted
        $trashedRecords = Project::onlyTrashed()->get(); // Only soft deleted

        // Restoring a soft deleted record
        $trashedRecord = Project::withTrashed()->find($id);
        $trashedRecord->restore();

        // Permanently deleting a record
        $trashedRecord->forceDelete();
    }
}
