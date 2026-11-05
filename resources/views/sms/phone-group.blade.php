@extends('layouts.master-layout')
@section('active-page')
    Phone Group
@endsection
@section('title')
    Phone Group
@endsection
@section('extra-styles')
    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('phone-group')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Phone Group Name</label>
                                    <input type="text" placeholder="Phone Group Name" name="group_name" value="{{old('group_name')}}" class="form-control">
                                    @error('group_name')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Contact</label>
                                    <textarea name="phone_numbers" id="contact" cols="30" rows="10" style="resize: none" placeholder="Enter a list of phone numbers separated by comma." class="form-control">{{old('phone_numbers')}}</textarea>
                                    @error('phone_numbers') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group d-flex justify-content-center">
                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4>Phone Groups</h4>
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Phone Group Name</th>
                                <th class="wd-15p">Count</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($groups as $group)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$group->group_name ?? '' }}</td>
                                    <td>{{number_format(count(explode(",",$group->phone_numbers) ?? 0))}}</td>
                                    <td>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#categoryModal_{{$group->id}}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <div class="modal" id="categoryModal_{{$group->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Phone Group</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('add-new-category')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Phone Group Name</label>
                                                                <input type="text" placeholder="Phone Group Name" name="group_name" value="{{old('group_name', $group->group_name)}}" class="form-control">
                                                                @error('group_name')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Phone Numbers</label>
                                                                <textarea name="contact" id="contact" cols="30" rows="10" style="resize: none" placeholder="Enter a list of phone numbers separated by comma." class="form-control">{{old('contact',$group->phone_numbers)}}</textarea>
                                                                @error('contact') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group d-flex justify-content-center">
                                                                <input type="hidden" name="group" value="{{$group->id}}">
                                                                <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Save changes</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatable/datatable.js"></script>
    <script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
@endsection
