<div class="cnx-topbar">
    <a href="#" class="mobile-menu-toggle w-icon-hamburger cnx-menu-toggle" aria-label="Menu"></a>
    <a href="{{ route('homepage') }}" class="cnx-logo">
        <img src="/assets/images/logo.png" alt="CNX Retail" width="144" height="45">
    </a>
    <form method="get" action="{{ route('product-search') }}" class="cnx-search" role="search">
        <select name="category" aria-label="Category">
            <option value="">All categories</option>
            @foreach($marketCategories as $cat)
                <option value="{{ $cat->slug }}" @selected(request('category') === $cat->slug)>{{ $cat->category_name }}</option>
            @endforeach
        </select>
        <input type="search" name="keyword" value="{{ request('keyword') }}" placeholder="Search products or sellers" aria-label="Search products or sellers">
        @if(request('vendor'))
            <input type="hidden" name="vendor" value="{{ request('vendor') }}">
        @endif
        <button type="submit" aria-label="Search"><i class="w-icon-search"></i></button>
    </form>
    <a href="{{ route('marketplace.cart') }}" class="cnx-cart-link" aria-label="Cart">
        <i class="w-icon-cart"></i>
        <span class="cnx-cart-count">{{ $cartCount ?? 0 }}</span>
    </a>
</div>
