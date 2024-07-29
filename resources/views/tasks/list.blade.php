@extends('layout.app')
@section('title', 'Task List | TSP - Task Management System')
@section('pageTitle', 'Task List')
@section('breadcrumTitle', 'Task List')

@section('content')
    <!-- Start Page Content here -->
    <style>
        .btn-success {
            background-color: #00952e !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Task Management</h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <div class="row">
                            @if (!$department_id)
                                <div class="col-lg-2 col-md-6 col-sm-12">
                                    <div class="form-group fill">
                                        <label for="departmentFilter">Filter by Department:</label>
                                        <select id="departmentFilter" class="form-control">
                                            <option value="">Select Department</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->name }}">{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="userFilter">Filter by User:</label>
                                    <select id="userFilter" class="form-control">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->name }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="statusFilter">Filter by Status:</label>
                                    <select id="statusFilter" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach ($task_status as $status)
                                            <option value="{{ $status }}">{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="perfromanceFilter">Filter by Performance:</label>
                                    <select id="perfromanceFilter" class="form-control">
                                        <option value="">Select Option</option>
                                        <option value="Deadline Missed">Deadline Missed</option>
                                        <option value="Deadline Acheived">Deadline Acheived</option>
                                        <option value="Performance N/D">Performance N/D</option>
                                        <option value="Deadline N/D">Deadline N/D</option>
                                        <option value="Deadline Today">Deadline Today</option>
                                        <option value="Deadline Tomorrow">Deadline Tomorrow</option>
                                        <option value="Deadline This Week">Deadline This Week</option>
                                    </select>
                                </div>
                            </div>



                            <div class="col-lg-2 col-md-6 col-sm-12">
                                <div class="form-group fill">
                                    <label for="projectFilter">Filter by Projects:</label>
                                    <select id="projectFilter" class="form-control">
                                        <option value="">Select Project</option>
                                        @foreach ($projects as $project)
                                            <option value="{{ $project->name }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <table class="table" id="tasksTable">
                            <thead>
                                <tr>
                                    <th># Details</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assign To</th>
                                    <th>Due Date</th>
                                    <th>Project</th>
                                    <th>Title</th>
                                    @if (!$department_id)
                                        <th>Department</th>
                                    @endif
                                    <th>Assigned By</th>

                                    <th>Performance</th>
                                    @can('delete-tasks')
                                        <th>Action</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tasks as $task)
                                    @php
                                        $currentDate = \Carbon\Carbon::now()->startOfDay();
                                        $todayDate = \Carbon\Carbon::now()->format('Y-m-d');
                                        $endDate = isset($task->end_date)
                                            ? \Carbon\Carbon::parse($task->end_date)->startOfDay()
                                            : null;
                                        $isDeadlinePassed = $endDate !== null && $endDate->lt($currentDate);

                                        $isNotClosed =
                                            isset($task_status[$task->status]) &&
                                            $task_status[$task->status] != 'Closed';
                                        $daysRemaining = null;
                                        if ($endDate !== null) {
                                            $daysRemaining = $endDate->diffInDays($currentDate, false);
                                            $daysRemaining = $endDate->lt($currentDate)
                                                ? -$daysRemaining
                                                : $daysRemaining;
                                            $daysRemaining = $endDate->isPast() ? $daysRemaining : -$daysRemaining;
                                            $daysLabel =
                                                $daysRemaining >= 0
                                                    ? $daysRemaining . ' Remaining'
                                                    : abs($daysRemaining) . ' Over';
                                        }

                                        // if ($task_status[$task->status] == 'Closed') {
                                        // $label = 'Deadline Acheived';
                                        // } elseif ($isNotClosed && $isDeadlinePassed) {
                                        // $label = 'Deadline Missed';
                                        // } elseif ($isNotClosed && !$isDeadlinePassed) {
                                        // $label = 'Performance N/D';
                                        // }

                                        if (
                                            $task->closed_date > $task->end_date ||
                                            ($task->end_date && $todayDate > $task->end_date && !$task->closed_date)
                                        ) {
                                            $label = 'Deadline Missed';
                                            $deadlinePassed = false;
                                        } elseif ($task->closed_date && $task->closed_date <= $task->end_date) {
                                            $label = 'Deadline Achieved';
                                            $deadlinePassed = true;
                                        } elseif ($endDate && $endDate->equalTo($currentDate)) {
                                            $label = 'Deadline Today';
                                            $deadlinePassed = false; // Assuming we want to highlight "Deadline Today" tasks separately
                                        } elseif ($endDate && $endDate->equalTo($currentDate->copy()->addDay())) {
                                            $label = 'Deadline Tomorrow';
                                            $deadlinePassed = false; // Highlight "Deadline Tomorrow" tasks separately if needed
                                        } elseif ($endDate && $currentDate->diffInDays($endDate) <= 7) {
                                            $label = 'Deadline This Week'; // Tasks with deadline within a week
                                            $deadlinePassed = false;
                                        } else {
                                            $label = 'Performance N/D';
                                            $deadlinePassed = true;
                                        }
                                    @endphp
                                    <tr class="{{ $deadlinePassed ? '' : 'bg-danger' }}">
                                        <td>
                                            <a href="{{ route('tasks.show', base64_encode($task->id)) }}" class="fw-bold">
                                                #{{ $task->id }} <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('tasks.show', base64_encode($task->id)) }}"
                                                class="btn {{ config('constants.PRIORITY_LIST')[$task->priority] == 'Medium' ? 'btn-warning' : (config('constants.PRIORITY_LIST')[$task->priority] == 'High' ? 'btn-danger' : 'btn-secondary') }} rounded-pill py-0">
                                                {{ config('constants.PRIORITY_LIST')[$task->priority] ?? '' }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('tasks.show', base64_encode($task->id)) }}"
                                                class="btn {{ $task_status[$task->status] == 'Assigned' ? 'btn-primary' : ($task_status[$task->status] == 'Closed' ? 'btn-success' : 'btn-secondary') }} rounded-pill py-0">
                                                {{ $task_status[$task->status] ?? '' }}
                                            </a>
                                        </td>
                                        <td>
                                            @foreach ($task->users as $user)
                                                <span>{{ $user->name }}</span>
                                                @if (!$loop->last)
                                                    ,
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ format_date($task->end_date) }}
                                            {{-- {{ $task->end_date ?? 'N/D' }} --}}
                                            , Days({{ $daysLabel ?? '' }})</td>
                                        <td>{{ $task->project->name ?? null }}</td>
                                        <td>{{ $task->title }}</td>
                                        @if (!$department_id)
                                            <td>{{ $task->department->name ?? null }}</td>
                                        @endif
                                        <td>{{ $task->creator->name }}</td>

                                        <td>{{ $label ?? 'Deadline N/D' }}</td>

                                        @can('delete-tasks')
                                            <td>
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-1"
                                                        style="border: 2px solid white;"
                                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                                </form>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script>
        $(document).ready(function() {



            var table = $('#tasksTable').DataTable({
                "order": [], // Disable initial sorting
                "paging": false,
                "info": false
            });

            // Filter for task status
            $('#statusFilter').on('change', function() {
                var selectedValue = $(this).val();
                table.column(2).search(selectedValue).draw();
            });

            // Filter for users
            $('#userFilter').on('change', function() {
                var selectedValue = $(this).val();
                table.column(3).search(selectedValue).draw();
            });

            // Filter for department
            $('#departmentFilter').on('change', function() {
                var selectedValue = $(this).val();
                table.column(6).search(selectedValue).draw();
            });

            // Filter for performance
            $('#perfromanceFilter').on('change', function() {
                var selectedValue = $(this).val();
                table.column(9).search(selectedValue).draw();



            });

            // Filter for projects
            $('#projectFilter').on('change', function() {
                var selectedValue = $(this).val();
                table.column(4).search(selectedValue).draw();
            });


            $('#departmentFilter').on('change', function() {
                var departmentName = $('#departmentFilter').val();
                $.ajax({
                    url: '{{ route('users.by.department') }}',
                    type: 'GET',
                    data: {
                        departmentName: departmentName
                    },
                    success: function(response) {
                        // console.log(response);

                        var usersSelect = $('#userFilter');
                        usersSelect.empty();
                        usersSelect.append(
                            '<option value="" selected>Select User</option>'
                            ); // Add the default option

                        $.each(response.users, function(key, value) {
                            usersSelect.append('<option value="' + value + '">' +
                                value + '</option>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        });
    </script>

@endsection
