<div class="app-header header">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="header-brand d-md-none" href="{{ route('admin.dashboard') }}">
                <img src="/assets/images/brand/main.png" class="header-brand-img mobile-icon" alt="logo">
                <img src="/assets/images/brand/main.png" class="header-brand-img desktop-logo mobile-logo" alt="logo">
            </a>
            <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-toggle="sidebar" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M21 11.01L3 11v2h18zM3 16h12v2H3zM21 6H3v2.01L21 8z"/></svg>
            </a>
            <div class="d-flex ml-auto header-right-icons header-search-icon">
                <button class="navbar-toggler navresponsive-toggler d-md-none" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4"
                        aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" class="navbar-toggler-icon"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                </button>
                @if(Auth::guard('admin')->check())
                    <div class="dropdown profile-1">
                        <a href="#" data-toggle="dropdown" class="nav-link pl-2 pr-2 leading-none d-flex">
                            <span>
                                <img src="/assets/drive/avatar.jpg" alt="profile-user" class="avatar mr-xl-3 profile-user brround cover-image">
                            </span>
                            <div class="text-center mt-1 d-none d-xl-block">
                                <h6 class="text-dark mb-0 fs-13 font-weight-semibold">{{ Auth::guard('admin')->user()->full_name ?? 'Admin' }}</h6>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                            <form method="post" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item" style="border:0;background:transparent;width:100%;text-align:left;">
                                    <i class="dropdown-icon ti-power-off"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
