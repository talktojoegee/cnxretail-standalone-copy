@foreach($items as $item)
    @include('partials.marketplace._product-card', ['item' => $item])
@endforeach
@if($items->hasMorePages() && request()->boolean('cards'))
    <span class="cnx-next-url" hidden data-next="{{ $items->nextPageUrl() }}"></span>
@endif
