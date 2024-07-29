<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $desi_scopes;
    public $task_status;
    public $task_preority;
    public $proj_status;

    public function __construct()
    {
        $this->desi_scopes = config('constants.DESIGNATION_SCOPE');
        $this->task_status = config('constants.STATUS_LIST');
        $this->proj_status = config('constants.STATUS_LIST');
        $this->task_preority = config('constants.PRIORITY_LIST');
        
        view()->share('desi_scopes', $this->desi_scopes);
        view()->share('task_status', $this->task_status);
        view()->share('task_preority', $this->task_preority);
        view()->share('proj_status', $this->proj_status);
    }

}
