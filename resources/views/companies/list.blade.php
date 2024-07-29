@extends('layout.app')
@section('title', 'Companies List | TSP - Task Management System')
@section('pageTitle', 'Companies List')
@section('breadcrumTitle', 'Companies List')

@section('content')
<!-- Start Page Content here -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Companies Management</h5>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table" id="data_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>WhatsApp</th>
                                    <th>Exp. Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($companies ?? [] as $key => $company)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->email }}</td>
                                        <td>{{ $company->phone }}</td>
                                        <td>{{ $company->whatsapp }}</td>
                                        <td>{{format_date($company->expiry_date) }}</td>
                                        <td>
                                            @can('update-companies')
                                            <a class="btn btn-primary rounded-pill px-4 py-1" href="{{ route('companies.edit', $company->id) }}">Edit</a>
                                            @endcan
                                            @can('delete-companies')
                                            <form action="{{ route('companies.destroy', $company->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-pill px-4 py-1" onclick="return confirm('Are you sure you want to delete this company?');">Delete</button>
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