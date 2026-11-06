<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Log in to your {{ config('app.name') }} account.">
    <title>{{ config('app.name') }} – Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/assets/assets/css/cnx-auth.css" rel="stylesheet">
</head>
<body class="cnx-auth">

<main class="cnx-auth-shell">
    {{-- Brand panel (desktop) / compact header (tablets) --}}
    <aside class="cnx-auth-brand">
        <img class="cnx-auth-brand-photo" src="/assets/assets/img/hero/vendor-market-stall.jpg" alt="" style="object-position: 62% 40%;">
        <div class="cnx-auth-brand-inner">
            <a href="{{ route('homepage') }}" class="cnx-auth-logo">
                <img src="/assets/images/brand/main.png" alt="{{ config('app.name') }} home">
            </a>
            <div class="cnx-auth-brand-copy">
                <span class="cnx-auth-eyebrow">Welcome back</span>
                <h1>Your business, all in one place</h1>
                <p>Log in to manage your store, products and services, invoices and receipts.</p>
                <ul class="cnx-auth-points">
                    <li><span aria-hidden="true">✓</span> Your store on Markethub</li>
                    <li><span aria-hidden="true">✓</span> Contacts and customers</li>
                    <li><span aria-hidden="true">✓</span> Invoices and receipts</li>
                </ul>
            </div>
        </div>
    </aside>

    {{-- Login form --}}
    <section class="cnx-auth-main">
        <div class="cnx-auth-card">
            <a href="{{ route('homepage') }}" class="cnx-auth-logo cnx-auth-logo-mobile">
                <img src="/assets/images/brand/main.png" alt="{{ config('app.name') }} home">
            </a>

            @if(session()->has('error'))
                <div class="cnx-auth-alert cnx-auth-alert-error" role="alert">
                    <span class="cnx-auth-alert-icon" aria-hidden="true">!</span>
                    <div>
                        <strong>Whoops!</strong>
                        <p>{!! session()->get('error') !!}</p>
                    </div>
                </div>
            @endif
            @if(session()->has('success'))
                <div class="cnx-auth-alert" role="status">
                    <span class="cnx-auth-alert-icon" aria-hidden="true">✓</span>
                    <div>
                        <strong>Great!</strong>
                        <p>{!! session()->get('success') !!}</p>
                    </div>
                </div>
            @endif

            <h2>Log in</h2>
            <p class="cnx-auth-lead">Welcome back. Enter your details to continue to your dashboard.</p>

            <form action="{{ route('login') }}" method="post" novalidate>
                @csrf

                <div class="cnx-field @error('email') has-error @enderror">
                    <label for="email">Email address</label>
                    <div class="cnx-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@business.com" autocomplete="email" inputmode="email" autofocus @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </div>
                    @error('email')<p class="cnx-field-error" id="email-error">{{ $message }}</p>@enderror
                </div>

                <div class="cnx-field @error('password') has-error @enderror">
                    <div class="cnx-label-row">
                        <label for="password">Password</label>
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    </div>
                    <div class="cnx-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                        <input id="password" type="password" name="password" placeholder="••••••••" autocomplete="current-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                        <button type="button" class="cnx-input-toggle" data-toggle-password="password" aria-label="Show password">Show</button>
                    </div>
                    @error('password')<p class="cnx-field-error" id="password-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="cnx-auth-submit">Log in</button>

                <p class="cnx-auth-switch">Don’t have an account? <a href="{{ route('register') }}">Register now</a></p>
            </form>
        </div>

        <p class="cnx-auth-footnote">© {{ date('Y') }} {{ config('app.name') }}. <a href="{{ route('homepage') }}">Back to home</a></p>
    </section>
</main>

<script>
    // Show or hide the password. Display only: it doesn't affect what is submitted.
    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            var input = document.getElementById(button.getAttribute('data-toggle-password'));
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            button.textContent = show ? 'Hide' : 'Show';
            button.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    });
</script>
</body>
</html>
