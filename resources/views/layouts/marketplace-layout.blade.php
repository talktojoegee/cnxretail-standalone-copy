@include('partials.marketplace._header')
<body>
<div class="page-wrapper cnx-market">
    <header class="header header-border">
        <div class="header-middle">
            <div class="container" style="display:flex;align-items:center;">
                @include('partials.marketplace._search-bar')
            </div>
        </div>
        <div class="header-bottom sticky-content fix-top sticky-header">
            <div class="container">
                <div class="inner-wrap">
                    <div class="header-left">
                        <div class="dropdown category-dropdown has-border" data-visible="true">
                            <a href="#" class="category-toggle" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" data-display="static" title="Browse Categories">
                                <i class="w-icon-category"></i>
                                <span>Browse Categories</span>
                            </a>
                            <div class="dropdown-box">
                                <ul class="menu vertical-menu category-menu">
                                    @foreach($marketCategories as $cat)
                                        <li>
                                            <a href="{{ route('product-category', $cat->slug) }}">
                                                <i class="w-icon-angle-right"></i>{{ $cat->category_name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @include('partials.marketplace._main-navigation')
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main class="main">
        @include('partials.marketplace._breadcrumb')
        <div class="page-content mb-10">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success cnx-alert">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-warning cnx-alert">{{ session('error') }}</div>
                @endif
                @if(! empty($errors) && $errors->any())
                    <div class="alert alert-danger cnx-alert">{{ $errors->first() }}</div>
                @endif
            </div>
            @yield('main-content')
        </div>
    </main>
</div>
<div class="sticky-footer sticky-content fix-bottom">
    <a href="{{ route('homepage') }}" class="sticky-link"><i class="w-icon-home"></i><p>Home</p></a>
    <a href="{{ route('marketplace') }}" class="sticky-link active"><i class="w-icon-category"></i><p>Shop</p></a>
    <a href="{{ route('marketplace.cart') }}" class="sticky-link"><i class="w-icon-cart"></i><p>Cart ({{ $cartCount ?? 0 }})</p></a>
    <a href="{{ route('login') }}" class="sticky-link"><i class="w-icon-account"></i><p>Account</p></a>
</div>
<a id="scroll-top" class="scroll-top" href="#top" title="Top" role="button"><i class="w-icon-angle-up"></i></a>
@yield('extra-scripts')
<script>
    var vendorFilter = document.getElementById('vendor-filter');
    if (vendorFilter) {
        vendorFilter.addEventListener('input', function () {
            var query = vendorFilter.value.toLowerCase();
            document.querySelectorAll('.cnx-vendors li').forEach(function (row) {
                row.hidden = query !== '' && !(row.getAttribute('data-vendor') || '').includes(query);
            });
        });
    }
</script>
@include('partials.marketplace._footer-script')
