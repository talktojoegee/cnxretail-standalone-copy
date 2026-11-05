@extends('layouts.admin-layout')
@section('active-page')
    Manage Pricing
@endsection
@section('title')
    Manage Pricing
@endsection
@section('extra-styles')
    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('add-pricing')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Price Name</label>
                                    <input type="text" placeholder="Price Name" name="name" value="{{ old('name') }}" class="form-control">
                                    @error('name')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <input type="number" step="0.01" placeholder="Price" name="amount" value="{{ old('amount') }}" class="form-control">
                                    @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Duration <small>(in days)</small></label>
                                    <input type="number" placeholder="Duration" name="duration" value="{{ old('duration') }}" class="form-control">
                                    @error('duration')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="description" id="description" style="resize: none;"
                                              class="form-control">{{ old('description') }}</textarea>
                                    @error('description')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group d-flex justify-content-center">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Submit</button>
                                    </div>
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
                    <h4>Pricing</h4>
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
                                <th class="wd-15p">Name</th>
                                <th class="wd-15p">Amount</th>
                                <th class="wd-15p">Duration</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($pricings as $pricing)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$pricing->price_name ?? '' }}</td>
                                    <td>{{ number_format($pricing->price ?? 0,2)  }}</td>
                                    <td>{{$pricing->duration ?? '' }} days</td>
                                    <td>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#pricingModal_{{$pricing->id}}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <div class="modal" id="pricingModal_{{$pricing->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Pricing</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('update-pricing')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Price Name</label>
                                                                <input type="text" placeholder="Price Name" name="name" value="{{$pricing->price_name ?? ''}}" class="form-control">
                                                                @error('name')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Amount</label>
                                                                <input type="number" step="0.01" placeholder="Price" name="amount" value="{{$pricing->price ?? ''}}" class="form-control">
                                                                @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Duration <small>(in days)</small></label>
                                                                <input type="number" placeholder="Duration" name="duration" value="{{$pricing->duration ?? ''}}" class="form-control">
                                                                @error('duration')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Description</label>
                                                                <textarea name="description" id="description" style="resize: none;"
                                                                          class="form-control">{{$pricing->description ?? '' }}</textarea>
                                                                @error('description')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <input type="hidden" name="price" value="{{$pricing->id}}">
                                                            <div class="form-group d-flex justify-content-center">
                                                                <div class="btn-group">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"> <i class="ti-close mr-2"></i> Close</button>
                                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Save changes</button>
                                                                </div>
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
