@extends('layouts.master-layout')
@section('active-page')
    Categories
@endsection
@section('title')
    Categories
@endsection
@section('extra-styles')
    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._item-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('add-new-category')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Category Name</label>
                                    <input type="text" placeholder="Category Name" name="category_name" value="{{old('category_name')}}" class="form-control">
                                    @error('category_name')<i class="text-danger">{{$message}}</i>@enderror
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
                    <h4>Categories</h4>
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
                                <th class="wd-15p">Category Name</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$category->category_name ?? '' }}</td>
                                    <td>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#categoryModal_{{$category->id}}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <div class="modal" id="categoryModal_{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Category</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('update-category')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Category Name</label>
                                                                <input type="text" placeholder="Category Name" name="category_name" value="{{$category->category_name ?? ''}}" class="form-control">
                                                                @error('category_name')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <input type="hidden" name="category" value="{{$category->id}}">
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
