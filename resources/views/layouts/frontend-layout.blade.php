@include('partials.frontend._header-style')
<body class="blue-skin">
<div class="Loader"></div>
<div id="main-wrapper">
    @include('partials.frontend._header')
    <div class="clearfix"></div>
    @yield('page-title')
    @yield('main-content')

    @include('partials.frontend._footer-note')
</div>
@include('partials.frontend._footer-scripts')
