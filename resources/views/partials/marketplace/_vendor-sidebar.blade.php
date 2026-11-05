<aside class="cnx-side">
    <h3>Vendors</h3>
    <input type="search" id="vendor-filter" placeholder="Find a vendor" aria-label="Find a vendor">
    <ul class="cnx-vendors">
        <li data-vendor="all vendors">
            <a href="{{ route('marketplace') }}" class="{{ empty($activeVendor) ? 'is-active' : '' }}">
                All vendors <span>{{ $marketVendors->sum('products_count') }}</span>
            </a>
        </li>
        @foreach($marketVendors as $vendor)
            <li data-vendor="{{ strtolower($vendor->company_name) }}">
                <a href="{{ route('product-search', array_filter(['keyword' => request('keyword'), 'category' => request('category'), 'sort' => request('sort'), 'vendor' => $vendor->slug])) }}"
                   class="{{ ($activeVendor ?? '') === $vendor->slug ? 'is-active' : '' }}">
                    {{ $vendor->company_name }}
                    <span>{{ $vendor->products_count }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</aside>
