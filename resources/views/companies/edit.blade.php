@extends('layout.app')
@section('title', 'Edit Company | TSP - Task Management System')
@section('pageTitle', 'Company Details')
@section('breadcrumTitle', 'Edit Company Details')

@section('content')
<!-- Start Page Content here -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Edit Company Details </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.update') }}" method="post" id="company_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $company->id }}">

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="name">Company Name</label>
                        <div class="col-md-10">
                            <input type="text" name="name" id="name" value="{{ $company->name }}" class="form-control" placeholder="Type here company name..." required>
                            <div class="invalid-feedback text-danger">
                                company Name is required.
                            </div>
                            @if ($errors->has('name'))
                            <span class="help-block text-danger">
                                {{ $errors->first('name') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="email">Email</label>
                        <div class="col-md-10">
                            <input type="email" name="email" id="email" value="{{ $company->email }}" class="form-control" placeholder="Email" required>
                            <div class="invalid-feedback text-danger">
                                Email is required.
                            </div>
                            @if ($errors->has('email'))
                            <span class="help-block text-danger">
                                {{ $errors->first('email') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="phone">Phone Number</label>
                        <div class="col-md-10">
                            <input type="number" name="phone" id="phone" value="{{ $company->phone ?? '' }}" class="form-control">
                            @if ($errors->has('phone'))
                            <span class="help-block text-danger">
                                {{ $errors->first('phone') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="whatsapp">WhatsApp</label>
                        <div class="col-md-10">
                            <input type="number" name="whatsapp" id="whatsapp" value="{{ $company->whatsapp ?? '' }}" class="form-control">
                            @if ($errors->has('whatsapp'))
                            <span class="help-block text-danger">
                                {{ $errors->first('whatsapp') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="logo">Company Logo</label>
                        <div class="col-md-10">
                            <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                            @if ($errors->has('logo'))
                            <span class="help-block text-danger">
                                {{ $errors->first('logo') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="joining_date">Joining Date</label>
                        <div class="col-md-10">
                            <input type="date" name="joining_date" id="joining_date" value="{{ $company->joining_date ? \Carbon\Carbon::parse($company->joining_date)->format('Y-m-d') : '' }}" class="form-control">
                            @if ($errors->has('joining_date'))
                            <span class="help-block text-danger">
                                {{ $errors->first('joining_date') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="mb-2 row">
                        <label class="col-md-2 col-form-label" for="expiry_date">Contract Expired</label>
                        <div class="col-md-10">
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ $company->expiry_date ? \Carbon\Carbon::parse($company->expiry_date)->format('Y-m-d') : '' }}" class="form-control">
                            @if ($errors->has('expiry_date'))
                            <span class="help-block text-danger">
                                {{ $errors->first('expiry_date') }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection