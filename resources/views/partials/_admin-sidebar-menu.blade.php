
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{route('dashboard')}}">
            <img src="/assets/images/brand/main.png" class="header-brand-img desktop-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img toggle-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img light-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img light-logo1" alt="logo">
        </a><!-- LOGO -->
    </div>
    <ul class="side-menu">
        <li><h3>Main</h3></li>
        <li class="slide">
            <a class="side-menu__item" href="{{route('admin.dashboard')}}">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24" class="side-menu__icon"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3 4h18v10H3z" opacity=".3"/><path d="M21 2H3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h7l-2 3v1h8v-1l-2-3h7c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H3V4h18v10z"/></svg>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{route('admin-notifications')}}">
                <i class="ti-bell mr-2"></i>
                <span class="side-menu__label">Notifications</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="mdi mdi-settings mr-2"></i>
                <span class="side-menu__label">Settings</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{route('manage-pricing')}}" class="slide-item"> Pricing</a></li>
                <li><a href="{{route('daily-motivation')}}" class="slide-item">Daily Motivation</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="ti-briefcase mr-2"></i>
                <span class="side-menu__label">Tenants</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{route('manage-tenants')}}" class="slide-item"> All Tenants</a></li>
                <li><a href="{{route('subscriptions')}}" class="slide-item">Subscriptions</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="ti-hand-point-up mr-2"></i>
                <span class="side-menu__label">Super-admin</span><i class="angle fa fa-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{route('manage-admin-users')}}" class="slide-item"> All Users</a></li>
                <li><a href="{{route('add-new-admin-user')}}" class="slide-item">Add New User</a></li>
            </ul>
        </li>
    </ul>
</aside>
