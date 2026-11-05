@extends('layouts.master-layout')
@section('active-page')
    Edit Product
@endsection
@section('title')
    Edit Product
@endsection
@section('extra-styles')
    <link href="/assets/plugins/select2/select2.min.css" rel="stylesheet"/>
@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._item-menu')
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
                    @if(session()->has('error'))
                        <div class="alert alert-warning mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Whoops!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('error') !!}</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{route('update-product')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Add New Item</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Item Info
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="hidden" value="1" name="item_type">
                                                <label class="form-label">Product Name</label>
                                                <input type="text" class="form-control" name="item_name" value="{{old('item_name', $product->item_name)}}" placeholder="Product/Service Name">
                                                @error('item_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row product-container">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Category</label>
                                                <select name="category"  class="form-control select2-show-search" >
                                                    <option selected disabled>--Select category--</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{$category->id}}" {{$category->id == $product->category_id ? 'selected' : '' }}>{{$category->category_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category') <i class="text-danger">{{$message}}</i>@enderror
                                                <input type="hidden" name="product" value="{{$product->id}}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="quantity" placeholder="Quantity" value="{{old('quantity', $product->quantity) }}" >
                                                @error('quantity') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row product-container">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Amount</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Cost Price</span>
                                                    </div>
                                                    <input type="number" name="cost_price" step="0.01" value="{{old('cost_price', $product->cost_price)}}" class="form-control" placeholder="Cost Price" aria-label="Username" aria-describedby="basic-addon1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Selling Price</span>
                                                    </div>
                                                    <input type="number" name="selling_price" value="{{old('selling_price', $product->selling_price)}}" step="0.01" class="form-control" placeholder="Selling Price" aria-label="Username" aria-describedby="basic-addon1">
                                                </div>
                                                @error('cost_price') <i class="text-danger">{{$message}}</i>@enderror
                                                <br> @error('selling_price') <i class="text-danger">{{$message}}</i>@enderror
                                                <br>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Image(s)</label>
                                                <input type="file" class="form-control-file" name="attachments[]" multiple " >
                                                @error('attachments') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="avatar-list">
                                                @foreach($product->getItemGalleryImages as $gallery)
                                                    <span  class="avatar avatar-xxl cover-image"  data-image-src="/assets/drive/{{$gallery->attachment}}">
                                                    <i data-toggle="modal" data-target="#itemModal_{{$gallery->id}}" class="ti-trash text-danger" style=" cursor: pointer; width:7px !important; height:7px !important; display:block; font-size:22px; float:right; margin-right:10px; background:#fff;"></i>
                                                    </span>
                                                    <div class="modal" id="itemModal_{{$gallery->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Are You Sure ?</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true" class="text-white">×</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                  <h6>This action cannot be undone. Are you sure you want to delete this image?</h6>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <a href="{{route('delete-image', $gallery->id)}}" class="btn btn-primary">Yes, please</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row product-container mt-3">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="" class="form-label">Description</label>
                                                <textarea name="p_description" placeholder="Type description here..." id="description" cols="20" style="resize: none;"
                                                          class="form-control">{{old('p_escription', $product->description)}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right button-container">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
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
    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>
@endsection
