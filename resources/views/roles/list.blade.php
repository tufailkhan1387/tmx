@extends('layout.app')
@section('title', 'User Roles List')
@section('pageTitle', 'User Roles List')
@section('breadcrumTitle', 'User Roles List')

@section('content')

<!-- Start Page Content here -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>All System Roles</h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="data_table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{filter_company_id($role->name)}}</td>
                                        <td>
                                            {{-- <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a> --}}
                                            @can('update-roles')
                                            <a class="btn btn-primary rounded-pill px-4 py-1" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                                            @endcan
                                            @can('delete-roles')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-pill py-1" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                                            </form>
                                            @endcan
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
</script>
@endsection