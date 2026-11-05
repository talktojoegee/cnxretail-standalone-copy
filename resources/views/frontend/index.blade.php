@extends('layouts.frontend-layout')

@section('title')
    Home
@endsection

@section('main-content')
    <div class="hero-banner full jumbo-banner" style="background:#f4f9fd url(assets/assets/img/bg2.png);">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-7 col-md-8">
                    <h1>Organize <span class="text-info">Your Sales</span> Processes!</h1>
                    <p class="lead">All in one place.</p>
                        <div class="cnx-hero-actions">
                            <a href="{{ route('contact-us') }}" class="cnx-hero-btn">Send Request</a>
                            <a href="{{ route('register') }}" class="cnx-hero-btn cnx-hero-btn-alt">Start Trial</a>
                        </div>

                </div>

                <div class="col-lg-5 col-md-4">
                    <img src="/assets/assets/img/a-2.png" alt="latest property" class="img-fluid">
                </div>

            </div>
        </div>
    </div>
    <section class="how-it-works">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="sec-heading">
                        <h2>How It <span class="theme-cl-2">Works?</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="working-process"><span class="process-img"><img src="/assets/assets/img/step-1.png" class="img-responsive" alt=""><span class="process-num">01</span></span>
                        <h4>Create An Account</h4>
                        <p>Post a job to tell us about your project. We'll quickly match you with the right freelancers find place best.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="working-process"><span class="process-img"><img src="/assets/assets/img/step-2.png" class="img-responsive" alt=""><span class="process-num">02</span></span>
                        <h4>Search Jobs</h4>
                        <p>Post a job to tell us about your project. We'll quickly match you with the right freelancers find place best.</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="working-process"><span class="process-img"><img src="/assets/assets/img/step-3.png" class="img-responsive" alt=""><span class="process-num">03</span></span>
                        <h4>Save &amp; Apply</h4>
                        <p>Post a job to tell us about your project. We'll quickly match you with the right freelancers find place best.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="min-sec">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="sec-heading">
                        <h2>Featured <span class="theme-cl-2">Products</span></h2>
                        <p>These products are randomly selected from our various vendors on the platform.</p>
                    </div>
                </div>
            </div>

            <div class="cnx-tabs" id="featured-products">
                <a class="{{ $activeProduct ? '' : 'is-active' }}" href="{{ route('homepage', array_filter(['services' => request('services')])) }}#featured-products">All categories</a>
                @foreach($productCategories as $category)
                    <a class="{{ optional($activeProduct)->id === $category->id ? 'is-active' : '' }}" href="{{ route('homepage', array_filter(['products' => $category->slug, 'services' => request('services')])) }}#featured-products">{{ $category->category_name }}</a>
                @endforeach
            </div>

            <div class="row justify-content-center">
                @forelse($products as $item)
                    @php $photo = optional($item->getItemGalleryImages->first())->attachment; @endphp
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                        <div class="job_grid_02">
                            <div class="blog-thumb">
                                <a href="{{ route('view-item', $item->slug) }}">
                                    @if($photo)
                                        <img src="/assets/drive/{{ $photo }}" class="img-fluid" alt="{{ $item->item_name }}">
                                    @else
                                        <span class="cnx-letter">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
                                    @endif
                                </a>
                            </div>
                            <div class="jb_grid_01_caption">
                                <h4 class="_jb_title">
                                    <a href="{{ route('view-item', $item->slug) }}" title="{{ $item->item_name }}">{{ $item->item_name }}</a>
                                </h4>
                            </div>
                            <div class="jb_grid_01_footer">
                                <a href="{{ route('view-item', $item->slug) }}" class="_jb_apply">View product</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center"><p>No products in this category yet.</p></div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="mt-3 text-center">
                        @if($activeProduct)
                            <a href="{{ route('product-category', $activeProduct->slug) }}" class="_browse_more-2 light">View all in {{ $activeProduct->category_name }}</a>
                        @else
                            <a href="{{ route('marketplace') }}" class="_browse_more-2 light">View all products</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="light-w" id="featured-services">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="sec-heading">
                        <h2>Featured <span class="theme-cl-2">Services</span></h2>
                        <p>Services listed by sellers on the platform.</p>
                    </div>
                </div>
            </div>

            <div class="cnx-tabs">
                <a class="{{ $activeSeller ? '' : 'is-active' }}" href="{{ route('homepage', array_filter(['products' => request('products')])) }}#featured-services">All services</a>
                @foreach($serviceSellers as $seller)
                    <a class="{{ optional($activeSeller)->id === $seller->id ? 'is-active' : '' }}" href="{{ route('homepage', array_filter(['products' => request('products'), 'services' => $seller->slug])) }}#featured-services">{{ trim($seller->company_name) }}</a>
                @endforeach
            </div>

            <div class="row justify-content-center">
                @forelse($services as $item)
                    @php $photo = optional($item->getItemGalleryImages->first())->attachment; @endphp
                    <div class="col-xl-3 col-lg-4 col-md-4 col-sm-6">
                        <div class="job_grid_02">
                            <div class="blog-thumb">
                                <a href="{{ route('view-item', $item->slug) }}">
                                    @if($photo)
                                        <img src="/assets/drive/{{ $photo }}" class="img-fluid" alt="{{ $item->item_name }}">
                                    @else
                                        <span class="cnx-letter">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
                                    @endif
                                </a>
                            </div>
                            <div class="jb_grid_01_caption">
                                <h4 class="_jb_title">
                                    <a href="{{ route('view-item', $item->slug) }}" title="{{ $item->item_name }}">{{ $item->item_name }}</a>
                                </h4>
                                <div class="cnx-card-meta">{{ $item->getTenant->company_name ?? '' }}</div>
                            </div>
                            <div class="jb_grid_01_footer">
                                <a href="{{ route('view-item', $item->slug) }}" class="_jb_apply">View service</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center"><p>No services from this seller yet.</p></div>
                @endforelse
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="mt-3">
                        @if($activeSeller)
                            <a href="{{ route('vendor-store', $activeSeller->slug) }}" class="_browse_more-2 light">View all from {{ trim($activeSeller->company_name) }}</a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="gray-light">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <div class="sec-heading">
                        <h2>What People <span class="theme-cl-2">Saying</span></h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="owl-carousel owl-theme middle-arrow-hover" id="reviews-slide">

                        <!-- Single Review -->
                        <div class="item testimonial-center">
                            <div class="smart-tes-author">
                                <div class="st-author-box">
                                    <div class="st-author-thumb">
                                        <img src="assets/img/team-1.jpg" class="img-fluid" alt="" />
                                    </div>
                                    <div class="st-author-info">
                                        <h4 class="st-author-title">Adam Williams</h4>
                                        <span class="st-author-subtitle theme-cl">CEO Of Microwoft</span>
                                    </div>
                                </div>
                            </div>
                            <div class="smart-tes-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</p>
                            </div>
                        </div>

                        <!-- Single Review -->
                        <div class="item testimonial-center">
                            <div class="smart-tes-author">
                                <div class="st-author-box">
                                    <div class="st-author-thumb">
                                        <img src="assets/img/team-2.jpg" class="img-fluid" alt="" />
                                    </div>
                                    <div class="st-author-info">
                                        <h4 class="st-author-title">Lilly Wikdoner</h4>
                                        <span class="st-author-subtitle theme-cl">Content Writer</span>
                                    </div>
                                </div>
                            </div>
                            <div class="smart-tes-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</p>
                            </div>
                        </div>

                        <!-- Single Review -->
                        <div class="item testimonial-center">
                            <div class="smart-tes-author">
                                <div class="st-author-box">
                                    <div class="st-author-thumb">
                                        <img src="assets/img/team-3.jpg" class="img-fluid" alt="" />
                                    </div>
                                    <div class="st-author-info">
                                        <h4 class="st-author-title">Shaurya Williams</h4>
                                        <span class="st-author-subtitle theme-cl">Manager Of Doodle</span>
                                    </div>
                                </div>
                            </div>
                            <div class="smart-tes-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</p>
                            </div>
                        </div>

                        <!-- Single Review -->
                        <div class="item testimonial-center">
                            <div class="smart-tes-author">
                                <div class="st-author-box">
                                    <div class="st-author-thumb">
                                        <img src="assets/img/team-4.jpg" class="img-fluid" alt="" />
                                    </div>
                                    <div class="st-author-info">
                                        <h4 class="st-author-title">Shrithi Setthi</h4>
                                        <span class="st-author-subtitle theme-cl">CEO Of Applio</span>
                                    </div>
                                </div>
                            </div>
                            <div class="smart-tes-content">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </section>
    <section id="download-app">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6 col-md-6">
                    <div class="_setup_process">
                        <h2>Download App Free App For <span class="theme-cl-2">Android And IPhone</span></h2>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia</p>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                    </div>
                    <div class="btn-box clearfix mt-5">
                        <a href="index.html" class="download-btn play-store">
                            <i class="fa fa-android"></i>
                            <span>Download on</span>
                            <h3>Google Play</h3>
                        </a>

                        <a href="index.html" class="download-btn app-store">
                            <i class="fa fa-apple"></i>
                            <span>Download on</span>
                            <h3>App Store</h3>
                        </a>
                    </div>
                </div>

                <div class="col-lg-6 col-md-6">
                    <img src="/assets/assets/img/app.png" class="img-fluid" alt="">
                </div>

            </div>
        </div>
    </section>
@endsection
