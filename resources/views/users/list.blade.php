@extends('layout.app')
@section('title', 'User List | TSP - Task Management System')
@section('pageTitle', 'User List')
@section('breadcrumTitle', 'User List')

@section('content')
<!-- Start Page Content here -->

<div class="row">
    <div id="userDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLiveLabel">User Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group row">
                            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Full Name</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control form-control-sm" id="colFormLabelSm" placeholder="col-form-label-sm" value="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabel" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="colFormLabel" placeholder="col-form-label">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label-lg">Email</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control form-control-lg" id="colFormLabelLg" placeholder="col-form-label-lg">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn  btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Users Management</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="data_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                @if (system_role())
                                <th> Company</th>
                                @endif
                                <th>Role</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>
                                    <img class="img-radius" src="{{ asset('storage/profile_pics/'.$user->profile_pic) }}" alt="User-Profile-Image" width="50px" height="50px">
                                </td>
                                <td>{{ $user->name }}</td>
                                @if (system_role())
                                <td>{{ $user->company->name }}</td>
                                @endif
                                <td>{{ filter_company_id($user->roles->first()->name) }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department_id ? $user->department->name : 'All Departments'}}</td>
                                <td>
                                    {{-- <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a> --}}
                                    @can('update-users')
                                    <a class="btn btn-primary rounded-pill px-4 py-1" href="{{ route('users.edit', $user->id) }}">Edit</a>
                                    @endcan
                                    {{-- @can('update-users') --}}
                                    <a class="btn btn-primary rounded-pill px-4 py-1" href="{{ route('users.show', $user->id) }}">Details</a>
                                    {{-- @endcan --}}
                                    @can('delete-users')
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-1" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                                    </form>
                                    @endcan
                                    {{-- <button type="button" class="btn btn-primary view-button rounded-pill px-4 py-1"  data-toggle="modal" data-target="#userDetailModal">View</button> --}}
                                </td>
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

<script>
    $('#data_table').DataTable();

    $(document).ready(function() {
        $('.view-button').on('click', function() {
            alert(12);
            // Get the user details from the data attributes of the clicked row
            // var row = $(this).closest('tr');
            // var userName = row.data('name');
            // var userEmail = row.data('email');

            // // Populate the modal with the user details
            // $('#userName').text(userName);
            // $('#userEmail').text(userEmail);
        });
    });
</script>
@endsection