@extends('layout.app')
@section('title', 'Projects List | TSP - Task Management System')
@section('pageTitle', 'Projects List')
@section('breadcrumTitle', 'Projects List')

@section('content')
<!-- Start Page Content here -->

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Projects List</h5>
            </div>
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table" id="data_table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Status</th>
                                @if (system_role())
                                <th> Company</th>
                                @endif
                                <th>Deadline</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                            @php
                                $projectStatus = $project->status ?? '';

                                if ($projectStatus && config('constants.PROJECT_STATUS_LIST')[$projectStatus] == 'Running') {
                                    $btnClass = 'btn-primary';
                                } elseif ($projectStatus && config('constants.STATUS_LIST')[$projectStatus] == 'Closed') {
                                    $btnClass = 'btn-success';
                                } else {
                                    $btnClass = 'btn-secondary';
                                }
                            @endphp
                            <tr>
                                <td>{{ $project->name }}</td>
                                <td>
                                    
                                    <a href="{{ route('projects.show', base64_encode($project->id)) }}" class="btn {{ $btnClass }}  rounded-pill py-0">{{ config('constants.STATUS_LIST')[$project->status] ?? '' }}</a>
                                </td>
                                @if (system_role())
                                <td>{{ $project->company->name }}</td>
                                @endif
                                <td>{{ format_date($project->deadline) }}</td>
                                <td>
                                    {{-- <a class="btn btn-info" href="{{ route('roles.show', $role->id) }}">Show</a> --}}
                                    @can('update-projects')
                                    <a class="btn btn-primary  rounded-pill px-4 py-1" href="{{ route('projects.edit', $project->id) }}">Edit</a>
                                    @endcan
                                    @can('delete-projects')
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger rounded-pill px-4 py-1" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                    </form>
                                    @endcan
                                    @can('view-projects')
                                    <a class="btn btn-primary rounded-pill px-4 py-1" href="{{ route('projects.show', base64_encode($project->id)) }}">View</a>
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