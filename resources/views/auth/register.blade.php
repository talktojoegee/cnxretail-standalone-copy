<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo', [
        'seoTitle' => 'Register',
        'seoDescription' => 'Create your '.config('app.name').' account and open a store on the marketplace.',
    ])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="/assets/assets/css/cnx-auth.css" rel="stylesheet">
</head>
<body class="cnx-auth">

<main class="cnx-auth-shell">
    {{-- Brand panel (desktop) / compact header (phones) --}}
    <aside class="cnx-auth-brand">
        <img class="cnx-auth-brand-photo" src="/assets/assets/img/hero/vendor-laptop.jpg" alt="">
        <div class="cnx-auth-brand-inner">
            <a href="{{ route('homepage') }}" class="cnx-auth-logo">
                <img src="/assets/images/brand/main.png" alt="{{ config('app.name') }} home">
            </a>
            <div class="cnx-auth-brand-copy">
                <span class="cnx-auth-eyebrow">For vendors</span>
                <h1>Open your online store on Markethub</h1>
                <p>Set up your business in minutes, list your products and services, and start receiving orders.</p>
                <ul class="cnx-auth-points">
                    <li><span aria-hidden="true">✓</span> 30-day free trial</li>
                    <li><span aria-hidden="true">✓</span> Your own store page</li>
                    <li><span aria-hidden="true">✓</span> Invoices and receipts built in</li>
                </ul>
            </div>
        </div>
    </aside>

    {{-- Registration form --}}
    <section class="cnx-auth-main">
        <div class="cnx-auth-card">
            <a href="{{ route('homepage') }}" class="cnx-auth-logo cnx-auth-logo-mobile">
                <img src="/assets/images/brand/main.png" alt="{{ config('app.name') }} home">
            </a>

            @if(session()->has('success'))
                <div class="cnx-auth-alert" role="status">
                    <span class="cnx-auth-alert-icon" aria-hidden="true">✓</span>
                    <div>
                        <strong>Congratulations!</strong>
                        <p>{!! session()->get('success') !!}</p>
                    </div>
                </div>
            @endif

            <h2>Create your account</h2>
            <p class="cnx-auth-lead">Start a <strong>30-day free trial</strong>. Quickly set up an account for your business or company.</p>

            <form action="{{ route('register') }}" method="post" autocomplete="off" novalidate>
                @csrf

                <div class="cnx-field @error('company_name') has-error @enderror">
                    <label for="company_name">Company name</label>
                    <div class="cnx-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 21V5a1 1 0 0 1 1-1h9a1 1 0 0 1 1 1v16M15 9h4a1 1 0 0 1 1 1v11M3 21h18M8 8h3M8 12h3M8 16h3"/></svg>
                        <input id="company_name" type="text" name="company_name" value="{{ old('company_name') }}" placeholder="e.g. Adaeze Fashion House" autocomplete="organization" @error('company_name') aria-invalid="true" aria-describedby="company_name-error" @enderror>
                    </div>
                    @error('company_name')<p class="cnx-field-error" id="company_name-error">{{ $message }}</p>@enderror
                </div>

                <div class="cnx-field @error('first_name') has-error @enderror">
                    <label for="first_name">First name</label>
                    <div class="cnx-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 3.6-6 8-6s8 2 8 6"/></svg>
                        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Your first name" autocomplete="given-name" @error('first_name') aria-invalid="true" aria-describedby="first_name-error" @enderror>
                    </div>
                    @error('first_name')<p class="cnx-field-error" id="first_name-error">{{ $message }}</p>@enderror
                </div>

                <div class="cnx-field @error('email') has-error @enderror">
                    <label for="email">Email address</label>
                    <div class="cnx-input">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="you@business.com" autocomplete="email" inputmode="email" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                    </div>
                    @error('email')<p class="cnx-field-error" id="email-error">{{ $message }}</p>@enderror
                </div>

                <div class="cnx-field-row">
                    <div class="cnx-field @error('password') has-error @enderror">
                        <label for="password">Password</label>
                        <div class="cnx-input">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password" type="password" name="password" placeholder="••••••••" autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>
                            <button type="button" class="cnx-input-toggle" data-toggle-password="password" aria-label="Show password">Show</button>
                        </div>
                        @error('password')<p class="cnx-field-error" id="password-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="cnx-field">
                        <label for="password_confirmation">Re-type password</label>
                        <div class="cnx-input">
                            <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                            <input id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" autocomplete="new-password">
                            <button type="button" class="cnx-input-toggle" data-toggle-password="password_confirmation" aria-label="Show password">Show</button>
                        </div>
                    </div>
                </div>

                <div class="cnx-field @error('terms') has-error @enderror">
                    <label class="cnx-check">
                        <input type="checkbox" name="terms" @checked(old('terms')) @error('terms') aria-invalid="true" aria-describedby="terms-error" @enderror>
                        <span class="cnx-check-box" aria-hidden="true"></span>
                        <span>I agree to the <a href="#">terms and policy</a></span>
                    </label>
                    @error('terms')<p class="cnx-field-error" id="terms-error">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="cnx-auth-submit">Create account</button>

                <p class="cnx-auth-switch">Already have an account? <a href="{{ route('login') }}">Log in</a></p>
            </form>
        </div>

        <p class="cnx-auth-footnote">© {{ date('Y') }} {{ config('app.name') }}. <a href="{{ route('homepage') }}">Back to home</a></p>
    </section>
</main>

<script>
    // Show or hide a password field. Display only: it doesn't affect what is submitted.
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
