@php
    $photo = optional($item->getItemGalleryImages->first())->attachment;
@endphp
<article class="cnx-product">
    <a class="photo" href="{{ route('view-item', $item->slug) }}">
        @if($photo)
            <img src="/assets/drive/{{ $photo }}" alt="{{ $item->item_name }}" loading="lazy">
        @else
            <span class="cnx-photo-fallback">{{ strtoupper(substr($item->item_name, 0, 1)) }}</span>
        @endif
    </a>
    <div class="body">
        <a class="meta" href="{{ $item->getCategory ? route('product-category', $item->getCategory->slug) : route('marketplace') }}">
            {{ $item->getCategory->category_name ?? 'Product' }}
        </a>
        <h3><a href="{{ route('view-item', $item->slug) }}">{{ $item->item_name }}</a></h3>
        <div class="meta">{{ $item->getTenant->company_name ?? '' }}</div>
        <div class="cnx-price">₦{{ number_format($item->selling_price, 2) }}</div>
        <form method="post" action="{{ route('marketplace.cart.add') }}">
            @csrf
            <input type="hidden" name="item" value="{{ $item->id }}">
            <input type="hidden" name="qty" value="1">
            <button class="cnx-btn" type="submit" style="width:100%">Add to cart</button>
        </form>
    </div>
</article>
