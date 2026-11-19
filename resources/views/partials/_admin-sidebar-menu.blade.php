
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
    <div class="side-header">
        <a class="header-brand1" href="{{ route('admin.dashboard') }}">
            <img src="/assets/images/brand/main.png" class="header-brand-img desktop-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img toggle-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img light-logo" alt="logo">
            <img src="/assets/images/brand/main.png" class="header-brand-img light-logo1" alt="logo">
        </a>
    </div>
    <ul class="side-menu">
        <li><h3>Overview</h3></li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.dashboard') }}">
                <i class="ti-dashboard mr-2"></i>
                <span class="side-menu__label">Dashboard</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin-notifications') }}">
                <i class="ti-bell mr-2"></i>
                <span class="side-menu__label">Notifications</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.activity-logs') }}">
                <i class="ti-time mr-2"></i>
                <span class="side-menu__label">Activity Log</span>
            </a>
        </li>

        <li><h3>Customers</h3></li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="ti-briefcase mr-2"></i>
                <span class="side-menu__label">Tenants</span><i class="angle ti-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('manage-tenants') }}" class="slide-item">All Tenants</a></li>
                <li><a href="{{ route('subscriptions') }}" class="slide-item">Subscriptions</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.buyer-requests') }}">
                <i class="ti-shopping-cart mr-2"></i>
                <span class="side-menu__label">Buyer Requests</span>
            </a>
        </li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('admin.contact-inquiries') }}">
                <i class="ti-email mr-2"></i>
                <span class="side-menu__label">Contact Inquiries</span>
            </a>
        </li>

        <li><h3>Platform</h3></li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="ti-settings mr-2"></i>
                <span class="side-menu__label">Settings</span><i class="angle ti-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('manage-pricing') }}" class="slide-item">Pricing</a></li>
                <li><a href="{{ route('daily-motivation') }}" class="slide-item">Daily Motivation</a></li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="ti-lock mr-2"></i>
                <span class="side-menu__label">Administrators</span><i class="angle ti-angle-right"></i>
            </a>
            <ul class="slide-menu">
                <li><a href="{{ route('manage-admin-users') }}" class="slide-item">All Users</a></li>
                <li><a href="{{ route('add-new-admin-user') }}" class="slide-item">Add New User</a></li>
            </ul>
        </li>
    </ul>
</aside>
