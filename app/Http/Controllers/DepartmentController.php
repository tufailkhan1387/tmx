<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// models
use App\Models\Department;
use App\Models\Company;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view-departments|create-departments|update-departments|delete-departments', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-departments', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-departments', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-departments', ['only' => ['destroy']]);
    }

    public function index()
    {
        if (system_role()) {
            $data['departments'] = Department::with('company:id,name')->where('is_enable', 1)->orderBy('company_id')->get();
        } else {
            $data['departments'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->orderBy('id')->get();
        }
        return view('departments.list', $data);
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'members'     => 'required',
        ]);

        $department = new Department();

        $department['name']        = $request->name;
        $department['description'] = $request->description;
        $department['members']     = $request->members;
        $department['email']       = $request->email ?? null;
        $department['company_id']  = user_company_id();
        $department['created_by']  = Auth::id();

        $response = $department->save();

        return redirect()->route('departments.list')->with('success', 'Department created successfully');
    }

    public function edit($id)
    {
        $data['department'] = Department::where('is_enable', 1)->find($id);
        return view('departments.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'members'     => 'required',
        ]);


        $post_data['name']        = $request->name;
        $post_data['description'] = $request->description;
        $post_data['members']     = $request->members;
        $post_data['email']       = $request->email ?? null;
        $post_data['company_id']  = user_company_id();
        $post_data['updated_by']  = Auth::id();

        $department = Department::find($request->id);
        $response   = $department->update($post_data);

        return redirect()->route('departments.list')->with('success', 'Data successcully updated!');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete($department);

        return redirect()->route('departments.list')->with('success', 'Data deleted successfully!');
    }
}
