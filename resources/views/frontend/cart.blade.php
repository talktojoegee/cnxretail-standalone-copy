@extends('layouts.marketplace-layout')
@section('title', 'Cart')
@section('current-page', 'Cart')

@section('main-content')
    <div class="container cnx-market">
        <h1 style="margin-bottom:16px;">Your cart</h1>
        @if($lines->isEmpty())
            <div class="cnx-card cnx-empty">
                Your cart is empty.
                <div style="margin-top:12px;"><a class="cnx-btn" href="{{ route('marketplace') }}">Browse the marketplace</a></div>
            </div>
        @else
            <div class="cnx-cart">
                <div class="cnx-card">
                    @foreach($lines as $line)
                        @php $photo = optional($line->item->getItemGalleryImages->first())->attachment; @endphp
                        <div class="cnx-line">
                            @if($photo)
                                <img src="/assets/drive/{{ $photo }}" alt="">
                            @else
                                <span class="cnx-photo-fallback">{{ strtoupper(substr($line->item->item_name, 0, 1)) }}</span>
                            @endif
                            <div>
                                <a href="{{ route('view-item', $line->item->slug) }}"><strong>{{ $line->item->item_name }}</strong></a>
                                <div style="color:#738099;font-size:13px;">{{ $line->item->getTenant->company_name ?? '' }}</div>
                                <div class="cnx-price">₦{{ number_format($line->item->selling_price, 2) }}</div>
                                <form method="post" action="{{ route('marketplace.cart.update') }}" style="display:flex;gap:8px;margin-top:8px;">
                                    @csrf
                                    <input type="hidden" name="item" value="{{ $line->item->id }}">
                                    <input type="number" name="qty" value="{{ $line->qty }}" min="1" max="99" style="width:70px;border:1px solid #d7deea;border-radius:8px;padding:6px;">
                                    <button class="cnx-btn-ghost" type="submit">Update</button>
                                </form>
                            </div>
                            <div style="text-align:right;">
                                <strong>₦{{ number_format($line->line, 2) }}</strong>
                                <form method="post" action="{{ route('marketplace.cart.remove') }}">
                                    @csrf
                                    <input type="hidden" name="item" value="{{ $line->item->id }}">
                                    <button class="cnx-btn-ghost" type="submit" style="margin-top:8px;">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <aside class="cnx-summary">
                    <h2>Summary</h2>
                    <p><span>Items</span><span>{{ $lines->sum('qty') }}</span></p>
                    <p><span>Total</span><strong>₦{{ number_format($total, 2) }}</strong></p>
                    <a class="cnx-btn" href="{{ route('marketplace.checkout') }}" style="width:100%;margin-top:8px;">Checkout</a>
                    <a class="cnx-btn-ghost" href="{{ route('marketplace') }}" style="width:100%;margin-top:8px;">Continue shopping</a>
                </aside>
            </div>
        @endif
    </div>
@endsection
