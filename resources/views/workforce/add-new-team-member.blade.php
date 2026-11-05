@extends('layouts.master-layout')
@section('active-page')
    Add New Team Member
@endsection
@section('title')
    Add New Team Member
@endsection
@section('extra-styles')


@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
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
                        <form action="{{route('add-new-team-member')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Add New Team Member</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Details
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
                                                <label class="form-label">Mobile No.</label>
                                                <input type="text" class="form-control" name="phone_no" placeholder="Phone No." value="{{old('phone_no') }}" >
                                                @error('phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Surname</label>
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
                                                <label class="form-label">Address</label>
                                                <textarea type="text" style="resize: none;" class="form-control" name="address" placeholder="Address" >{{old('address') }}</textarea>
                                                @error('address') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" id="gender" class="form-control">
                                                    <option disabled selected>--Select gender--</option>
                                                    <option value="1">Male</option>
                                                    <option value="2">Female</option>
                                                </select>
                                                @error('gender') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancel</a>
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
