@extends('layout.app')
@section('title', 'JD Task List | TSP - Task Management System')
@section('pageTitle', 'JD Task List')
@section('breadcrumTitle', 'JD Task List')

@section('content')
<!-- Start Page Content here -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>JD Task List</h5>
                    
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="data_table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>User</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($task_list as $task)
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td>{{ $task->description }}</td>
                                    <td>{{ $task->user->name }}</td>
                                    <td>
                                        {{-- @if(Auth::user()->scope) --}}
                                        @can('update-jd-tasks')
                                            <a class="btn btn-primary  rounded-pill px-4 py-1" href="{{ route('jd.edit', $task->id) }}">Edit</a>
                                        @endcan
                                        @can('delete-jd-tasks')
                                            <form action="{{ route('jd.destroy', $task->id) }}" method="POST" style="display:inline;">
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