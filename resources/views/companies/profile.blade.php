@extends('layout.app')
@section('title', 'User Profile | TSP - Task Management System')
@section('pageTitle', 'User Profile')

@section('content')

<!-- Start Page Content here -->

<div class="pcoded-content">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Users Management</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Users Details</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">

        <!-- Latest Customers start -->
        <div class="col-lg-12">
            <div class="card table-card review-card">
                <div class="card-header borderless ">
                    <h5>Customer Reviews</h5>
                    <div class="card-header-right">
                        <div class="btn-group card-option">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="feather icon-more-horizontal"></i>
                            </button>
                            <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                                <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                                <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                                <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="review-block">
                        <div class="row">
                            <div class="col-12">
                                <div class="p-2">
                                    <form action="{{ route('users.profile_update') }}" method="post" id="user_profile_form" class="form-horizontal needs-validation" role="form" novalidate enctype="multipart/form-data">
                                        @csrf
                                        {{-- <input type="hidden" name="id" value="{{ $user->id }}"> --}}
                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="name">Full Name</label>
                                            <div class="col-md-10">
                                                <input type="text" name="name" id="name" value="{{ $user->name }}" class="form-control" placeholder="Type here full name..." required>
                                                <div class="invalid-feedback text-danger">
                                                    Name is required.
                                                </div>
                                                @if ($errors->has('name'))
                                                <span class="help-block text-danger">
                                                    {{ $errors->first('name') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="password">Password</label>
                                            <div class="col-md-10">
                                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                            </div>
                                        </div>

                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="profile_pic">Profile Picture</label>
                                            <div class="col-md-10">
                                                <input type="file" name="profile_pic" id="profile_pic" class="form-control" accept="image/*">
                                                @if ($errors->has('profile_pic'))
                                                <span class="help-block text-danger">
                                                    {{ $errors->first('profile_pic') }}
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="mb-2 row">
                                            <label class="col-md-2 col-form-label" for="phone">Phone Number</label>
                                            <div class="col-md-10">
                                                <input type="number" name="phone" id="phone" value="{{ $user->phone ?? '' }}" class="form-control">
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
                                                <input type="number" name="whatsapp" id="whatsapp" value="{{ $user->whatsapp ?? '' }}" class="form-control">
                                                @if ($errors->has('whatsapp'))
                                                <span class="help-block text-danger">
                                                    {{ $errors->first('whatsapp') }}
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
                    <div class="text-right  m-r-20">
                        <a href="#!" class="b-b-primary text-primary">View all Customer Reviews</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Latest Customers end -->
    </div>
    <!-- [ Main Content ] end -->
</div>


@endsection