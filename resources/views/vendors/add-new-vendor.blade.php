@extends('layouts.master-layout')
@section('active-page')
    Add New Vendor
@endsection
@section('title')
    Add New Vendor
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    @include('vendors.partials._menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{route('add-new-vendor')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Add New Vendor</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Company Info
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" value="{{old('company_name') }}" name="company_name" placeholder="Company Name">
                                                @error('company_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Phone No.</label>
                                                <input type="text" class="form-control" name="company_phone_no" placeholder="Company Phone No." value="{{old('company_phone_no') }}" >
                                                @error('company_phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Company Email Address</label>
                                                <input type="text" class="form-control" name="company_email" value="{{old('company_email')}}" placeholder="Company Email Address">
                                                @error('company_email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Website</label>
                                                <input type="text" class="form-control" name="website" placeholder="Website" value="{{old('website') }}" >
                                                @error('website') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address" placeholder="Address" value="{{old('address') }}" >
                                                @error('address') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Contact Person
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" value="{{old('first_name') }}" name="first_name" placeholder="First Name">
                                                @error('first_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Phone No.</label>
                                                <input type="text" class="form-control" name="phone_no" placeholder="Phone No." value="{{old('phone_no') }}" >
                                                @error('phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="Last Name">
                                                @error('last_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Email Address</label>
                                                <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{old('email') }}" >
                                                @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" name="position" placeholder="Position" value="{{old('position') }}" >
                                                @error('position') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancle</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')

@endsection
