<!doctype html>
<html lang="en" dir="ltr">
<head>

    <!-- META DATA -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Yoha –  HTML5 Bootstrap Admin Template">
    <meta name="author" content="Spruko Technologies Private Limited">
    <meta name="keywords" content="admin dashboard html template, admin dashboard template bootstrap 4, analytics dashboard templates, best admin template bootstrap 4, best bootstrap admin template, bootstrap 4 template admin, bootstrap admin template premium, bootstrap admin ui, bootstrap basic admin template, cool admin template, dark admin dashboard, dark admin template, dark dashboard template, dashboard template bootstrap 4, ecommerce dashboard template, html5 admin template, light bootstrap dashboard, sales dashboard template, simple dashboard bootstrap 4, template bootstrap 4 admin">
    <link rel="shortcut icon" type="image/x-icon" href="http://akmaltechnology.com/template/yoha/assets/images/brand/favicon.ico" />
    <title>{{config('app.name')}} – Register</title>
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/css/style.css" rel="stylesheet"/>
    <link href="/assets/css/skin-modes.css" rel="stylesheet"/>
    <link href="/assets/css/dark-style.css" rel="stylesheet"/>
    <link href="/assets/plugins/single-page/css/main.css" rel="stylesheet" type="text/css">
    <link href="/assets/plugins/scroll-bar/jquery.mCustomScrollbar.css" rel="stylesheet"/>
    <link href="/assets/css/icons.css" rel="stylesheet"/>
    <link id="theme" rel="stylesheet" type="text/css" media="all" href="/assets/colors/color1.css" />
</head>

<body class="app sidebar-mini">
<div class="login-img">

    <!-- PAGE -->
    <div class="page">
        <div class="">
            <!-- CONTAINER OPEN -->
            <div class="col col-login mx-auto">
                <div class="text-center">
                    @if(session()->has('success'))
                    <div class="alert alert-success mb-4">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Congratulations!</strong>
                        <hr class="message-inner-separator">
                        <p>{!! session()->get('success') !!}</p>
                    </div>
                    @endif
                    <a href="{{route('homepage')}}">
                        <img src="/assets/images/brand/main.png" class="header-brand-img" alt="logo">
                    </a>
                </div>
            </div>
            <div class="container-login100">

                <div class="wrap-login100 p-6">
                    <form class="login100-form validate-form" action="{{route('register')}}" method="post" autocomplete="off">
                        @csrf
								<span class="login100-form-title">
									Registration
								</span>
                        <p class="text-center">Start a <strong>30-days</strong> FREE trial. Quickly setup an account for your business/company.</p>
                        <div class="wrap-input100 validate-input" data-validate = "Enter company name">
                            <input class="input100" type="text" name="company_name" value="{{old('company_name')}}" placeholder="Company Name">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><circle cx="12" cy="8" opacity=".3" r="2.1"/><path d="M12 14.9c-2.97 0-6.1 1.46-6.1 2.1v1.1h12.2V17c0-.64-3.13-2.1-6.1-2.1z" opacity=".3"/><path d="M12 13c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm6.1 5.1H5.9V17c0-.64 3.13-2.1 6.1-2.1s6.1 1.46 6.1 2.1v1.1zM12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6.1c1.16 0 2.1.94 2.1 2.1 0 1.16-.94 2.1-2.1 2.1S9.9 9.16 9.9 8c0-1.16.94-2.1 2.1-2.1z"/></svg>
                            </span>
                        </div>
                        @error('company_name')<div><i class="text-danger">{{$message}}</i></div>@enderror
                        <div class="wrap-input100 validate-input" data-validate = "Enter first name">
                            <input class="input100" type="text" name="first_name" value="{{old('first_name')}}" placeholder="First Name">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><circle cx="12" cy="8" opacity=".3" r="2.1"/><path d="M12 14.9c-2.97 0-6.1 1.46-6.1 2.1v1.1h12.2V17c0-.64-3.13-2.1-6.1-2.1z" opacity=".3"/><path d="M12 13c-2.67 0-8 1.34-8 4v3h16v-3c0-2.66-5.33-4-8-4zm6.1 5.1H5.9V17c0-.64 3.13-2.1 6.1-2.1s6.1 1.46 6.1 2.1v1.1zM12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0-6.1c1.16 0 2.1.94 2.1 2.1 0 1.16-.94 2.1-2.1 2.1S9.9 9.16 9.9 8c0-1.16.94-2.1 2.1-2.1z"/></svg>
                            </span>
                        </div>
                        @error('first_name')<div><i class="text-danger">{{$message}}</i></div>@enderror
                        <div class="wrap-input100 validate-input" data-validate = "Enter a valid email address">
                            <input class="input100" type="text" name="email" placeholder="Email Address" value="{{old('email')}}">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M20 8l-8 5-8-5v10h16zm0-2H4l8 4.99z" opacity=".3"/><path d="M4 20h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2zM20 6l-8 4.99L4 6h16zM4 8l8 5 8-5v10H4V8z"/></svg>
									</span>
                        </div>
                        @error('email')<div><i class="text-danger">{{$message}}</i></div>@enderror
                        <div class="wrap-input100 validate-input" data-validate = "Password is required">
                            <input class="input100" type="password" name="password" placeholder="Choose Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><g fill="none"><path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/></g><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
									</span>
                        </div>
                        @error('password')<div><i class="text-danger">{{$message}}</i></div>@enderror
                        <div class="wrap-input100 validate-input" data-validate = "Re-type password">
                            <input class="input100" type="password" name="password_confirmation" placeholder="Re-type Password">
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><g fill="none"><path d="M0 0h24v24H0V0z"/><path d="M0 0h24v24H0V0z" opacity=".87"/></g><path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3"/><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z"/></svg>
                            </span>
                        </div>
                        <label class="custom-control custom-checkbox mt-4">
                            <input type="checkbox" class="custom-control-input" name="terms">
                            <span class="custom-control-label">Agree the <a href="#">terms and policy</a></span>
                        </label>
                        @error('terms')<div><i class="text-danger">{{$message}}</i></div>@enderror
                        <div class="container-login100-form-btn">
                            <button type="submit" class="login100-form-btn btn-primary">
                                Register
                            </button>
                        </div>
                        <div class="text-center pt-3">
                            <p class="text-dark mb-0">Already have account?
                                <a href="{{route('login')}}"  class="text-primary ml-1">Login</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/js/jquery-3.4.1.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/plugins/bootstrap/js/popper.min.js"></script>

<!-- EVA-ICONS JS -->
<script src="/assets/iconfonts/eva.min.js"></script>

<!-- INPUT MASK JS -->
<script src="/assets/plugins/input-mask/jquery.mask.min.js"></script>

<!-- CUSTOM SCROLL BAR JS-->
<script src="/assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="/assets/js/custom.js"></script>

</body>
</html>
