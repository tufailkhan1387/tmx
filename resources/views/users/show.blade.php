@extends('layout.app')
@section('title', 'Update User | TSP - Task Management System')
@section('pageTitle', 'User Details')
@section('breadcrumTitle', $user->name)
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>User Detail</h5>
                        </div>
                        <div>
                            <h5>Performance : <span id="performance">{{ number_format($performance, 1) }}</span>%</h5>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                   
                    <div class="row">
                        <div class="col-md-3">
                            <div id="totalTasksCard" class="card text-white bg-primary mb-3">
                                <div class="card-header">
                                    <i class="fas fa-tasks"></i> Total Tasks
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 1.5rem;">{{ $totalTasks }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="completedTasksCard" class="card text-white bg-success mb-3">
                                <div class="card-header">
                                    <i class="fas fa-check-circle"></i> Completed Tasks
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 1.5rem;">{{ $completedTasks }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="missedTasksCard" class="card text-white bg-danger mb-3">
                                <div class="card-header">
                                    <i class="fas fa-times-circle"></i> Missed Tasks
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 1.5rem;">{{ $missedTasks }}</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div id="assignedTasksCard" class="card text-white bg-warning mb-3">
                                <div class="card-header">
                                    <i class="fas fa-clipboard-list"></i> Assigned Tasks
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title text-white" style="font-size: 1.5rem;">{{ $assignedTasks }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="statusFilter">Date Range Filter:</label>
                                <input type="text" id="dateRangeFilter" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="tasksTable">
                            <thead>
                                <tr>
                                    <th># Details</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Assign To</th>
                                    <th>Project</th>
                                    <th>Title</th>
                                    @if (!$department_id)
                                        <th>Department</th>
                                    @endif
                                    <th>Assigned By</th>
                                    <th>Due Date</th>
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

                                        if (
                                            $task->closed_date > $task->end_date ||
                                            ($task->end_date && $todayDate > $task->end_date && !$task->closed_date)
                                        ) {
                                            $label = 'Deadline Missed';
                                            $deadlinePassed = false;
                                        } elseif ($task->closed_date && $task->closed_date <= $task->end_date) {
                                            $label = 'Deadline Acheived';
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
                                        <td>{{ $task->project->name ?? null }}</td>
                                        <td>{{ $task->title }}</td>
                                        @if (!$department_id)
                                            <td>{{ $task->department->name ?? null }}</td>
                                        @endif
                                        <td>{{ $task->creator->name }}</td>
                                        <td>{{ format_date($task->end_date) }}
                                            {{-- {{ $task->end_date ?? 'N/D' }} --}}
                                            , Days({{ $daysLabel ?? '' }})</td>
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


    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#dateRangeFilter').daterangepicker({
                    opens: 'left'
                }, function(start, end, label) {
                    const startDate = $('#dateRangeFilter').data('daterangepicker').startDate.format(
                        'YYYY-MM-DD');
                    const endDate = $('#dateRangeFilter').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    fetchFilteredData(startDate, endDate);
                });


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
                    table.column(8).search(selectedValue).draw();



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

            function fetchFilteredData(startDate, endDate) {
                var userId = {{ $user->id }};

                $.ajax({
                    url: '{{ route('users.by.filter_by_date') }}',
                    type: 'POST',
                    data: {
                        startDate: startDate,
                        endDate: endDate,
                        userId: userId,
                        _token: '{{ csrf_token() }}' // Add CSRF token here
                    },
                    success: function(response) {
                        console.log(response);

                        // Clear existing table and card contents
                        $('#tasksTable tbody').empty();
                        $('#totalTasksCard .card-title').text('');
                        $('#completedTasksCard .card-title').text('');
                        $('#missedTasksCard .card-title').text('');
                        $('#assignedTasksCard .card-title').text('');

                        // Assuming response contains an array of task data
                        response.tasks.forEach(function(task) {
                            var currentDate = new Date();
                            var endDate = task.end_date ? new Date(task.end_date) : null;
                            var isDeadlinePassed = endDate && endDate < currentDate;
                            var label = getTaskLabel(task, currentDate, endDate);

                            var newRow = `
                    <tr class="${isDeadlinePassed ? 'bg-danger' : ''}">
                        <td>
                            <a href="{{ route('tasks.show', '') }}/${btoa(task.id)}" class="fw-bold">
                                #${task.id} <i class="fas fa-eye"></i>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('tasks.show', '') }}/${btoa(task.id)}"
                                class="btn ${getPriorityClass(task.priority)} rounded-pill py-0">
                                ${getPriorityText(task.priority)}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('tasks.show', '') }}/${btoa(task.id)}"
                                class="btn ${getStatusClass(task.status)} rounded-pill py-0">
                                ${getStatusText(task.status)}
                            </a>
                        </td>
                        <td>
                            ${task.users.map(user => user.name).join(', ')}
                        </td>
                        <td>${task.project ? task.project.name : ''}</td>
                        <td>${task.title}</td>
                       
                        <td>${task?.creator?.name}</td>
                        <td>${formatDate(task.end_date)}, Days(${label.daysLabel})</td>
                        <td>${label.label}</td>
                        @can('delete-tasks')
                            <td>
                                <form action="{{ route('tasks.destroy', '') }}/${task.id}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill px-4 py-1"
                                        style="border: 2px solid white;"
                                        onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                </form>
                            </td>
                        @endcan
                    </tr>
                `;

                            $('#tasksTable tbody').append(newRow);
                        });

                        // Update cards
                        $('#totalTasksCard .card-title').text(response.totalTasks);
                        $('#completedTasksCard .card-title').text(response.completedTasks);
                        $('#missedTasksCard .card-title').text(response.missedTasks);
                        $('#assignedTasksCard .card-title').text(response.assignedTasks);
                        $('#performance').text(response.performance);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            function getTaskLabel(task, currentDate, endDate) {
                var todayDate = currentDate.toISOString().split('T')[0];
                var isDeadlinePassed = endDate && endDate < currentDate;
                var daysRemaining = endDate ? Math.ceil((endDate - currentDate) / (1000 * 60 * 60 * 24)) : null;

                if (task.closed_date > task.end_date || (task.end_date && todayDate > task.end_date && !task.closed_date)) {
                    return {
                        label: 'Deadline Missed',
                        daysLabel: daysRemaining + ' Over'
                    };
                } else if (task.closed_date && task.closed_date <= task.end_date) {
                    return {
                        label: 'Deadline Acheived',
                        daysLabel: daysRemaining + ' Remaining'
                    };
                } else if (endDate && endDate.toISOString().split('T')[0] === todayDate) {
                    return {
                        label: 'Deadline Today',
                        daysLabel: daysRemaining + ' Remaining'
                    };
                } else if (endDate && endDate.toISOString().split('T')[0] === new Date(currentDate.getTime() + 86400000)
                    .toISOString().split('T')[0]) {
                    return {
                        label: 'Deadline Tomorrow',
                        daysLabel: daysRemaining + ' Remaining'
                    };
                } else if (endDate && currentDate - endDate <= 7 * 86400000) {
                    return {
                        label: 'Deadline This Week',
                        daysLabel: daysRemaining + ' Remaining'
                    };
                } else {
                    return {
                        label: 'Performance N/D',
                        daysLabel: daysRemaining + ' Remaining'
                    };
                }
            }

            function getPriorityClass(priority) {
                switch (priority) {
                    case 1:
                        return 'btn-secondary';
                    case 2:
                        return 'btn-warning';
                    case 3:
                        return 'btn-danger';
                    default:
                        return 'btn-secondary';
                }
            }

            function getPriorityText(priority) {
                switch (priority) {
                    case 1:
                        return 'Low';
                    case 2:
                        return 'Medium';
                    case 3:
                        return 'High';
                    default:
                        return 'N/A';
                }
            }

            function getStatusClass(status) {
                switch (status) {
                    case 1:
                        return 'btn-primary';
                    case 2:
                        return 'btn-success';
                    case 3:
                        return 'btn-secondary';
                    default:
                        return 'btn-secondary';
                }
            }

            function getStatusText(status) {
                switch (status) {
                    case 1:
                        return 'Assigned';
                    case 2:
                        return 'Closed';
                    case 3:
                        return 'In Progress';
                    default:
                        return 'N/A';
                }
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/D';
                var options = {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                };
                return new Date(dateString).toLocaleDateString(undefined, options);
            }
        </script>
    @endpush

@endsection
