@extends('layouts.admin-layout')
@section('active-page')
    Add New Admin User
@endsection
@section('title')
    Add New Admin User
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
                        <form action="{{route('add-new-admin-user')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Add New Admin User</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Details
                                </div>
                                <p class="mt-3 pl-3"><strong class="text-danger">Note: </strong> The default password is <code>password123</code>. This should be changed to something else upon login.</p>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Full Name</label>
                                                <input type="text" class="form-control" value="{{old('full_name') }}" name="full_name" placeholder="Full Name">
                                                @error('full_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Mobile No.</label>
                                                <input type="text" class="form-control" name="mobile_no" placeholder="Mobile No." value="{{old('mobile_no') }}" >
                                                @error('mobile_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Email Address</label>
                                                <input type="email" class="form-control" name="email" value="{{old('email')}}" placeholder="Email Address">
                                                @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Choose Password</label>
                                                <input type="text" value="password123" readonly class="form-control" name="password" placeholder="Choose Password" >
                                                @error('password') <i class="text-danger">{{$message}}</i>@enderror
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
