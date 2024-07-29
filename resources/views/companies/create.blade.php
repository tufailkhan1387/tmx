@extends('layout.app')
@section('title', 'Create Company | TSP - Task Management System')
@section('pageTitle', 'Company Details')
@section('breadcrumTitle', 'Add New Company ')

@section('content')
<!-- Start Page Content here -->

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>Enter Company Details</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.store') }}" method="post" id="user_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-sm-3 col-form-label">Company Name</label>
                        <div class="col-sm-9">
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" placeholder="Type here full name..." required>
                        </div>
                        @if ($errors->has('name'))
                        <span class="help-block text-danger">
                            {{ $errors->first('name') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-sm-3 col-form-label">Contact Email</label>
                        <div class="col-sm-9">
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email" required>
                        </div>
                        @if ($errors->has('email'))
                        <span class="help-block text-danger">
                            {{ $errors->first('email') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Phone Number</label>
                        <div class="col-sm-9">
                            <input type="number" name="phone" id="phone" value="{{ old('phone') }}" placeholder="Phone" class="form-control">
                        </div>
                        @if ($errors->has('phone'))
                        <span class="help-block text-danger">
                            {{ $errors->first('phone') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Whatsapp</label>
                        <div class="col-sm-9">
                            <input type="number" name="whatsapp" id="whatsapp" placeholder="Phone" value="{{ old('whatsapp') }}" class="form-control">
                        </div>
                        @if ($errors->has('whatsapp'))
                        <span class="help-block text-danger">
                            {{ $errors->first('whatsapp') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="profile" class="col-sm-3 col-form-label">Company Logo</label>
                        <div class="col-sm-9">
                            <div class="input-group mb-3">
                                <div class="custom-file">
                                    <input type="file" name="logo" id="logo" class="custom-file-input" accept="image/*">
                                    <label class="custom-file-label" for="logo">Choose file</label>
                                </div>
                            </div>
                        </div>
                        @if ($errors->has('logo'))
                        <span class="help-block text-danger">
                            {{ $errors->first('logo') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Joining Date</label>
                        <div class="col-sm-9">
                            <input type="date" name="joining_date" id="joining_date" value="{{ old('joining_date') }}" class="form-control">
                        </div>
                        @if ($errors->has('joining_date'))
                        <span class="help-block text-danger">
                            {{ $errors->first('joining_date') }}
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-3 col-form-label">Contract Expired</label>
                        <div class="col-sm-9">
                            <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" class="form-control">
                        </div>
                        @if ($errors->has('expiry_date'))
                        <span class="help-block text-danger">
                            {{ $errors->first('expiry_date') }}
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