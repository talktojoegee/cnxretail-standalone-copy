@extends('layouts.frontend-layout')
@section('title')
    Contact us
@endsection
@section('extra-styles')

@endsection
@section('meta-keyword')
    Masterly, contact us, data analyst, data management, statistical analysis, behavioural science, modelers, clients, Masterly.uk, Bayelsian analysis, MATLab,
@endsection
@section('meta-description')
    Masterly is home to data analyst. We connect client with modelers in different categories or fields to achieve their set objectives.
@endsection

@section('main-content')
    <div class="page-title inner-page">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12">

                    <h2 class="ipt-title">Contact us</h2>
                    <span class="ipn-subtitle">Couldn't find what you were looking for or you'll just want to talk to us? No issues. Send us a message and you'll hear from us soon.</span>

                </div>
            </div>
        </div>
        <div class="ht-80"></div>
    </div>
    <section class="pt-0">
        <div class="container overlio-top">
            <div class="row">

                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="contact_side overlio-top">

                        <div class="ct_cmp_social">
                            <ul>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>

                        <div class="ct_cmp_caption">
                            <h4 class="mb-0">Get in Touch.</h4>
                            <p>Get in touch via mail, call or direct address.</p>
                        </div>

                        <div class="ct_cmp_address">
                            <div class="ct_cmp_single">
                                <div class="ct_cmp_single_icon"><i class="ti-location-pin"></i></div>
                                <div class="ct_cmp_brief">
                                    <h5>Reach Us:</h5>
                                    <span>{!! config('app.address') !!}</span>
                                </div>
                            </div>
                            <div class="ct_cmp_single">
                                <div class="ct_cmp_single_icon"><i class="fa fa-envelope"></i></div>
                                <div class="ct_cmp_brief">
                                    <h5>Drop a mail:</h5>
                                    <span>{{config('app.email')}}</span>
                                </div>
                            </div>
                            <div class="ct_cmp_single">
                                <div class="ct_cmp_single_icon"><i class="fa fa-phone"></i></div>
                                <div class="ct_cmp_brief">
                                    <h5>Call Us:</h5>
                                    <span>{{config('app.phone')}}</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <form class="contact_row" method="post" action="{{route('contact-us')}}">
                        @csrf
                        <div class="form_row_box">
                            <div class="form_row_header">
                                <div class="form_row_header_flex"><img src="assets/img/email.svg" class="img-fluid" width="52" alt="" /></div>
                                <div class="form_row_header_right">
                                    <p>Write to us in few words about your concerns or interest. Our alert support team will get in touch with you <strong>soonest.</strong></p>
                                </div>
                            </div>
                            @if(session()->get('success'))
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {!! session()->get('success') !!}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="form_row_box_body">
                                <div class="form-row">

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" value="{{old('first_name')}}" name="first_name" class="form-control with-light" placeholder="First Name" />
                                            @error('first_name')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Email Address</label>
                                            <input type="text" class="form-control with-light" placeholder="Email address" value="{{old('email')}}" name="email" />
                                            @error('email')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Subject</label>
                                            <input type="text" name="subject" class="form-control with-light" placeholder="Subject" value="{{old('subject')}}" />
                                            @error('subject')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label>Mobile No.</label>
                                            <input type="text" name="mobile_no" value="{{old('mobile_no')}}" class="form-control with-light" placeholder="Mobile No." />
                                            @error('mobile_no')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Message</label>
                                            <textarea style="resize: none;" placeholder="Type message here..." class="form-control with-light" name="message">{{old('message')}}</textarea>
                                            @error('message')
                                            <i class="text-danger mt-2">{{$message}}</i>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn dark-3">Submit</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('extra-scripts')

@endsection
