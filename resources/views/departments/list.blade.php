@extends('layout.app')
@section('title', 'Departments List | TSP - Task Management System')
@section('pageTitle', 'Departments List')
@section('breadcrumTitle', 'Departments List')

@section('content')
<!-- Start Page Content here -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Departments List</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="data_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Official Email</th>
                                <th>Members</th>
                                @if (system_role())
                                <th> Company</th>
                                @endif
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departments as $department)
                            <tr>
                                <td>{{ $department->name }}</td>
                                <td>{{ $department->email }}</td>
                                <td>{{ $department->members }}</td>
                                @if (system_role())
                                <td>{{ $department->company->name }}</td>
                                @endif
                                <td>{{ $department->description }}</td>

                                <td>
                                    @can('update-departments')
                                    <a class="btn btn-primary  rounded-pill px-4 py-1" href="{{ route('departments.edit', $department->id) }}">Edit</a>
                                    @endcan
                                    @can('delete-departments')
                                    <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-1" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
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