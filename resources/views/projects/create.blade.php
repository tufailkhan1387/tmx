@extends('layout.app')
@section('title', 'Create Project | TSP - Task Management System')
@section('pageTitle', 'Add New Project')
@section('breadcrumTitle', 'Add New Project')

@section('content')

<!-- Start Page Content here -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Enter Detail</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.store') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Project Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Enter Project Name" required>
                            </div>
                            @if ($errors->has('name'))
                            <span class="help-block text-danger">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Project Overview</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" id="description" value="{{ old('description') }}" class="form-control" placeholder="Enter description" required>
                            </div>
                            @if ($errors->has('description'))
                            <span class="help-block text-danger">
                                {{ $errors->first('description') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Project Plan</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="project_plan" id="project_plan" rows="5"></textarea>
                            </div>
                            @if ($errors->has('description'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('description') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="department" class="col-sm-3 col-form-label">Departments</label>
                            <div class="col-sm-9">
                                <select name="department_id" id="department_id" class="form-control" required>
                                    <option value="" selected>Select Department</option>
                                    @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @if(old('department')==$department->id) selected @endif>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('department_id'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('department_id') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="status" class="col-sm-3 col-form-label">Status</label>
                            <div class="col-sm-9">
                                <select name="status" id="status" class="form-control">
                                    <option value="" selected>Select Status</option>
                                    @foreach ($status as $key => $val)
                                    <option value="{{ $key }}" @if(old('status')==$key) selected @endif>{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('status'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('status') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Refrence URL <span class="text-danger">(use * to separate multiple URLs)</span></label>
                            <div class="col-sm-9">
                                <input type="text" name="ref_url" id="ref_url" value="{{ old('ref_url') }}" class="form-control" placeholder="Enter description">
                            </div>
                            @if ($errors->has('ref_url'))
                            <span class="help-block text-danger">
                                {{ $errors->first('ref_url') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Deadline</label>
                            <div class="col-sm-9">
                                <input type="date" name="deadline" id="deadline" value="{{ old('deadline') }}" class="form-control">
                            </div>
                            @if ($errors->has('deadline'))
                            <span class="help-block text-danger">
                                {{ $errors->first('deadline') }}
                            </span>
                            @endif
                        </div>

                        <div class="col-sm-3">
                            <button type="submit" class="btn  btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection