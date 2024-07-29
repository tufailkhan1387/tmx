@extends('layout.app')
@section('title', 'Edit Project | TSP - Task Management System')
@section('pageTitle', 'Edit Project Detail')

@section('content')

<!-- Start Page Content here -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Enter Detail</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.update') }}"  method="post" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{ $project->id }}">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="role">Project Name</label>
                                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Project Name" value="{{ old('description',$project->name) }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                            <div class="col-sm-12">
                            <div class="form-group">
                                <label class="floating-label" for="role">Project Overview</label>
                                <input name="description" type="text" class="form-control py-5" id="description" placeholder="Project Overview" value="{{ old('description',$project->description) }}" required>
                            </div>
                            @if ($errors->has('description'))
                            <span class="help-block text-danger">
                                {{ $errors->first('description') }}
                            </span>
                            @endif
                        </div>

                            <div class="col-sm-3">
                                <button type="submit" class="btn  btn-primary">Update</button>
                            </div>    
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection