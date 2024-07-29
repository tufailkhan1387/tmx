@extends('layout.app')
@section('title', 'Edit Department | TSP - Task Management System')
@section('pageTitle', 'Edit Department Detail')
@section('breadcrumTitle', 'Edit Department')

@section('content')
<!-- Start Page Content here -->

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Department Detail</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('departments.update') }}"  method="post" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="id" value="{{ $department->id }}">
                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="role">Department Name</label>
                                    <input name="name" type="text" class="form-control" id="name" placeholder="Enter Department Name" value="{{ old('name',$department->name)  }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="role">Offcial Email</label>
                                    <input name="email" type="email" class="form-control" id="email" placeholder="departmental offcial email" value="{{ old('email',$department->email) }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="role">Total Members</label>
                                    <input name="members" type="number" class="form-control" id="members" placeholder="total members " value="{{ old('members',$department->members) }}" required>
                                </div>
                                @if ($errors->has('members'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('members') }}
                                    </span>
                                @endif
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="floating-label" for="role">Short Description</label>
                                    <input name="description" type="text" class="form-control" id="description" placeholder="shortly describe department" value="{{ old('description',$department->description) }}" required>
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