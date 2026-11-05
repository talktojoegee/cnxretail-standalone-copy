@extends('layouts.master-layout')
@section('active-page')
    Profile
@endsection
@section('title')
    Profile
@endsection
@section('extra-styles')


@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
                                <img class="" src="/assets/drive/{{Auth::user()->avatar ?? "avatar.jpg"}}" alt="img">
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1">{{$user->first_name ?? '' }} {{$user->surname ?? '' }}</h4>
                                <h6 class="text-muted mb-4">Member Since: {{date('d M, Y', strtotime($user->created_at))}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">Profile</a></li>
                                @if(Auth::user()->id == $user->id)
                                <li><a href="#tab-61" data-toggle="tab" class="">Profile Picture</a></li>
                                <li><a href="#tab-71" data-toggle="tab" class="">Settings</a></li>
                                <li><a href="#tab-81" data-toggle="tab" class="">Change Password</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                @if(session()->has('success'))
                    <div class="alert alert-success mb-4">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Great!</strong>
                        <hr class="message-inner-separator">
                        <p>{!! session()->get('success') !!}</p>
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-warning mb-4">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Whoops!</strong>
                        <hr class="message-inner-separator">
                        <p>{!! session()->get('error') !!}</p>
                    </div>
                @endif
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>Personal Information</strong> <sup>{!! $user->account_status == 1 ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Deactivated</span>"  !!}</sup></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><strong>Full Name :</strong> {{$user->first_name ?? '' }} {{$user->surname ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email :</strong> {{$user->email ?? ''}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender :</strong> {{$user->gender == 1 ? 'Male' : 'Female' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><strong>Mobile No. :</strong> {{$user->mobile_no ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Address :</strong> {{$user->address ?? '' }}</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->id == $user->id)
                <div class="tab-pane" id="tab-61">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{route('change-avatar')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" name="avatar">
                                    @error('avatar') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group d-flex justify-content-center">
                                    <button class="btn btn-primary">Save Image</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab-71">
                    <form action="{{route('update-profile')}}" method="post">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Profile</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputname">First Name</label>
                                            <input type="text" name="first_name" class="form-control" value="{{$user->first_name ?? '' }}" placeholder="First Name">
                                            @error('first_name') <i class="text-danger">{{$message}}</i>@enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label for="exampleInputname1">Surname</label>
                                            <input type="text" name="last_name" class="form-control" value="{{$user->surname ?? '' }}" placeholder="Enter Last Name">
                                            @error('last_name') <i class="text-danger">{{$message}}</i>@enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <input type="email" class="form-control" readonly value="{{$user->email ?? '' }}"  placeholder="Email address">

                                </div>
                                <div class="form-group">
                                    <label for="exampleInputnumber">Mobile No.</label>
                                    <input type="number" name="mobile_no" class="form-control" value="{{$user->mobile_no ?? '' }}" placeholder="Mobile No.">
                                    @error('mobile_no') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" placeholder="Enter address" rows="2">{{$user->address ?? '' }}</textarea>
                                    @error('address') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <select class="form-control select2 select2-hidden-accessible" name="gender" tabindex="-1" aria-hidden="true">
                                                <option disabled selected>--Select gender--</option>
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                            </select>
                                        </div>
                                    </div>
                                    @error('gender') <i class="text-danger">{{$message}}</i>@enderror
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                <button type="submit" class="btn btn-success mt-1">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="tab-81">
                    <div class="row">
                        <form action="{{route('change-password')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Change Password</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputname">Current Password</label>
                                                <input type="password" class="form-control" placeholder="Current Password" name="current_password">
                                                @error('current_password') <i class="text-danger">{{$message}}</i> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputname">New Password</label>
                                                <input type="password" class="form-control" placeholder="New Password" name="password">
                                                @error('password') <i class="text-danger">{{$message}}</i> @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-12">
                                            <div class="form-group">
                                                <label for="exampleInputname">Re-type Password</label>
                                                <input type="password" class="form-control" placeholder="Re-type Password" name="password_confirmation">
                                                @error('password_confirmation') <i class="text-danger">{{$message}}</i> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success mt-1">Change Password</button>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div><!-- COL-END -->
    </div>
@endsection

@section('extra-scripts')

@endsection
