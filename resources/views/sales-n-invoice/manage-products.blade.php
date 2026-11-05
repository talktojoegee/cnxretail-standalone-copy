@extends('layouts.master-layout')
@section('active-page')
    Manage Products
@endsection
@section('title')
    Manage Products
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="wd-15p">Image</th>
                                    <th class="wd-15p">Product Name</th>
                                    <th class="wd-15p">Quantity</th>
                                    <th class="wd-15p">Cost Price</th>
                                    <th class="wd-15p">Selling Price</th>
                                    <th class="wd-15p">Sold</th>
                                    <th class="wd-25p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $serial = 1; @endphp
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>
                                            <img style="width: 32px; height: 32px; border-radius: 50%;" src="/assets/drive/{{$product->getItemFirstGalleryImage($product->id)->attachment}}" alt="{{$product->item_name ?? ''}}">
                                        </td>
                                        <td>{{strlen($product->item_name) > 48 ? substr($product->item_name,0,48).'...' : $product->item_name }}</td>
                                        <td>{{$product->quantity ?? '' }}</td>
                                        <td class="text-right">{{number_format($product->cost_price,2)}}</td>
                                        <td class="text-right">{{number_format($product->selling_price,2)}}</td>
                                        <td>{{number_format($product->sold )}}</td>
                                        <td>
                                            <a href="{{route('product-details', $product->slug)}}" class="btn btn-sm btn-info"> <i class="ti-eye"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
