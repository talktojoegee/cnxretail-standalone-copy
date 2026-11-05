@extends('layouts.master-layout')
@section('active-page')
    Audience Details
@endsection
@section('title')
    Audience Details
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    @include('campaign.partials._campaign-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div id="profile-log-switch">
                        <div class="table-responsive ">
                            <table class="table row table-borderless">
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                <tr>
                                    <td><strong>List Name : </strong> {{$audience->name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Company Name :</strong> {{ $audience->contact->company ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address 1 :</strong> {{ $audience->contact->address1 ?? '' }}</td>
                                </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                <tr>
                                    <td><strong>Address 2 :</strong> {{ $audience->contact->address2 ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City :</strong> {{ $audience->contact->city ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone :</strong> {{ $audience->contact->phone ?? '' }} </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row profie-img">
                            <div class="col-md-12">
                                <div class="media-heading">
                                    <h5><strong>Permission Reminder</strong></h5>
                                </div>
                                {!! $audience->permission_reminder ?? '' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header border-bottom">
                    <h5 class="card-title">Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="clearfix row mb-4">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Subscribers</strong></h5>
                                <small class="text-muted"># of subscribers</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mb-0 mt-2 text-blue">{{number_format($audience->stats->member_count ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix row mb-4">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Unsubscribed</strong></h5>
                                <small class="text-muted"># of persons who opted-out</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mt-2 mb-0 text-success">{{number_format($audience->stats->unsubscribe_count ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix row mb-4">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Campaigns</strong></h5>
                                <small class="text-muted">The # of campaigns using this audience list</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mt-2 mb-0 text-warning">{{number_format($audience->stats->campaign_count ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix row mb-4">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Average Subscription Rate</strong></h5>
                                <small class="text-muted">Average subscription rate</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mt-2 mb-0 text-danger">{{number_format($audience->stats->avg_sub_rate ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix row mb-4">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Open Rate</strong></h5>
                                <small class="text-muted">As against the total # of receivers. The # that actually opened the mail</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mt-2 mb-0 text-info">{{number_format($audience->stats->open_rate ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix row mb-0">
                        <div class="col">
                            <div class="float-left">
                                <h5 class="mb-0"><strong>Click Rate</strong></h5>
                                <small class="text-muted">The # of those that reacted to the mail</small>
                            </div>
                        </div>
                        <div class="col">
                            <div class="float-right">
                                <h4 class="font-weight-bold mt-2 mb-0 text-info">{{number_format($audience->stats->click_rate ?? 0 )}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="float-left">
                        <h3 class="card-title">Other Details</h3>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="card-body wideget-user-contact">
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Date</a>
                            <div class="text-muted fs-14">{{date('d F, Y', strtotime($audience->date_created))}}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Has Welcome?</a>
                            <div class="text-muted fs-14">{!! $audience->has_welcome ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('extra-scripts')

@endsection
