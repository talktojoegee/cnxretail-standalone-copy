
<div class="page-header">
    <div>
        <h1 class="page-title">@yield('active-page')</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Invoice</a></li>
            <li class="breadcrumb-item active" aria-current="page">@yield('active-page')</li>
        </ol>
    </div>
    <div class="ml-auto pageheader-btn">
        @yield('breadcrumb-action-btn')
    </div>
</div>
