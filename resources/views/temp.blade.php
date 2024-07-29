{{-- 
public function all_dashboard($user)
{
    // Dashobard stats for upper management like software manger or company ceo or manager
    $total = '';
    if($user->company_id){
        $result = Task::where('is_enable', 1)->get();
    }
    else{
        $result = Task::where('is_enable', 1)->get();
    }

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
    ];
}

public function department_dashboard($user)
{
    // Dashboard stats for department hod's
    $total = '';
    $result = Task::where('is_enable', 1)->where('department_id', $user->department_id)->get();

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
    ];
}

public function specific_dashboard($user)
{
    // Dashboard stats for self user
    $total = '';

    $result = Task::whereHas('users', function ($query) use ($user) {
                        $query->where('user_id', $user->id);
                        })->where('is_enable', 1)->get();

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
    ];
} --}}