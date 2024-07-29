@extends('layout.app')
@section('title', 'Export Task | TSP - Task Management System')
@section('pageTitle', 'Task Report')
@section('breadcrumTitle', 'Reports')
@section('content')

<!-- Start Page Content here -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Select Task Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.export') }}" method="POST" id="task_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="row">

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="department_id">Department Name</label>
                                <select name="department_id" id="department_id" class="form-control">
                                    <option value="" selected>Please select department</option>
                                    @foreach ($departments as $deparment)
                                    <option value="{{ $deparment->id }}">{{ $deparment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('department_id'))
                            <span class="help-block text-danger">
                                {{ $errors->first('department_id') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="project_id">Project Name</label>
                                <select name="project_id" id="project_id" class="form-control">
                                    <option value="" selected>Please select project</option>
                                    @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('project_id'))
                            <span class="help-block text-danger">
                                {{ $errors->first('project_id') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="status">User</label>
                                <select name="assign_to[]" id="assign_to" class="form-control">
                                    <option value="" selected>Please select one from blow</option>
                                    @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('assign_to'))
                            <span class="help-block text-danger">
                                {{ $errors->first('assign_to') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="priority">Priority</label>
                                <select name="priority" id="priority" class="form-control">
                                    <option value="" selected>Select Task Priority</option>
                                    @foreach ($priority as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('priority'))
                            <span class="help-block text-danger">
                                {{ $errors->first('priority') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="" selected>Select Task status</option>
                                    @foreach ($status as $key => $val)
                                    <option value="{{ $key }}">{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('status'))
                            <span class="help-block text-danger">
                                {{ $errors->first('status') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="performance">Performance:</label>
                                <select name="performance" id="performance" class="form-control">
                                    <option value="" selected>Select Task Performance</option>
                                    <option value="D_Missed">Deadline Missed</option>
                                    <option value="D_Achieved">Deadline Acheived</option>
                                </select>
                            </div>
                            @if ($errors->has('performance'))
                            <span class="help-block text-danger">
                                {{ $errors->first('performance') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="start_date">Start Date</label>
                                <input type="date" class="form-control" name="start_date" id="start_date">
                            </div>
                            @if ($errors->has('start_date'))
                            <span class="help-block text-danger">
                                {{ $errors->first('start_date') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <div class="form-group fill">
                                <label for="end_date">End Date</label>
                                <input type="date" class="form-control" name="end_date" id="end_date">
                            </div>
                            @if ($errors->has('end_date'))
                            <span class="help-block text-danger">
                                {{ $errors->first('end_date') }}
                            </span>
                            @endif
                        </div>

                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-md">Export</button>
                    </div>
            </div>

            </form>
        </div>
    </div>
</div>
</div>


@endsection