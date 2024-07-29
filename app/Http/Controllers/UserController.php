<?php

namespace App\Http\Controllers;

use App\Mail\UserWelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

//models
use App\Models\User;
use App\Models\Department;
use App\Models\Company;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware('permission:view-users|create-users|update-users|delete-users', ['only' => ['index', 'store']]);
        $this->middleware('permission:create-users', ['only' => ['create', 'store']]);
        $this->middleware('permission:update-users', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-users', ['only' => ['destroy']]);
        parent::__construct();
    }

    public function index()
    {
        if (system_role()) {
            $data['users'] = User::with('company')->orderBy('id')->skip(1)->take(PHP_INT_MAX)->get();
            $data['companies'] = Company::where('is_enable', 1)->pluck('name', 'id')->all();
        } else {
            $department_id = Auth::user()->department_id;
            $data['users'] = $department_id ? User::with('department')->where('department_id', $department_id)->where('company_id', user_company_id())->orderBy('id')->skip(1)->take(PHP_INT_MAX)->get() : User::with('department')->where('company_id', user_company_id())->orderBy('id')->skip(1)->take(PHP_INT_MAX)->get();
        }

        return view('users.list', $data);
    }

    public function create()
    {
        $data['user'] = Auth::user();
        if (system_role()) {
            $data['roles'] = Role::whereNull('company_id')->orderBy('id')->skip(1)->take(PHP_INT_MAX)->get();
            $data['companies'] = Company::where('is_enable', 1)->pluck('name', 'id')->all();
        } else {
            $data['roles'] = Role::where('company_id', user_company_id())->orderBy('id')->get();
        }
        $data['departments'] = Department::where('is_enable', 1)->get();
        return view('users.create', $data);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $this->validate($request, [
            'name'          => 'required',
            'company_id'    => 'required',
            'scope'         => 'required',
            'roles'         => 'required',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required',
            'profile_pic'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joining_date'  => 'nullable|date_format:Y-m-d',
            'expiry_date'   => 'nullable|date_format:Y-m-d',
            'phone'         => 'nullable|numeric',
            'whatsapp'      => 'nullable|numeric',
        ]);

        // Handle the profile picture file upload
        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $image_name = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $org_name = $image->getClientOriginalName();
            $request->file('profile_pic')->storeAs('public/profile_pics/', $image_name);
        }

        // Create a new user instance
        $user = new User();
        $user->name         = $request->name;
        $user->email        = $request->email;
        $user->scope        = $request->scope;
        $user->company_id   = $request->company_id;
        $user->password     = Hash::make($request->password);
        $user->joining_date = $request->joining_date;
        $user->expiry_date  = $request->expiry_date;
        $user->start_time   = $request->start_time;
        $user->end_time     = $request->end_time;
        $user->phone        = $request->phone;
        $user->whatsapp     = $request->whatsapp;
        $user->created_by   = Auth::id();
        $user->department_id = $request->department ?? NULL;

        if ($request->hasFile('profile_pic')) {
            $user->profile_pic = $image_name;
        }

        // Save the user and send the welcome email
        $response = $user->save();
        if ($response) {
            $user->assignRole($request->roles);

            try {
                Mail::to($user->email)->send(new UserWelcomeMail($user, $request->password));
            } catch (\Exception $e) {
                // return redirect()->route('users.list')->with('warning', 'User created successfully, but the welcome email could not be sent.');
            }
            return redirect()->route('users.list')->with('success', 'User created successfully and welcome email sent.');
        } else {
            return redirect()->back()->with('error', 'User was not created.');
        }
    }

    public function edit($id)
    {
        $data['user'] = User::with('company:id,name')->find($id);
        if (system_role()) {
            $data['user_role'] = $data['user']->roles->pluck('name', 'name')->all();
            $data['roles'] = Role::whereNull('company_id')->orderBy('id')->skip(1)->take(PHP_INT_MAX)->get();
            $data['companies'] = Company::where('is_enable', 1)->pluck('name', 'id')->all();
        } else {
            $data['roles'] = Role::where('company_id', user_company_id())->orderBy('id')->get();
        }
        $data['departments'] = Department::where('is_enable', 1)->get();
        return view('users.edit', $data);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'roles'         => 'required',
            'email'         => 'required|email|unique:users,email,' . $request->id,
            'profile_pic'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'joining_date'  => 'nullable|date_format:Y-m-d',
            'expiry_date'   => 'nullable|date_format:Y-m-d',
            'phone'         => 'nullable|numeric',
            'whatsapp'      => 'nullable|numeric',
        ]);

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $image_name = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $request->file('profile_pic')->storeAs('public/profile_pics/', $image_name);
        }

        $post_data['name']         = $request->name;
        $post_data['email']        = $request->email;
        $post_data['company_id']   = $request->company_id;;
        $post_data['scope']        = $request->scope;;
        $post_data['joining_date'] = $request->joining_date;
        $post_data['expiry_date']  = $request->expiry_date;
        $post_data['start_time']   = $request->start_time;
        $post_data['end_time']     = $request->end_time;
        $post_data['phone']        = $request->phone;
        $post_data['whatsapp']     = $request->whatsapp;
        $post_data['updated_by']   = Auth::id();
        $post_data['department_id'] = $request->department ?? NULL;

        if ($request->hasFile('profile_pic')) {
            $post_data['profile_pic'] = $image_name;
        }


        if (!empty($request->password)) {
            $post_data['password'] = Hash::make($request->password);
        }

        $user = User::find($request->id);
        $response = $user->update($post_data);

        DB::table('model_has_roles')->where('model_id', $request->id)->delete();

        $user->assignRole($request->roles);

        return redirect()->route('users.list')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete(); // Soft delete

        return redirect()->route('users.list')->with('success', 'Record deleted successfully.');
    }

    public function profile()
    {
        $data['user'] = Auth::user();
        return view('users.profile', $data);
    }

    public function profile_update(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required|alpha_space',
            'profile_pic'   => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'phone'         => 'nullable|numeric',
            'whatsapp'      => 'nullable|numeric',
        ]);

        if ($request->hasFile('profile_pic')) {
            $image = $request->file('profile_pic');
            $image_name = time() . '_' . uniqid('', true) . '.' . $image->getClientOriginalExtension();
            $request->file('profile_pic')->storeAs('public/profile_pics/', $image_name);
        }

        $user_id = Auth::id();

        $post_data['name']         = $request->name;
        $post_data['phone']        = $request->phone;
        $post_data['whatsapp']     = $request->whatsapp;
        $post_data['updated_by']   = $user_id;

        if ($request->hasFile('profile_pic')) {
            $post_data['profile_pic'] = $image_name;
        }


        if (!empty($request->password)) {
            $post_data['password'] = Hash::make($request->password);
        }

        $user = User::find($user_id);
        $response = $user->update($post_data);

        return redirect()->route('users.profile')->with('success', 'User updated successfully');
    }

    public function show($id)
    {
        $data['user'] = User::find($id);

        // Get all tasks assigned to the user
        $tasks = Task::with('users')->where('is_enable', 1)
            ->whereHas('users', function ($query) use ($id) {
                $query->where('task_user.user_id', $id);
            })
            ->get();

        // Get task statuses from config
        $taskStatus = Config::get('constants.TASK_STATUS');

        // Total tasks assigned to the user
        $totalTasks = $tasks->count();

        // Completed tasks
        $completedTasks = $tasks->where('status', $taskStatus['Completed'])->count();

        // Missed tasks (assuming 'Revision' indicates a missed task)
        $missedTasks = $tasks->where('status', $taskStatus['Revision'])->count();

        // Assigned tasks (tasks that are neither completed nor missed)
        $assignedTasks = $tasks->whereNotIn('status', [$taskStatus['Completed'], $taskStatus['Revision']])->count();

        // Closed tasks
        $closedTasks = $tasks->where('status', $taskStatus['Closed'])->count();

        // Calculate performance
        $performance = $totalTasks > 0 ? ($closedTasks / $totalTasks) * 100 : 0;

        // Add task data to the $data array
        $data['tasks'] = $tasks;
        $data['totalTasks'] = $totalTasks;
        $data['completedTasks'] = $completedTasks;
        $data['missedTasks'] = $missedTasks;
        $data['assignedTasks'] = $assignedTasks;
        $data['performance'] = $performance;

        $user = $data['user'];
        $department_id = $user->department_id;
        $data['department_id'] = $department_id;

        if ($department_id) {
            if ($user->scope == 2) {
                $data['tasks'] = Task::where('is_enable', 1)
                    ->where('department_id', $department_id)
                    ->with('project', 'department', 'users', 'creator')
                    ->orderBy('id', 'desc')
                    ->get();
                $data['users'] = User::where('is_enable', 1)
                    ->where('department_id', $department_id)
                    ->where('company_id', user_company_id())
                    ->get();
            } else {
                $data['tasks'] = Task::whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('is_enable', 1)
                    ->with('project', 'department', 'users', 'creator')
                    ->orderBy('id', 'desc')
                    ->get();
                $data['users'] = User::where('is_enable', 1)
                    ->where('id', $user->id)
                    ->where('department_id', $department_id)
                    ->where('company_id', user_company_id())
                    ->get();
            }
            $data['departments'] = Department::where('is_enable', 1)
                ->where('id', $department_id)
                ->where('company_id', user_company_id())
                ->get();
        } else {
            $data['tasks'] = Task::where('is_enable', 1)
                ->with('project', 'department', 'users', 'creator')
                ->orderBy('id', 'desc')
                ->get();
            $data['users'] = User::where('is_enable', 1)
                ->where('company_id', user_company_id())
                ->get();
            $data['departments'] = Department::where('is_enable', 1)
                ->where('company_id', user_company_id())
                ->get();
        }

        $data['projects'] = Project::where('is_enable', 1)
            ->where('company_id', user_company_id())
            ->get();
        // return $data['performance'];
        return view('users.show', $data);
    }

    public function filter_by_date(Request $request)
    {
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $userId = $request->userId;

        // Ensure startDate, endDate, and userId are not empty
        if (empty($startDate) || empty($endDate) || empty($userId)) {
            return response()->json(['error' => 'Start date, end date, and user ID are required'], 400);
        }

        // Get task statuses from config
        $taskStatus = Config::get('constants.TASK_STATUS');

        // Filter tasks between start date and end date and by user ID
        $tasks = Task::with('users','project','creator')
            ->where('is_enable', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->whereHas('users', function ($query) use ($userId) {
                $query->where('task_user.user_id', $userId);
            })
            ->get();

        // Total tasks
        $totalTasks = $tasks->count();

        // Completed tasks
        $completedTasks = $tasks->where('status', $taskStatus['Completed'])->count();

        // Missed tasks (assuming 'Revision' indicates a missed task)
        $missedTasks = $tasks->where('status', $taskStatus['Revision'])->count();

        // Assigned tasks (tasks that are neither completed nor missed)
        $assignedTasks = $tasks->whereNotIn('status', [$taskStatus['Completed'], $taskStatus['Revision']])->count();

        // Closed tasks
        $closedTasks = $tasks->where('status', $taskStatus['Closed'])->count();

        // Calculate performance
        $performance = $totalTasks > 0 ? number_format(($closedTasks / $totalTasks) * 100, 1) : 0;

        // Prepare response data
        $data = [
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'missedTasks' => $missedTasks,
            'assignedTasks' => $assignedTasks,
            'performance' => $performance,
            'tasks' => $tasks
        ];

        return response()->json($data);
    }





    public function users_by_role(Request $request)
    {
        // fetch users by specific role
        $roles = Role::find($request->role_id);
        $users = User::role($roles->name)->where('is_enable', 1)->orderBy('id')->get()->toArray();
        $users_list = array_column($users, 'name', 'id');

        return response()->json(['users' => $users_list]);
    }
    public function users_by_department(Request $request)
    {
        // Check if department name is provided
        if (empty($request->departmentName)) {
            // Fetch all users if department name is empty
            $users = User::where('is_enable', 1)->get()->toArray();
        } else {
            // Fetch users by specific department
            $department = Department::where('name', $request->departmentName)->first();
            if ($department) {
                $users = User::where('department_id', $department->id)
                    ->where('is_enable', 1)
                    ->get()
                    ->toArray();
            } else {
                // Return an empty array if the department is not found
                $users = [];
            }
        }

        // Create a list of users with 'id' as the key and 'name' as the value
        $users_list = array_column($users, 'name', 'id');

        return response()->json(['users' => $users_list]);
    }
}
