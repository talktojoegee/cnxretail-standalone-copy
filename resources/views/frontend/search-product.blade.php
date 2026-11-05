@extends('layouts.marketplace-layout')

@section('title')
    Search Result
@endsection
@section('current-page')
    Search Result
@endsection

@include('partials.marketplace._header')
<body>
<div class="page-wrapper">
    <h1 class="d-none">CNX Retail</h1>
    <header class="header header-border">
        <div class="header-middle">
            <div class="container">
                @include('partials.marketplace._search-bar')
            </div>
        </div>
        <div class="header-bottom sticky-content fix-top sticky-header">
            <div class="container">
                <div class="inner-wrap">
                    <div class="header-left">
                        <div class="dropdown category-dropdown has-border" data-visible="true">
                            <a href="#" class="category-toggle" role="button" data-toggle="dropdown"
                               aria-haspopup="true" aria-expanded="true" data-display="static"
                               title="Browse Categories">
                                <i class="w-icon-category"></i>
                                <span>Browse Categories</span>
                            </a>
                            <div class="dropdown-box">
                                <ul class="menu vertical-menu category-menu">
                                    @foreach($categories as $cat)
                                        <li>
                                            <a href="{{route('product-category', $cat->slug)}}">
                                                <i class="w-icon-angle-right"></i>{{$cat->category_name ?? '' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <nav class="main-nav">
                            <ul class="menu active-underline">
                                <li>
                                    <a href="{{route('homepage')}}">Home</a>
                                </li>
                                <li>
                                    <a href="{{route('marketplace')}}">Marketplace</a>
                                </li>
                                <li>
                                    <a href="{{route('contact-us')}}">Contact us</a>
                                </li>
                                <li>
                                    <a href="{{route('login')}}">Login</a>
                                </li>
                                <li>
                                    <a href="{{route('register')}}">Start Trial</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
    @section('main-content')
        <div class="container">
            <div class="shop-content row gutter-lg">
                <!-- Start of Sidebar, Shop Sidebar -->
                <aside class="sidebar shop-sidebar sticky-sidebar-wrapper sidebar-fixed">
                    <!-- Start of Sidebar Overlay -->
                    <div class="sidebar-overlay"></div>
                    <a class="sidebar-close" href="#"><i class="close-icon"></i></a>

                    <!-- Start of Sidebar Content -->
                    <div class="sidebar-content scrollable">
                        <!-- Start of Sticky Sidebar -->
                        <div class="sticky-sidebar">
                            <div class="widget widget-collapsible">
                                <h3 class="widget-title"><span>All Vendors</span></h3>
                                <ul class="widget-body filter-items search-ul">
                                    @foreach($vendors as $vendor)
                                        <li><a href="{{route('vendor-store', $vendor->slug)}}">{{$vendor->company_name ?? '' }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </aside>
                <div class="main-content">
                    <nav class="toolbox sticky-toolbox sticky-content fix-top">
                        <div class="toolbox-left">
                            <h3>Search result for: <label for="" style="color: #ffa800;">{{$keyword ?? '' }}</label></h3>
                        </div>

                    </nav>
                    <div class="product-wrapper row cols-md-3 cols-sm-2 cols-2">
                        @if($items->count() > 0)
                            @foreach($items as $item)
                                <div class="product-wrap">
                                    <div class="product text-center">
                                        <figure class="product-media">
                                            <a href="{{route('view-item', $item->slug)}}">
                                                <img src="/assets/drive/{{$item->getItemFirstGalleryImage($item->id)->attachment ?? 'image.jpg'}}" alt="Product" width="274"
                                                     height="309" style="width:274px !important; height:309px !important;" />
                                            </a>
                                        </figure>
                                        <div class="product-details">
                                            <div class="product-cat">
                                                <a href="{{route('view-item', $item->slug)}}">{{ $item->getCategory->category_name ?? '' }}</a>
                                            </div>
                                            <h3 class="product-name">
                                                <a href="{{route('view-item', $item->slug)}}">{{ strlen($item->item_name) > 28 ? substr($item->item_name,0,25).'...' : $item->item_name}}</a>
                                            </h3>
                                            <div class="product-pa-wrapper">
                                                <div class="product-price">
                                                    ₦{{number_format($item->selling_price,2)}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h4>No products found.</h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection
</div>
<a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"> <i class="w-icon-angle-up"></i> <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"> <circle id="progress-indicator" fill="transparent" stroke="#000000" stroke-miterlimit="10" cx="35" cy="35" r="34" style="stroke-dasharray: 16.4198, 400;"></circle> </svg> </a>


@include('partials.marketplace._footer-script')
