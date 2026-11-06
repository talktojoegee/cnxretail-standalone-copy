@extends('layouts.marketplace-layout')

@php
    $seoPhoto = optional($item->getItemGalleryImages->first())->attachment;
    $seoPlain = trim(preg_replace('/\s+/', ' ', strip_tags((string) $item->description)));
    $seoKind = (int) $item->item_type === 2 ? 'service' : 'product';
    $seoSeller = $item->getTenant->company_name ?? null;
    $seoDescription = $seoPlain === ''
        ? $item->item_name.' is a '.$seoKind.($seoSeller ? ' from '.$seoSeller : '').' on '.config('app.name').'. ₦'.number_format($item->selling_price, 2).'.'
        : (stripos($seoPlain, $item->item_name) === 0 ? $seoPlain : $item->item_name.'. '.$seoPlain);
@endphp
@section('title', $item->item_name)
@section('meta-description', $seoDescription)
@section('meta-type', 'product')
@if($seoPhoto)
@section('meta-image', '/assets/drive/'.$seoPhoto)
@endif
@section('current-page', $item->item_name)

@section('main-content')
    @php
        $photos = $item->getItemGalleryImages;
        $main = optional($photos->first())->attachment;
    @endphp
    <div class="container cnx-market">
        <div class="cnx-detail">
            <div class="cnx-gallery">
                @if($main)
                    <img id="cnx-main-photo" class="main" src="/assets/drive/{{ $main }}" alt="{{ $item->item_name }}">
                @else
                    <span class="cnx-photo-fallback main">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
                @endif
                @if($photos->count() > 1)
                    <div class="cnx-thumbs">
                        @foreach($photos as $image)
                            <button type="button" class="{{ $loop->first ? 'is-active' : '' }}" data-src="/assets/drive/{{ $image->attachment }}">
                                <img src="/assets/drive/{{ $image->attachment }}" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="cnx-buy">
                <a class="meta" href="{{ $item->getCategory ? route('product-category', $item->getCategory->slug) : route('marketplace') }}" style="color:#738099;text-decoration:none;">
                    {{ $item->getCategory->category_name ?? 'Product' }}
                </a>
                <h1>{{ $item->item_name }}</h1>
                <div class="price">₦{{ number_format($item->selling_price, 2) }}</div>
                <form method="post" action="{{ route('marketplace.cart.add') }}">
                    @csrf
                    <input type="hidden" name="item" value="{{ $item->id }}">
                    <div class="cnx-qty">
                        <button type="button" id="qty-down" aria-label="Decrease quantity">−</button>
                        <input id="qty" type="number" name="qty" value="1" min="1" max="{{ $item->quantity > 0 ? $item->quantity : 99 }}">
                        <button type="button" id="qty-up" aria-label="Increase quantity">+</button>
                    </div>
                    @if($item->quantity > 0)
                        <p style="color:#738099;font-size:13px;">{{ $item->quantity }} in stock</p>
                    @endif
                    <button class="cnx-btn" type="submit">Add to cart</button>
                </form>
                <div class="cnx-seller">
                    <strong>Sold by</strong>
                    @if($item->getTenant)
                        <div><a href="{{ route('vendor-store', $item->getTenant->slug) }}">{{ $item->getTenant->company_name }}</a></div>
                        @if($item->getTenant->phone_no)<div>{{ $item->getTenant->phone_no }}</div>@endif
                        @if($item->getTenant->email)<div>{{ $item->getTenant->email }}</div>@endif
                    @endif
                </div>
            </div>
        </div>
        <article class="cnx-copy">
            <h2>Description</h2>
            <div>{!! $item->description ?: '<p>No description has been added for this product yet.</p>' !!}</div>
        </article>
        @if($related->isNotEmpty())
            <h2 style="margin:28px 0 14px;">More from this category</h2>
            <div class="cnx-grid">
                @foreach($related as $item)
                    @include('partials.marketplace._product-card', ['item' => $item])
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('extra-scripts')
    <script>
        document.querySelectorAll('.cnx-thumbs button').forEach(function (button) {
            button.addEventListener('click', function () {
                var photo = document.getElementById('cnx-main-photo');
                if (photo) photo.src = button.getAttribute('data-src');
                document.querySelectorAll('.cnx-thumbs button').forEach(function (el) { el.classList.remove('is-active'); });
                button.classList.add('is-active');
            });
        });
        var input = document.getElementById('qty');
        var max = parseInt(input.getAttribute('max'), 10) || 99;
        document.getElementById('qty-down').addEventListener('click', function () {
            input.value = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
        });
        document.getElementById('qty-up').addEventListener('click', function () {
            input.value = Math.min(max, (parseInt(input.value, 10) || 1) + 1);
        });
    </script>
@endsection
