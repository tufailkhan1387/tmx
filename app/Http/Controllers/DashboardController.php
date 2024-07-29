<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PushNotificationService;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Task;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Log;
use App\Models\Company;
use App\Models\Department;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    public function index()
    {
        $user = Auth::user();
        $scope = $user->scope;
        $user->load('roles.permissions');
        

        // Get the roles and permissions
        $rolesWithPermissions = $user->roles->map(function ($role) {
            return [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name')
            ];
        });
        // return $rolesWithPermissions;
        $notes = $user->notes;
      
        $CONSTANTS = config('constants')['DESIGNATION_SCOPE'];

        switch ($scope) {
            case $CONSTANTS['Manage All Departments']:
                $result = Task::where('is_enable', 1)->get();
                $response = $this->make_dashboard_data($result);
                break;
            case $CONSTANTS['Sepecific Department']:
                $result = Task::where('is_enable', 1)->where('department_id', $user->department_id)->get();
                $response = $this->make_dashboard_data($result);
                break;
            case $CONSTANTS['Just Self']:
                $result = Task::whereHas('users', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->where('is_enable', 1)->get();
                $response = $this->make_dashboard_data($result);
                break;
        }
        $data['stats'] = $response;
        $data['notes'] = $notes;

        if (system_role()) {
            $data['companies'] = Company::where('is_enable', 1)->count();
            $data['departments'] = Department::where('is_enable', 1)->count();
            $data['projects'] = Project::where('is_enable', 1)->count();
            $data['users'] = User::where('is_enable', 1)->count();
        } else {
            $data['companies'] = Company::where('is_enable', 1)->count();
            $data['departments'] = Department::where('is_enable', 1)->count();
            $data['total_projects'] = Project::where(['is_enable' => 1, 'company_id' => user_company_id()])->count();
            $data['projects'] = Project::where(['is_enable' => 1, 'company_id' => user_company_id()])->orderBy('id', 'DESC')->take('5')->get();
            $data['users'] = User::where('is_enable', 1)->count();
        }

        $department_id = $user->department_id;
        if ($department_id) {
            $data['usersList'] = User::where('is_enable', 1)->where('department_id', $department_id)->where('company_id', user_company_id())->get();
            $data['departmentsList'] = Department::where('is_enable', 1)->where('id', $department_id)->where('company_id', user_company_id())->get();
        } else {
            if (system_role()) {
                $data['usersList'] = User::where('is_enable', 1)->get();
                $data['departmentsList'] = Department::where('is_enable', 1)->get();
            } else {
                $data['usersList'] = User::where('is_enable', 1)->where('company_id', user_company_id())->get();
                $data['departmentsList'] = Department::where('is_enable', 1)->where('company_id', user_company_id())->get();
            }
        }
        return view('dashboard', $data);
    }

    public function make_dashboard_data($result)
    {
        // Total Task
        $total = count($result);

        $totalAssigned = $result->filter(function ($value) {
            return $value->status == 1;
        });
        $totalAssigned = count($totalAssigned);

        $totalRunning = $result->filter(function ($value) {
            return in_array($value->status, [2, 4, 6, 7]);
        });
        $totalRunning = count($totalRunning);

        $totalClosed = $result->filter(function ($value) {
            return $value->status == 3;
        });
        $totalClosed = count($totalClosed);

        // Today Task
        $todayTotal = $result->filter(function ($value) {
            return Carbon::parse($value['created_at'])->isToday();;
        });
        $todayTotal = count($todayTotal);

        $todayAssigned = $result->filter(function ($value) {
            return Carbon::parse($value['created_at'])->isToday() && $value['status'] == 1;
        });
        $todayAssigned = count($todayAssigned);

        $todayRunning = $result->filter(function ($value) {
            return Carbon::parse($value['created_at'])->isToday() && in_array($value->status, [2, 4, 6, 7]);
        });
        $todayRunning = count($todayRunning);

        $todayClosed = $result->filter(function ($value) {
            return Carbon::parse($value['created_at'])->isToday() && $value['status'] == 3;
        });
        $todayClosed = count($todayClosed);

        // Weekly Task
        $weeklyTotal = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            return $createdDate->between($startOfWeek, $endOfWeek);
        });
        $weeklyTotal = count($weeklyTotal);

        $weeklyAssigned = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            return $createdDate->between($startOfWeek, $endOfWeek)  && $value['status'] == 1;
        });
        $weeklyAssigned = count($weeklyAssigned);

        $weeklyRunning = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            return $createdDate->between($startOfWeek, $endOfWeek)  && in_array($value->status, [2, 4, 6, 7]);
        });
        $weeklyRunning = count($weeklyRunning);

        $weeklyClosed = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            return $createdDate->between($startOfWeek, $endOfWeek)  && $value['status'] == 3;
        });
        $weeklyClosed = count($weeklyClosed);

        // Monthly
        $monthlyTotal = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return $createdDate->between($startOfMonth, $endOfMonth);
        });
        $monthlyTotal = count($monthlyTotal);

        $monthlyAssigned = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return $createdDate->between($startOfMonth, $endOfMonth) && $value['status'] == 1;
        });
        $monthlyAssigned = count($monthlyAssigned);

        $monthlyRunning = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return $createdDate->between($startOfMonth, $endOfMonth) && in_array($value->status, [2, 4, 6, 7]);
        });
        $monthlyRunning = count($monthlyRunning);

        $monthlyClosed = $result->filter(function ($value) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return $createdDate->between($startOfMonth, $endOfMonth) && $value['status'] == 3;
        });
        $monthlyClosed = count($monthlyClosed);

        // missed
        $todayDate = Carbon::now()->format('Y-m-d');
        $missedTask = $result->filter(function ($value) use ($todayDate) {
            return ($value->closed_date > $value->end_date) ||
                ($value->end_date && $todayDate > $value->end_date && !$value->closed_date);
        });
        $missedTask = count($missedTask);



        // Today Missed Tasks
        $todayMissed = $result->filter(function ($value) use ($todayDate) {
            $createdDate = Carbon::parse($value['created_at']);
            return $createdDate->isToday() &&
                (($value->closed_date > $value->end_date) ||
                    ($value->end_date && $todayDate > $value->end_date && !$value->closed_date));
        });
        $todayMissed = count($todayMissed);

        // Weekly Missed Tasks
        $weeklyMissed = $result->filter(function ($value) use ($todayDate) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endOfWeek = Carbon::now()->endOfWeek(Carbon::SATURDAY);
            return $createdDate->between($startOfWeek, $endOfWeek) &&
                (($value->closed_date > $value->end_date) ||
                    ($value->end_date && $todayDate > $value->end_date && !$value->closed_date));
        });
        $weeklyMissed = count($weeklyMissed);

        // Monthly Missed Tasks
        $monthlyMissed = $result->filter(function ($value) use ($todayDate) {
            $createdDate = Carbon::parse($value['created_at']);
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            return $createdDate->between($startOfMonth, $endOfMonth) &&
                (($value->closed_date > $value->end_date) ||
                    ($value->end_date && $todayDate > $value->end_date && !$value->closed_date));
        });
        $monthlyMissed = count($monthlyMissed);

        return [
            'totalTask' => $total,
            'totalAssignedTask' => $totalAssigned,
            'totalRunningTask' => $totalRunning,
            'totalClosedTask' => $totalClosed,

            'todayTotalTask' => $todayTotal,
            'todayAssignedTask' => $todayAssigned,
            'todayRunningTask' => $todayRunning,
            'todayClosedTask' => $todayClosed,

            'weeklyTotalTask' => $weeklyTotal,
            'weeklyAssignedTask' => $weeklyAssigned,
            'weeklyRunningTask' => $weeklyRunning,
            'weeklyClosedTask' => $weeklyClosed,

            'monthlyTotalTask' => $monthlyTotal,
            'monthlyAssignedTask' => $monthlyAssigned,
            'monthlyRunningTask' => $monthlyRunning,
            'monthlyClosedTask' => $monthlyClosed,

            'missedTask' => $missedTask,
            'todayMissed'=>$todayMissed,
            'weeklyMissed'=>$weeklyMissed,
            'monthlyMissed'=>$monthlyMissed,
        ];
    }

    public function filter(Request $request)
    {
        $department = $request->input('department');
        $user = $request->input('user');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // fetch task list
        db::enableQueryLog();
        $query = Task::where('is_enable', 1);
        if ($department) {
            $query->whereHas('department', function ($q) use ($department) {
                $q->where('department_id', $department);
            });
        }
        if ($user) {
            $query->whereHas('users', function ($q) use ($user) {
                $q->where('user_id', $user);
            });

            $result = Task::whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user);
            })->where('is_enable', 1)->get();
        }
        if ($startDate && $endDate) {
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
        $tasks = $query->get();

        $response = $this->make_dashboard_data($tasks);

        return response()->json(['stats' => $response]);
    }
}
