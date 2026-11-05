@unless(request()->routeIs('homepage'))
<nav class="breadcrumb-nav">
    <div class="container">
        <ul class="breadcrumb bb-no">
            <li><a href="{{route('homepage')}}">Home</a></li>
            <li><a href="javascript:void(0);">@yield('current-page')</a></li>
        </ul>
    </div>
</nav>
@endunless
