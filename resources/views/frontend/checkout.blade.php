@extends('layouts.marketplace-layout')
@section('title', 'Checkout')
@section('meta-description', 'Send your '.config('app.name').' order to the sellers and add a delivery address.')
@section('meta-robots', 'noindex, nofollow')
@section('current-page', 'Checkout')

@section('main-content')
    <div class="container cnx-market">
        <h1 style="margin-bottom:16px;">Checkout</h1>
        <div class="cnx-check">
            <form class="cnx-card" method="post" action="{{ route('marketplace.checkout.place') }}" style="padding:20px;">
                @csrf
                <h2>Delivery details</h2>
                <div class="cnx-field">
                    <input id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Full name" aria-label="Full name" required>
                </div>
                <div class="cnx-field">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Email" aria-label="Email" required>
                </div>
                <div class="cnx-field">
                    <input id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Phone" aria-label="Phone" required>
                </div>
                <div class="cnx-field">
                    <input id="address" name="address" value="{{ old('address') }}" placeholder="Delivery address" aria-label="Delivery address" required>
                </div>
                <div class="cnx-field">
                    <textarea id="note" name="note" rows="3" placeholder="Note for the seller" aria-label="Note for the seller">{{ old('note') }}</textarea>
                </div>
                <button class="cnx-btn" type="submit">Place order</button>
            </form>
            <aside class="cnx-summary">
                <h2>Your order</h2>
                @foreach($lines as $line)
                    <p>
                        <span>{{ $line->qty }} × {{ $line->item->item_name }}</span>
                        <span>₦{{ number_format($line->line, 2) }}</span>
                    </p>
                @endforeach
                <hr>
                <p><span>Total</span><strong>₦{{ number_format($total, 2) }}</strong></p>
                <p style="color:#738099;font-size:13px;">Each seller receives the items from their own store and will contact you to confirm.</p>
            </aside>
        </div>
    </div>
@endsection
