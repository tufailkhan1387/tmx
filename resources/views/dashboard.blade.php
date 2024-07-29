@extends('layout.app')
@section('title', 'Dashboard | TSP - Task Management System')
@section('pageTitle', 'Dashboard')
@section('breadcrumTitle', 'User Dashboard')

@section('content')
<style>
    .bottom-row{
        flex-wrap: unset
    }
    </style>
<!-- Start Page Content here -->
<!-- [ Main Content ] start -->
@if(in_array(Auth::user()->scope, [1,2]) )
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @if(Auth::user()->scope == 1)
                            <div class="col-lg-3 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="departmentFilter">Filter by Department:</label>
                                    <select name="department" id="departmentFilter" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach ($departmentsList as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="userFilter">Filter by User:</label>
                                <select id="userFilter" class="form-control">
                                    <option value="">Select User</option>
                                    @foreach ($usersList as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="statusFilter">Date Range Filter:</label>
                                <input type="text" id="dateRangeFilter" class="form-control" />
                            </div>
                        </div>

                        {{-- <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="perfromanceFilter">Filter by Performance:</label>
                                <select id="perfromanceFilter" class="form-control">
                                    <option value="">Select Option</option>
                                    <option value="Deadline Missed">Deadline Missed</option>
                                    <option value="Deadline Acheived">Deadline Acheived</option>
                                    <option value="Performance N/D">Performance N/D</option>
                                    <option value="Deadline N/D">Deadline N/D</option>
                                </select>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="row">
    <div class="col-lg-12 col-md-12">
        <!-- support-section start -->
        <div class="row" style="font-size: 12px">
            <div class="col-sm-3">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <h2 class="m-0" id="todayTotalTask">{{ $stats['todayTotalTask'] }}</h2>
                        <span class="text-c-blue">Today Tasks</span>
                        <p class="mb-3 mt-3">Today total number of tasks requests that come in.</p>
                    </div>
                    {{-- <div id="support-chart"></div> --}}
                    <div class="card-footer bg-primary text-white">
                        <div class="row text-center bottom-row">
                            <div class="col">
                                <h4 class="m-0 text-white" id="todayAssignedTask">{{ $stats['todayAssignedTask'] }}</h4>
                                <span>Open</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="todayRunningTask">{{ $stats['todayRunningTask'] }}</h4>
                                <span>Running</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="todayClosedTask">{{ $stats['todayClosedTask'] }}</h4>
                                <span>Done</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="todayMissed">{{ $stats['todayMissed'] }}</h4>
                                <span>Missed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <h2 class="m-0" id="weeklyTotalTask">{{ $stats['weeklyTotalTask'] }}</h2>
                        <span class="text-c-blue">Weekly Tasks</span>
                        <p class="mb-3 mt-3">Weekly total number of tasks requests that come in.</p>
                    </div>
                    {{-- <div id="support-chart"></div> --}}
                    <div class="card-footer bg-primary text-white">
                        <div class="row text-center bottom-row">
                            <div class="col">
                                <h4 class="m-0 text-white" id="weeklyAssignedTask">{{ $stats['weeklyAssignedTask'] }}</h4>
                                <span>Open</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="weeklyRunningTask">{{ $stats['weeklyRunningTask'] }}</h4>
                                <span>Running</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="weeklyClosedTask">{{ $stats['weeklyClosedTask'] }}</h4>
                                <span>Done</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="weeklyMissed">{{ $stats['weeklyMissed'] }}</h4>
                                <span>Missed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <h2 class="m-0" id="monthlyTotalTask">{{ $stats['monthlyTotalTask'] }}</h2>
                        <span class="text-c-blue">Monthly Tasks</span>
                        <p class="mb-3 mt-3">Monthly total number of tasks requests that come in.</p>
                    </div>
                    {{-- <div id="support-chart"></div> --}}
                    <div class="card-footer bg-primary text-white">
                        <div class="row text-center bottom-row">
                            <div class="col">
                                <h4 class="m-0 text-white" id="monthlyAssignedTask">{{ $stats['monthlyAssignedTask'] }}</h4>
                                <span>Open</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="monthlyRunningTask">{{ $stats['monthlyRunningTask'] }}</h4>
                                <span>Running</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="monthlyClosedTask">{{ $stats['monthlyClosedTask'] }}</h4>
                                <span>Done</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="monthlyMissed">{{ $stats['monthlyMissed'] }}</h4>
                                <span>Missed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card support-bar overflow-hidden">
                    <div class="card-body pb-0">
                        <h2 class="m-0" id="totalTask">{{ $stats['totalTask'] }}</h2>
                        <span class="text-c-green">Total Tasks</span>
                        <p class="mb-3 mt-3">Total number of tasks requests that come in.</p>
                    </div>
                    {{-- <div id="support-chart1"></div> --}}
                    <div class="card-footer bg-success text-white">
                        <div class="row text-center bottom-row" >
                            <div class="col">
                                <h4 class="m-0 text-white" id="totalAssignedTask">{{ $stats['totalAssignedTask'] }}</h4>
                                <span>Open</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="totalRunningTask">{{ $stats['totalRunningTask'] }}</h4>
                                <span>Running</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="totalClosedTask">{{ $stats['totalClosedTask'] }}</h4>
                                <span>Done</span>
                            </div>
                            <div class="col">
                                <h4 class="m-0 text-white" id="missedTask">{{ $stats['missedTask'] }}</h4>
                                <span>Missed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- support-section end -->
    </div>
    <div class="col-lg-12 col-md-12">
        <!-- page statustic card start -->
        <div class="row">
            @if(system_role())
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-yellow">$30200</h4>
                                    <h6 class="text-muted m-b-0">Earning</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-bar-chart-2 f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-c-yellow">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">% change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-red">$6702</h4>
                                    <h6 class="text-muted m-b-0">Pendding</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-calendar f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-c-red">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">0 % change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-down text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-green">{{$companies ?? 0 }} +</h4>
                                    <h6 class="text-muted m-b-0">Companies</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-file-text f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-c-green">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">% change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (Auth::user()->scope == 1)
                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-blue">{{$users ?? 0 }} +</h4>
                                    <h6 class="text-muted m-b-0"> Users</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-bar-chart-2 f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-c-blue">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">% change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-down text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(!system_role())
                @if (Auth::user()->scope == 1)
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h4 class="text-c-yellow">{{$departments ?? 0 }}</h4>
                                        <h6 class="text-muted m-b-0">Deparments</h6>
                                    </div>
                                    <div class="col-4 text-right">
                                        <i class="feather icon-calendar f-28"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-c-yellow">
                                <div class="row align-items-center">
                                    <div class="col-9">
                                        <p class="text-white m-b-0">% change</p>
                                    </div>
                                    <div class="col-3 text-right">
                                        <i class="feather icon-trending-up text-white f-16"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-green">{{$total_projects ?? 0 }} +</h4>
                                    <h6 class="text-muted m-b-0">Projects</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-bar-chart f-28"></i>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-c-green">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">% change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-up text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <h4 class="text-c-red" id="missedTask">{{ $stats['missedTask'] }}</h4>
                                    <h6 class="text-muted m-b-0">Missed Task</h6>
                                </div>
                                <div class="col-4 text-right">
                                    <i class="feather icon-thumbs-down f-28"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-c-red">
                            <div class="row align-items-center">
                                <div class="col-9">
                                    <p class="text-white m-b-0">% change</p>
                                </div>
                                <div class="col-3 text-right">
                                    <i class="feather icon-trending-down text-white f-16"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!-- page statustic card end -->
    </div>

    <!-- prject ,team member start -->
    @if(!system_role())
    <!-- Latest Projects start -->
    <div class="col-xl-8 col-md-12">
        <div class="card table-card">
            <div class="card-header">
                <h5>Latest Projects</h5>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="feather icon-more-horizontal"></i>
                        </button>
                        <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                            <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                            <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                            <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Due Date</th>
                                <th class="text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $key => $project)
                            <tr>
                                <td>{{$project->name ?? '' }}</td>
                                <td>{{$project->deadline ?? '' }}</td>
                                <td class="text-right"><label class="badge badge-light-danger">{{ $proj_status[$project->status] ?? '' }}</label></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="text-center mb-3">
                    <a href="{{route('projects.list')}}" class="b-b-primary text-primary">View all Projects</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12">
        <div class="card user-card2">
            <div class="card-body text-center">
                <h6 class="m-b-15">Project Risk</h6>
                <div class="risk-rate">
                    <span><b>5</b></span>
                </div>
                <h6 class="m-b-10 m-t-10">Balanced</h6>
                <a href="#!" class="text-c-green b-b-success">Change Your Risk</a>
                <div class="row justify-content-center m-t-10 b-t-default m-l-0 m-r-0">
                    <div class="col m-t-15 b-r-default">
                        <h6 class="text-muted">Nr</h6>
                        <h6>AWS 2455</h6>
                    </div>
                    <div class="col m-t-15">
                        <h6 class="text-muted">Created</h6>
                        <h6>30th Sep</h6>
                    </div>
                </div>
            </div>
            <button class="btn btn-success btn-block">Download Overall Report</button>
        </div>
    </div>
    <!-- Latest Projects end -->
    @endif
</div>
<!-- [ Main Content ] end -->
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#dateRangeFilter').daterangepicker({
            opens: 'left'
        }, function(start, end, label) {
            const startDate = $('#dateRangeFilter').data('daterangepicker').startDate.format('YYYY-MM-DD');
            const endDate = $('#dateRangeFilter').data('daterangepicker').endDate.format('YYYY-MM-DD');
            fetchFilteredData(startDate, endDate);
        });

        $('#departmentFilter, #userFilter').on('change', function() {
            fetchFilteredData()
        });

        function fetchFilteredData(startDate, endDate) {
            const department = $('#departmentFilter').val();
            const user = $('#userFilter').val();
            // console.log(department)
            // console.log(user)

            $.ajax({
                url: '{{ route("dashboard.filter") }}',
                type: 'GET',
                data: {
                    department: department,
                    user: user,
                    start_date: startDate,
                    end_date: endDate
                },
                success: function(data) {
                    // Update your dashboard with the filtered data
                    // console.log(data);
                    if(startDate && endDate){
                        $('#todayTotalTask').text(0);
                        $('#todayAssignedTask').text(0);
                        $('#todayRunningTask').text(0);
                        $('#todayClosedTask').text(0);

                        $('#weeklyTotalTask').text(0);
                        $('#weeklyAssignedTask').text(0);
                        $('#weeklyRunningTask').text(0);
                        $('#weeklyClosedTask').text(0);

                        $('#monthlyTotalTask').text(0);
                        $('#monthlyAssignedTask').text(0);
                        $('#monthlyRunningTask').text(0);
                        $('#monthlyClosedTask').text(0);

                        $('#weeklyMissed').text(0);
                        $('#todayMissed').text(0);
                        $('#monthlyMissed').text(0);
                    }
                    else{
                        $('#todayTotalTask').text(data.stats.todayTotalTask);
                        $('#todayAssignedTask').text(data.stats.todayAssignedTask);
                        $('#todayRunningTask').text(data.stats.todayRunningTask);
                        $('#todayClosedTask').text(data.stats.todayClosedTask);

                        $('#weeklyTotalTask').text(data.stats.weeklyTotalTask);
                        $('#weeklyAssignedTask').text(data.stats.weeklyAssignedTask);
                        $('#weeklyRunningTask').text(data.stats.weeklyRunningTask);
                        $('#weeklyClosedTask').text(data.stats.weeklyClosedTask);

                        $('#monthlyTotalTask').text(data.stats.monthlyTotalTask);
                        $('#monthlyAssignedTask').text(data.stats.monthlyAssignedTask);
                        $('#monthlyRunningTask').text(data.stats.monthlyRunningTask);
                        $('#monthlyClosedTask').text(data.stats.monthlyClosedTask);

                        $('#weeklyMissed').text(data.stats.weeklyMissed);
                        $('#todayMissed').text(data.stats.todayMissed);
                        $('#monthlyMissed').text(data.stats.monthlyMissed);
                    }

                    $('#totalTask').text(data.stats.totalTask);
                    $('#totalAssignedTask').text(data.stats.totalAssignedTask);
                    $('#totalRunningTask').text(data.stats.totalRunningTask);
                    $('#totalClosedTask').text(data.stats.totalClosedTask);

                    $('#missedTask').text(data.stats.missedTask);

                },
                error: function(error) {
                    console.error('Error fetching filtered data:', error);
                }
            });
        }

        
    });
    
        
</script>

@endsection