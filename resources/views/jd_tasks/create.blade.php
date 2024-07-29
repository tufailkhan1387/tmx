@extends('layout.app')
@section('title', 'Create JD Task | TSP - Task Management System')
@section('pageTitle', 'Add New JD Task')
@section('breadcrumTitle', 'Add New JD Task')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple{
        border-left: none !important;
        border-right: none !important;
        border-top: none !important;
        border-radius: 0px !important;
    }
</style>
<!-- Start Page Content here -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Enter Detail</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jd.store') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Task Title</label>
                            <div class="col-sm-9">
                                <input type="text" name="title" id="title" value="{{ old('title') }}" class="form-control" placeholder="Enter Task Title" required>
                            </div>
                            @if ($errors->has('title'))
                            <span class="help-block text-danger">
                                {{ $errors->first('title') }}
                            </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Task Description</label>
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
                            <label for="role" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-9">
                                <select name="role" id="role" class="form-control" required>
                                    <option value="" selected>Select Role</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" >{{ filter_company_id($role->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('role'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('role') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group row">
                            <label for="user" class="col-sm-3 col-form-label">User</label>
                            <div class="col-sm-9">
                                <select name="user[]" id="user" multiple class="form-control users_list" required>
                                   
                                </select>
                            </div>
                            @if ($errors->has('user'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('user') }}
                                </span>
                            @endif
                        </div>
    

                        <div class="form-group row">
                            <label for="frequency" class="col-sm-3 col-form-label">Frequency</label>
                            <div class="col-sm-9">
                                <select name="frequency" id="frequency" class="form-control">
                                    <option value="" selected>Select Frequency</option>
                                    @foreach ($frequency as $key => $val)
                                    <option value="{{ $key }}" >{{ $val }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('frequency'))
                                <span class="help-block text-danger">
                                    {{ $errors->first('frequency') }}
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

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function (){
            $('#user').select2({
                 placeholder: "Select User"
            });
            $('#role').on('change', function(){
                var roleId = $('#role').val();
                
                $.ajax({
                    url: '{{ route("users.by.role") }}',
                    type: 'GET',
                    data: {
                        role_id: roleId
                    },
                    success: function(response) {
                        console.log(response);

                        var usersSelect = $('#user');
                        usersSelect.empty();
                        // usersSelect.append('<option value="" selected>Select User</option>'); // Add the default option
                        
                        $.each(response.users, function(key, value) {
                            usersSelect.append('<option value="' + key + '">' + value + '</option>');
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