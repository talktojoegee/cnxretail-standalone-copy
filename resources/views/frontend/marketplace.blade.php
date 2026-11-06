@extends('layouts.marketplace-layout')

@section('title', $heading ?? 'Marketplace')
@section('meta-description')
@if(request()->filled('keyword'))
Products matching “{{ request('keyword') }}” from vendors on {{ config('app.name') }}.
@elseif(request()->routeIs('vendor-store'))
Shop products from {{ $heading }} on the {{ config('app.name') }} marketplace.
@elseif(request()->routeIs('product-category'))
Shop {{ $heading }} from vendors on the {{ config('app.name') }} marketplace.
@else
Browse products from vendors on {{ config('app.name') }}. Compare sellers and check out.
@endif
@endsection
@section('current-page')
    {{ $heading ?? 'Marketplace' }}
@endsection

@section('main-content')
    <div class="container cnx-market">
        @if(isset($slides) && $slides->isNotEmpty())
            <section class="cnx-hero" aria-label="Featured products">
                @foreach($slides as $slide)
                    @php $photo = optional($slide->getItemGalleryImages->first())->attachment; @endphp
                    <article class="cnx-slide {{ $loop->first ? 'is-active' : '' }}">
                        <div class="cnx-slide-copy">
                            <p>{{ $slide->getCategory->category_name ?? 'Featured' }} · {{ $slide->getTenant->company_name ?? 'Marketplace' }}</p>
                            <h2>{{ $slide->item_name }}</h2>
                            <strong>₦{{ number_format($slide->selling_price, 2) }}</strong>
                            <div>
                                <a class="cnx-btn" href="{{ route('view-item', $slide->slug) }}">View product</a>
                            </div>
                        </div>
                        @if($photo)
                            <img src="/assets/drive/{{ $photo }}" alt="{{ $slide->item_name }}">
                        @endif
                    </article>
                @endforeach
                @if($slides->count() > 1)
                    <div class="cnx-hero-nav">
                        <button type="button" id="cnx-prev" aria-label="Previous slide">‹</button>
                        <button type="button" id="cnx-next" aria-label="Next slide">›</button>
                    </div>
                @endif
            </section>
        @endif

        <div class="cnx-shop">
            @include('partials.marketplace._vendor-sidebar')
            <section>
                <div class="cnx-toolbar">
                    <div>
                        <h1>{{ $heading ?? 'Products' }}</h1>
                        <div class="meta" style="color:#738099;font-size:13px;">{{ $items->total() }} listed</div>
                    </div>
                    <form method="get" action="{{ url()->current() }}">
                        @foreach(request()->except('sort', 'page') as $key => $value)
                            @if(is_string($value) && $value !== '')
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" aria-label="Sort products" onchange="this.form.submit()">
                            <option value="newest" @selected(request('sort', 'newest') === 'newest')>Newest</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: low to high</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: high to low</option>
                            <option value="name" @selected(request('sort') === 'name')>Name</option>
                        </select>
                    </form>
                </div>
                @if($items->count())
                    <div class="cnx-grid" id="cnx-grid">
                        @include('partials.marketplace._product-cards')
                    </div>
                    @if($items->hasMorePages())
                        <div id="cnx-more" data-next="{{ $items->nextPageUrl() }}"></div>
                        <p id="cnx-more-status" class="cnx-more-status" aria-live="polite"></p>
                    @endif
                @else
                    <div class="cnx-card cnx-empty">No products match these filters. Try another vendor or clear the search.</div>
                @endif
            </section>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script>
        (function () {
            var slides = document.querySelectorAll('.cnx-slide');
            var index = 0;
            function show(next) {
                if (!slides.length) return;
                slides[index].classList.remove('is-active');
                index = (next + slides.length) % slides.length;
                slides[index].classList.add('is-active');
            }
            var next = document.getElementById('cnx-next');
            var prev = document.getElementById('cnx-prev');
            if (next) next.addEventListener('click', function () { show(index + 1); });
            if (prev) prev.addEventListener('click', function () { show(index - 1); });
            if (slides.length > 1) setInterval(function () { show(index + 1); }, 6000);
        })();

        (function () {
            var grid = document.getElementById('cnx-grid');
            var sentinel = document.getElementById('cnx-more');
            var status = document.getElementById('cnx-more-status');
            if (!grid || !sentinel) return;
            var nextUrl = sentinel.getAttribute('data-next');
            var loading = false;

            function loadMore() {
                if (!nextUrl || loading) return;
                loading = true;
                if (status) status.textContent = 'Loading more products…';
                var url = new URL(nextUrl, window.location.origin);
                url.searchParams.set('cards', '1');
                fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (response) { return response.text(); })
                    .then(function (html) {
                        var wrap = document.createElement('div');
                        wrap.innerHTML = html;
                        var marker = wrap.querySelector('.cnx-next-url');
                        nextUrl = marker ? marker.getAttribute('data-next') : '';
                        if (marker) marker.remove();
                        while (wrap.firstChild) grid.appendChild(wrap.firstChild);
                        if (status) status.textContent = nextUrl ? '' : 'All products are loaded.';
                        loading = false;
                        if (!nextUrl) observer.disconnect();
                    })
                    .catch(function () {
                        loading = false;
                        if (status) status.textContent = 'Could not load more products. Scroll again to retry.';
                    });
            }

            var observer = new IntersectionObserver(function (entries) {
                if (entries.some(function (entry) { return entry.isIntersecting; })) loadMore();
            }, { rootMargin: '480px' });
            observer.observe(sentinel);
        })();
    </script>
@endsection
