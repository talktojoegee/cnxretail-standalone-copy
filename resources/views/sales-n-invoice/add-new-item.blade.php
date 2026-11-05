@extends('layouts.master-layout')
@section('active-page')
    Add New Item
@endsection
@section('title')
    Add New Item
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
                        <form action="{{route('add-new-item')}}" method="post" enctype="multipart/form-data">
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
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Item Type</label>
                                                <select name="item_type" id="item_type" class="form-control select2-show-search" >
                                                    <option selected>--Select item type--</option>
                                                    <option value="1">Product</option>
                                                    <option value="2">Service</option>
                                                </select>
                                                @error('item_type') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Product/Service Name</label>
                                                <input type="text" class="form-control" name="item_name" value="{{old('item_name')}}" placeholder="Product/Service Name">
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
                                                    <option value="{{$category->id}}">{{$category->category_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('category') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Quantity</label>
                                                <input type="number" class="form-control" name="quantity" placeholder="Quantity" value="{{old('quantity') }}" >
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
                                                    <input type="text" id="cost_price" name="cost_price"  value="{{old('cost_price')}}" class="form-control" placeholder="Cost Price" aria-label="Username" aria-describedby="basic-addon1">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1">Selling Price</span>
                                                    </div>
                                                    <input type="text" id="selling_price" name="selling_price" value="{{old('selling_price')}}" step="0.01" class="form-control" placeholder="Selling Price" aria-label="Username" aria-describedby="basic-addon1">
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
                                    <div class="row product-container">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea name="p_description" placeholder="Type description here..." id="description" cols="20" style="resize: none;"
                                                          class="form-control">{{old('p_escription')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row service-container">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Service Fee</label>
                                                <input type="text" id="service_fee" name="service_fee" value="{{old('service_fee')}}" placeholder="Service Fee" class="form-control">
                                                 @error('selling_price') <i class="text-danger">{{$message}}</i>@enderror
                                                <br>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Description</label>
                                                <textarea name="description" placeholder="Type description here..." id="description" cols="20" style="resize: none;"
                                                          class="form-control">{{old('description')}}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right button-container">
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
    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>
    <script>
        $(document).ready(function(){
            $('.product-container').hide();
            $('.service-container').hide();
            $('.button-container').hide();

            $(document).on('change', '#item_type', function(e){
                e.preventDefault();
                if($(this).val() == 1){
                    $('.product-container').show();
                    $('.service-container').hide();
                    $('.button-container').show();
                }else if($(this).val() == 2){
                    $('.product-container').hide();
                    $('.service-container').show();
                    $('.button-container').show();
                }
            });
            //cost price
            $('#cost_price').on('blur', function(e){
                e.preventDefault();
                var cst = $(this).val();
                $(this).val(formatter.format(cst));
            });
            $('#cost_price').on('focus', function(e){
                e.preventDefault();
                $(this).val("");
            });
            //selling price
            $('#selling_price').on('blur', function(e){
                e.preventDefault();
                var sp = $(this).val();
                $(this).val(formatter.format(sp));
            });
            $('#selling_price').on('focus', function(e){
                e.preventDefault();
               $(this).val("");
            });
            //service fee
            $('#service_fee').on('blur', function(e){
                e.preventDefault();
                var sf = $(this).val();
                $(this).val(formatter.format(sf));
            });
            $('#service_fee').on('focus', function(e){
                e.preventDefault();
                $(this).val("");
            });
        });

        var formatter = Intl.NumberFormat('en-US');
        function updateAmountFieldOnBlur(field, amount){
            //var cst = $(this).val();
            $(field).val(formatter.format(amount));
        }
    </script>
@endsection
