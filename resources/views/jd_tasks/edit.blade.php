@extends('layout.app')
@section('title', 'Edit JD Task | TSP - Task Management System')
@section('pageTitle', 'Edit JD Task Detail')

@section('content')

<!-- Start Page Content here -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Enter Detail</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('jd.update') }}" method="post" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{ $jd_task->id }}">
                        <div class="form-group row">
                            <label for="title" class="col-sm-3 col-form-label">Task Title</label>
                            <div class="col-sm-9">
                                <input type="text" name="title" id="title" value="{{ $jd_task->title }}" class="form-control" required>
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
                                <input type="text" name="description" id="description" value="{{ $jd_task->description }}" class="form-control" required>
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
                                        <option value="{{ $role->id }}" {{ $jd_task->role == $role->id ? 'selected' : '' }}>{{ filter_company_id($role->name) }}</option>
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
                                <select name="user" id="user" class="form-control users_list" required>
                                    <option value="" selected>Select User</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" {{ $jd_task->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
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
                                    <option value="{{ $key }}" {{ $jd_task->frequency == $key ? 'selected' : '' }}>{{ $val }}</option>
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
    <script>
        $(document).ready(function (){
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
                        usersSelect.append('<option value="" selected>Select User</option>'); // Add the default option
                        
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