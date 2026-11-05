@extends('layouts.master-layout')
@section('active-page')
    Product Details
@endsection
@section('title')
    Product Details
@endsection
@section('extra-styles')


@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._item-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-8 col-md-12">
            <div class="card productdesc-1">
                <div class="card-body bg_gray">
                    <div id="carouselExampleControls" class="carousel slide " data-ride="carousel">
                        <div class="carousel-inner ">
                            @php $index = 1; @endphp
                            @foreach($product->getItemGalleryImages as $gallery)
                                <div class="carousel-item {{$index == 1 ? 'active' : ''}}">
                                    <img class="pro_img" alt="Product" src="/assets/drive/{{$gallery->attachment}}">
                                    <input type="hidden" value="{{$index++}}">
                                </div>
                            @endforeach
                        </div>
                        <a class="carousel-control-prev product-carousel-control" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next product-carousel-control" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <div class="mt-4 mb-4">
                        <h3>{{$product->item_name ?? ''}}</h3>
                        <h5 class="mb-3 mt-2">Description</h5>
                        {!! $product->description ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"> Other Details</div>
                </div>
                <div class="card-body">
                    <a href="{{route('update-item-details', $product->slug)}}" title="Edit Product" class="btn btn-icon  btn-warning float-right"><i class="ti-pencil"></i></a>
                    <div class="form-group">
                        <label for="">Cost Price: <strong>{{'₦'.number_format($product->cost_price,2)}}</strong></label>
                    </div>
                    <div class="form-group">
                        <label for="">Selling Price: <strong>{{'₦'.number_format($product->selling_price,2)}}</strong></label>
                    </div>
                    <div class="form-group">
                        <label for="">Sold: <strong>{{number_format($product->sold)}}</strong></label>
                    </div>
                    <div class="form-group">
                        <label for="">Category: <strong>{{$product->getCategory->category_name ?? '' }}</strong></label>
                    </div>
                    <div class="form-group">
                        <label for="">Added By: <strong>{{$product->getAddedBy->first_name ?? '' }} {{$product->getAddedBy->surname ?? '' }}</strong></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('extra-scripts')

@endsection
