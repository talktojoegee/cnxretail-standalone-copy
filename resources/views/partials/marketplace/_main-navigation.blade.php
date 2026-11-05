<nav class="main-nav">
    <ul class="menu active-underline">
        <li class="{{ request()->routeIs('homepage') ? 'cnx-nav-current' : '' }}">
            <a href="{{ route('homepage') }}">Home</a>
        </li>
        <li class="{{ request()->routeIs('marketplace') ? 'cnx-nav-current' : '' }}">
            <a href="{{ route('marketplace') }}">Marketplace</a>
        </li>
        <li class="{{ request()->routeIs('contact-us') ? 'cnx-nav-current' : '' }}">
            <a href="{{ route('contact-us') }}">Contact us</a>
        </li>
        <li class="{{ request()->routeIs('login') ? 'cnx-nav-current' : '' }}">
            <a href="{{ route('login') }}">Login</a>
        </li>
        <li>
            <a href="{{ route('register') }}">Start Trial</a>
        </li>
    </ul>
</nav>
