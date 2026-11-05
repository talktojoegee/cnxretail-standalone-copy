@extends('layouts.master-layout')
@section('active-page')
    Campaign Details
@endsection
@section('title')
    Campaign Details
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
                                    <td><strong>Campaign Name : </strong> {{$campaign->settings->subject_line ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type :</strong> {{ ucfirst($campaign->type) ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>List Name :</strong> {{$campaign->recipients->list_name ?? '' }}</td>
                                </tr>
                                </tbody>
                                <tbody class="col-lg-12 col-xl-6 p-0">
                                <tr>
                                    <td><strong>From :</strong> {{$campaign->settings->from_name ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Reply To :</strong> {{$campaign->settings->reply_to ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Subscribers :</strong> {{ number_format($campaign->recipients->recipient_count) ?? '' }} </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="row profie-img">
                            <div class="col-md-12">
                                <div class="media-heading">
                                    <h5><strong>Preview Text</strong></h5>
                                </div>
                                {!! $campaign->settings->preview_text ?? '' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
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
                            <div class="text-muted fs-14">{{date('d F, Y', strtotime($campaign->create_time))}}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Emails Sent</a>
                            <div class="text-muted fs-14">{{number_format($campaign->emails_sent)}}</div>
                        </div>
                    </div>

                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Allow Conversation</a>
                            <div class="text-muted fs-14">{!! $campaign->settings->use_conversation ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Auto Tweet</a>
                            <div class="text-muted fs-14">{!! $campaign->settings->auto_tweet ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Facebook Comments</a>
                            <div class="text-muted fs-14">{!! $campaign->settings->fb_comments ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Allow Tracking</a>
                            <div class="text-muted fs-14">{!! $campaign->tracking->opens ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                    <div class="media mb-5 mt-0">
                        <div class="media-body">
                            <a href="#" class="text-dark">Delivery Status</a>
                            <div class="text-muted fs-14">{!! $campaign->delivery_status->enabled ? "<label class='label label-success'>Yes</label>" : "<label class='label label-danger'>No</label>" !!}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('extra-scripts')

@endsection
