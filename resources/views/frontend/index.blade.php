@extends('layouts.frontend-layout')

@section('title', 'Marketplace for vendors and buyers')
@section('meta-description', config('app.name').' is a marketplace where vendors open a store and buyers discover products, place orders, and check out.')

@section('main-content')
    @php
        // Hero slides: edit the wording, image, or button link of any slide here.
        $heroSlides = [
            [
                'audience' => 'For vendors',
                'heading' => 'Sell on Markethub',
                'tagline' => 'Online Store + Marketplace',
                'button' => 'Create a Store',
                'link' => route('register'),
                'image' => '/assets/assets/img/hero/vendor-market-stall.jpg',
                'focus' => '50% 40%',
                'source' => 'https://nappy.co/photo/shopping-in-market%2Bjaf9HWx_i6g_eLRaBjZQj', // CC0, from Nappy
            ],
            [
                'audience' => 'For buyers',
                'heading' => 'Discover and Buy',
                'tagline' => 'Explore vast categories, from fashion to electronics and everyday essentials',
                'button' => 'Sign Up',
                'link' => route('register'),
                'image' => '/assets/assets/img/hero/buyer-on-phone.jpg',
                'focus' => '50% 60%', // The product card overlay is aligned to this, so change both together.
                'portrait' => true, // Tall photo: shown as a panel on the right so it isn't over-zoomed.
                'productCard' => true, // Product card and arrow showing what she's browsing (wider screens only).
                'source' => 'https://nappy.co/photo/outdoor-relaxation-with-smartphone%2BQUNbaXfgQhv5g82trB-iR', // CC0, from Nappy
            ],
            [
                'audience' => 'For vendors',
                'heading' => 'Become a Vendor',
                'tagline' => 'Create your online store',
                'support' => 'Set up in minutes, list your products, and start receiving orders.',
                'button' => 'Start Selling',
                'link' => route('register'),
                'image' => '/assets/assets/img/hero/vendor-laptop.jpg',
                'focus' => '50% 30%',
                'source' => 'https://nappy.co/photo/black-woman-working-with-a-laptop%2BTBi1JQmOdVPQI_z9odVxR', // CC0, from Nappy
            ],
            [
                'audience' => 'For buyers',
                'heading' => 'Explore the Markethub',
                'tagline' => 'Browse products, order and checkout',
                'support' => 'Thousands of products from trusted vendors, with secure payment by CSwitch.',
                'button' => 'Shop Now',
                'link' => route('marketplace'),
                'image' => '/assets/assets/img/hero/buyer-on-sofa.jpg',
                'focus' => '50% 8%',
                'source' => 'https://nappy.co/photo/home-entertainment-woman-on-couch-using-ipad%2BjS08oe4lSEMyKXjtuUBRS', // CC0, from Nappy
            ],
        ];
    @endphp

    <section class="cnx-showcase" data-showcase aria-roledescription="carousel" aria-label="Markethub highlights for vendors and buyers">
        <div class="cnx-showcase-track" data-showcase-track aria-live="off">
            @foreach($heroSlides as $slide)
                @php $headingTag = $loop->first ? 'h1' : 'h2'; @endphp
                <div class="cnx-slide {{ $loop->first ? 'is-active' : '' }} {{ ($slide['productCard'] ?? false) ? 'cnx-slide-has-art' : '' }}" data-showcase-slide role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $loop->count }}">
                    @if($slide['portrait'] ?? false)
                        <div class="cnx-slide-media">
                            <img class="cnx-slide-image" src="{{ $slide['image'] }}" alt="" style="object-position: {{ $slide['focus'] }};" @unless($loop->first) loading="lazy" @endunless>
                            @if($slide['productCard'] ?? false)
                                @include('partials.frontend._hero-product-card')
                            @endif
                        </div>
                    @else
                        <img class="cnx-slide-image" src="{{ $slide['image'] }}" alt="" style="object-position: {{ $slide['focus'] }};" @unless($loop->first) loading="lazy" @endunless>
                    @endif
                    <div class="container cnx-slide-inner">
                        <div class="cnx-slide-copy">
                            <span class="cnx-slide-audience">{{ $slide['audience'] }}</span>
                            <{{ $headingTag }} class="cnx-slide-heading">{{ $slide['heading'] }}</{{ $headingTag }}>
                            <p class="cnx-slide-tagline">{{ $slide['tagline'] }}</p>
                            @isset($slide['support'])
                                <p class="cnx-slide-text">{{ $slide['support'] }}</p>
                            @endisset
                            <a href="{{ $slide['link'] }}" class="cnx-slide-button">{{ $slide['button'] }} <i class="ti-arrow-right" aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="cnx-showcase-controls">
            <div class="container cnx-showcase-controls-inner">
                <div class="cnx-showcase-bars">
                    @foreach($heroSlides as $slide)
                        <button type="button" class="cnx-showcase-bar {{ $loop->first ? 'is-active' : '' }}" data-showcase-bar aria-label="Go to slide {{ $loop->iteration }}: {{ $slide['heading'] }}" @if($loop->first) aria-current="true" @endif><span></span></button>
                    @endforeach
                </div>
                <div class="cnx-showcase-buttons">
                    <button type="button" class="cnx-showcase-button" data-showcase-pause aria-label="Pause slideshow"><i class="ti-control-pause" aria-hidden="true"></i></button>
                    <button type="button" class="cnx-showcase-button" data-showcase-prev aria-label="Previous slide"><i class="ti-arrow-left" aria-hidden="true"></i></button>
                    <button type="button" class="cnx-showcase-button" data-showcase-next aria-label="Next slide"><i class="ti-arrow-right" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
    </section>
    @php
        $howItWorks = [
            [
                'class' => 'cnx-how-card-buyers',
                'icon' => 'ti-shopping-cart',
                'label' => 'For buyers',
                'title' => 'Shop from vendors across Markethub',
                'steps' => [
                    ['Find what you need', 'Browse categories, search for products and services, or visit a vendor’s store.'],
                    ['Add to cart and check out', 'Put items from different vendors in one cart, then check out with your name, phone number and delivery address.'],
                    ['Get your order', 'Each vendor receives your order and contacts you to confirm delivery.'],
                ],
                'button' => 'Browse the marketplace',
                'link' => route('marketplace'),
            ],
            [
                'class' => 'cnx-how-card-vendors',
                'icon' => 'ti-bag',
                'label' => 'For vendors',
                'title' => 'Sell online with your own store',
                'steps' => [
                    ['Create your store', 'Sign up and set up your business. Your products get their own store page on Markethub.'],
                    ['List products and services', 'Add photos, prices and categories so buyers can find what you sell.'],
                    ['Receive orders', 'Buyers order straight from your store, and you manage invoices and receipts from your dashboard.'],
                ],
                'button' => 'Start selling',
                'link' => route('register'),
            ],
        ];
    @endphp
    <section class="cnx-how" id="how-it-works">
        <div class="container">
            <div class="cnx-how-head">
                <span class="cnx-how-eyebrow">How it works</span>
                <h2>Buy or sell on Markethub in three simple steps</h2>
                <p>Whether you’re shopping or running a business, getting started takes minutes.</p>
            </div>

            <div class="cnx-how-grid">
                @foreach($howItWorks as $card)
                    <article class="cnx-how-card {{ $card['class'] }}">
                        <div class="cnx-how-card-head">
                            <span class="cnx-how-icon"><i class="{{ $card['icon'] }}" aria-hidden="true"></i></span>
                            <div>
                                <span class="cnx-how-label">{{ $card['label'] }}</span>
                                <h3>{{ $card['title'] }}</h3>
                            </div>
                        </div>
                        <ol class="cnx-how-steps">
                            @foreach($card['steps'] as [$stepTitle, $stepText])
                                <li>
                                    <span class="cnx-how-num" aria-hidden="true">{{ $loop->iteration }}</span>
                                    <div>
                                        <h4>{{ $stepTitle }}</h4>
                                        <p>{{ $stepText }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                        <a href="{{ $card['link'] }}" class="cnx-how-button">{{ $card['button'] }} <i class="ti-arrow-right" aria-hidden="true"></i></a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @php
        $nairaPrice = fn ($amount) => '₦'.number_format((float) $amount, fmod((float) $amount, 1) ? 2 : 0);
    @endphp
    <section class="cnx-featured" id="featured-products">
        <div class="container">
            <div class="cnx-featured-head">
                <div>
                    <span class="cnx-featured-eyebrow">Marketplace</span>
                    <h2>Featured products</h2>
                    <p>A selection of products from vendors on Markethub.</p>
                </div>
                @if($activeProduct)
                    <a href="{{ route('product-category', $activeProduct->slug) }}" class="cnx-featured-all">View all in {{ $activeProduct->category_name }} <i class="ti-arrow-right" aria-hidden="true"></i></a>
                @else
                    <a href="{{ route('marketplace') }}" class="cnx-featured-all">View all products <i class="ti-arrow-right" aria-hidden="true"></i></a>
                @endif
            </div>

            @if($productCategories->isNotEmpty())
                <nav class="cnx-featured-filters" aria-label="Filter products by category">
                    <a class="{{ $activeProduct ? '' : 'is-active' }}" href="{{ route('homepage', array_filter(['services' => request('services')])) }}#featured-products" @unless($activeProduct) aria-current="true" @endunless>All</a>
                    @foreach($productCategories as $category)
                        @php $isActive = optional($activeProduct)->id === $category->id; @endphp
                        <a class="{{ $isActive ? 'is-active' : '' }}" href="{{ route('homepage', array_filter(['products' => $category->slug, 'services' => request('services')])) }}#featured-products" @if($isActive) aria-current="true" @endif>{{ $category->category_name }}</a>
                    @endforeach
                </nav>
            @endif

            @if($products->isNotEmpty())
                <div class="cnx-product-grid">
                    @foreach($products as $item)
                        @php $photo = optional($item->getItemGalleryImages->first())->attachment; @endphp
                        <a class="cnx-product" href="{{ route('view-item', $item->slug) }}">
                            <div class="cnx-product-media">
                                @if($photo)
                                    <img src="/assets/drive/{{ $photo }}" alt="" loading="lazy">
                                @else
                                    <span class="cnx-product-letter" aria-hidden="true">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
                                @endif
                                @if($item->getCategory)
                                    <span class="cnx-product-category">{{ $item->getCategory->category_name }}</span>
                                @endif
                            </div>
                            <div class="cnx-product-body">
                                <span class="cnx-product-vendor">{{ trim($item->getTenant->company_name ?? '') }}</span>
                                <h3>{{ $item->item_name }}</h3>
                                <div class="cnx-product-foot">
                                    <strong class="cnx-product-price">{{ $nairaPrice($item->selling_price) }}</strong>
                                    <span class="cnx-product-go" aria-hidden="true"><i class="ti-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="cnx-featured-empty">No products in this category yet. <a href="{{ route('marketplace') }}">Browse the marketplace</a></div>
            @endif
        </div>
    </section>

    <section class="cnx-featured cnx-featured-services" id="featured-services">
        <div class="container">
            <div class="cnx-featured-head">
                <div>
                    <span class="cnx-featured-eyebrow">Services</span>
                    <h2>Featured services</h2>
                    <p>Find help from businesses on Markethub, from repairs to photography.</p>
                </div>
                @if($activeSeller)
                    <a href="{{ route('vendor-store', $activeSeller->slug) }}" class="cnx-featured-all">View all from {{ trim($activeSeller->company_name) }} <i class="ti-arrow-right" aria-hidden="true"></i></a>
                @endif
            </div>

            @if($serviceSellers->isNotEmpty())
                <nav class="cnx-featured-filters" aria-label="Filter services by provider">
                    <a class="{{ $activeSeller ? '' : 'is-active' }}" href="{{ route('homepage', array_filter(['products' => request('products')])) }}#featured-services" @unless($activeSeller) aria-current="true" @endunless>All providers</a>
                    @foreach($serviceSellers as $seller)
                        @php $isActive = optional($activeSeller)->id === $seller->id; @endphp
                        <a class="{{ $isActive ? 'is-active' : '' }}" href="{{ route('homepage', array_filter(['products' => request('products'), 'services' => $seller->slug])) }}#featured-services" @if($isActive) aria-current="true" @endif>{{ trim($seller->company_name) }}</a>
                    @endforeach
                </nav>
            @endif

            @if($services->isNotEmpty())
                <div class="cnx-service-grid">
                    @foreach($services as $item)
                        @php $photo = optional($item->getItemGalleryImages->first())->attachment; @endphp
                        <a class="cnx-service" href="{{ route('view-item', $item->slug) }}">
                            <div class="cnx-service-media">
                                @if($photo)
                                    <img src="/assets/drive/{{ $photo }}" alt="" loading="lazy">
                                @else
                                    <span class="cnx-product-letter" aria-hidden="true">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="cnx-service-body">
                                <span class="cnx-product-vendor">{{ trim($item->getTenant->company_name ?? '') }}</span>
                                <h3>{{ $item->item_name }}</h3>
                                <div class="cnx-product-foot">
                                    <span class="cnx-product-price"><small>From</small> {{ $nairaPrice($item->selling_price) }}</span>
                                    <span class="cnx-product-go" aria-hidden="true"><i class="ti-arrow-right"></i></span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="cnx-featured-empty">No services from this provider yet.</div>
            @endif
        </div>
    </section>
    @php
        $testimonials = config('testimonials.items', []);
        // Mock testimonials so the section can be designed locally. They are made up, so they
        // never show on the live site: add real ones to config/testimonials.php for that.
        if (empty($testimonials) && app()->environment('local')) {
            $testimonials = [
                ['quote' => 'Setting up my store took one afternoon. Now customers outside Kano can find my bags and order without me posting everywhere.', 'name' => 'Musa A.', 'role' => 'Vendor, Kano Leather Works', 'rating' => 5],
                ['quote' => 'I bought a power bank and headphones from two different vendors in one checkout. Both sellers called me the same day to arrange delivery.', 'name' => 'Chioma O.', 'role' => 'Buyer, Lagos', 'rating' => 5],
                ['quote' => 'I list my repair services with clear prices, so customers know what to expect before they call. It has made my day much easier.', 'name' => 'Tunde B.', 'role' => 'Vendor, FixIt Gadget Repairs', 'rating' => 4],
            ];
        }
    @endphp
    @if(!empty($testimonials))
        <section class="cnx-reviews" aria-labelledby="cnx-reviews-title">
            <div class="container">
                <div class="cnx-how-head">
                    <span class="cnx-how-eyebrow">Testimonials</span>
                    <h2 id="cnx-reviews-title">What vendors and buyers say</h2>
                    <p>Hear from the people selling and shopping on Markethub.</p>
                </div>

                <div class="cnx-reviews-grid">
                    @foreach($testimonials as $review)
                        <figure class="cnx-review">
                            <div class="cnx-review-top">
                                <span class="cnx-review-mark" aria-hidden="true">“</span>
                                @if(!empty($review['rating']))
                                    @php $stars = max(1, min(5, (int) $review['rating'])); @endphp
                                    <span class="cnx-review-stars" role="img" aria-label="Rated {{ $stars }} out of 5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="{{ $i <= $stars ? 'is-on' : '' }}" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 1.5l2.6 5.4 5.9.8-4.3 4.1 1 5.9L10 14.9l-5.2 2.8 1-5.9L1.5 7.7l5.9-.8z"/></svg>
                                        @endfor
                                    </span>
                                @endif
                            </div>
                            <blockquote>{{ $review['quote'] }}</blockquote>
                            <figcaption>
                                @if(!empty($review['photo']))
                                    <img class="cnx-review-avatar" src="{{ $review['photo'] }}" alt="">
                                @else
                                    @php
                                        $initials = collect(preg_split('/\s+/', trim($review['name'])))->filter()->take(2)->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))->implode('');
                                    @endphp
                                    <span class="cnx-review-avatar" aria-hidden="true">{{ $initials }}</span>
                                @endif
                                <span>
                                    <strong>{{ $review['name'] }}</strong>
                                    <small>{{ $review['role'] }}</small>
                                </span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    <section class="cnx-signup" id="sign-up" aria-labelledby="cnx-signup-title">
        <div class="container">
            <div class="cnx-signup-panel">
                <div class="cnx-signup-copy">
                    <span class="cnx-signup-eyebrow">Join Markethub</span>
                    <h2 id="cnx-signup-title">Ready to buy or sell? Sign up now.</h2>
                    <p>Create your account in minutes. Shop from vendors across Markethub, or open your own store and start receiving orders.</p>
                    <ul class="cnx-signup-points">
                        <li><i class="ti-check" aria-hidden="true"></i> Your own store page</li>
                        <li><i class="ti-check" aria-hidden="true"></i> Products and services in one place</li>
                        <li><i class="ti-check" aria-hidden="true"></i> Invoices and receipts built in</li>
                    </ul>
                </div>
                <div class="cnx-signup-actions">
                    <a href="{{ route('register') }}" class="cnx-signup-button">Sign up now <i class="ti-arrow-right" aria-hidden="true"></i></a>
                    <a href="{{ route('marketplace') }}" class="cnx-signup-link">Or browse the marketplace first</a>
                    <span class="cnx-signup-login">Already have an account? <a href="{{ route('login') }}">Log in</a></span>
                </div>
            </div>
        </div>
    </section>
@endsection
