@include('partials._header')

<body class="app sidebar-mini">
<div class="page">
    <div class="page-main">

@include('partials._sidebar-menu')
        @include('partials._app-header')
        <div class="app-content">
            <div class="side-app">
                @include('partials._breadcrumb')
                @yield('main-content')
            </div>
        </div>
    </div>


@include('partials._footer')
</div>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
@include('partials._footer-scripts')
