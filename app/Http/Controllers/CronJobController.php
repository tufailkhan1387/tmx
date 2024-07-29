<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JdTask;
use Illuminate\Support\Carbon;
use App\Models\Task;
use App\Models\User;

class CronJobController extends Controller
{
    public function cronJobDaily(Request $request)
    {
        if($request->key == 'HamzaTspUK007'){
            $company_id = $request->company;

            $users = User::where('company_id', $company_id)->where('is_enable', 1)->get();
            if(count($users) > 0){
                foreach($users as $user){
                    $jd_tasks = JdTask::where('is_enable', 1)->where('company_id', $company_id)->where('user_id', $user->id)->where('frequency', 1)->get();

                    if(count($jd_tasks) > 0){
                        
                        $today = Carbon::today(config('app.timezone'))->format('Y-m-d');

                        foreach ($jd_tasks as $jd_task){
                            $task = new Task();
                            
                            $task['company_id']     = $jd_task->company_id;
                            $task['priority']       = 3;
                            $task['status']         = 1;
                            $task['title']          = $jd_task->title;
                            $task['description']    = $jd_task->description;
                            $task['created_by']     = $jd_task->created_by;
                            $task['start_date']     = $today;
                            $task['end_date']       = $today;

                            $response = $task->save();

                            $assign_user = $user->id;
                            $task->users()->attach($assign_user);
                        }
                    }  
                } 
            }
            else{
                echo 'no user';
            }
        }
        else{
            echo 'wrong secret key';
        }
    }

    public function cronJobWeekly(Request $request)
    {
        if($request->key == 'HamzaTspUK007'){
            $company_id = $request->company;

            $users = User::where('company_id', $company_id)->where('is_enable', 1)->get();
            if(count($users) > 0){
                foreach($users as $user){
                    $jd_tasks = JdTask::where('is_enable', 1)->where('company_id', $company_id)->where('user_id', $user->id)->where('frequency', 2)->get();

                    if(count($jd_tasks) > 0){
                        $today = Carbon::today(config('app.timezone'))->format('Y-m-d');

                        foreach ($jd_tasks as $jd_task){
                            $task = new Task();

                            $task['company_id']     = $jd_task->company_id;
                            $task['priority']       = 3;
                            $task['status']         = 1;
                            $task['title']          = $jd_task->title;
                            $task['description']    = $jd_task->description;
                            $task['created_by']     = $jd_task->created_by;
                            $task['start_date']     = $today;
                            $task['end_date']       = $today;

                            $response = $task->save();

                            $user = $user->id;
                            $task->users()->attach($user);
                        }
                    }  
                } 
            }
            else{
                echo 'no user';
            }
        }
        else{
            echo 'wrong';
        }
    }

    public function cronJobMonthly(Request $request)
    {
        if($request->key == 'HamzaTspUK007'){
            $company_id = $request->company;

            $users = User::where('company_id', $company_id)->where('is_enable', 1)->get();
            if(count($users) > 0){
                foreach($users as $user){
                    $jd_tasks = JdTask::where('is_enable', 1)->where('company_id', $company_id)->where('user_id', $user->id)->where('frequency', 3)->get();

                    if(count($jd_tasks) > 0){
                        $today = Carbon::today(config('app.timezone'))->format('Y-m-d');

                        foreach ($jd_tasks as $jd_task){
                            $task = new Task();

                            $task['company_id']     = $jd_task->company_id;
                            $task['priority']       = 3;
                            $task['status']         = 1;
                            $task['title']          = $jd_task->title;
                            $task['description']    = $jd_task->description;
                            $task['created_by']     = $jd_task->created_by;
                            $task['start_date']     = $today;
                            $task['end_date']       = $today;

                            $response = $task->save();

                            $user = $user->id;
                            $task->users()->attach($user);
                        }
                    }  
                } 
            }
            else{
                echo 'no user';
            }
        }
        else{
            echo 'wrong';
        }
    }
}
