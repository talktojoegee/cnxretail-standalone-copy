@extends('layouts.admin-layout')
@section('active-page')
    Tenant Details
@endsection
@section('title')
    Tenant Details
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection

@section('breadcrumb-action-btn')
    <a href="{{route('manage-tenants')}}" class="btn btn-primary btn-icon text-white mr-2">
            <span>
                <i class="ti-user"></i>
            </span> Manage Tenants
    </a>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">Subscriptions</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>Subscription Log</strong></h5>
                                </div>
                                <div class="table-responsive">
                                    <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                        <thead>
                                        <tr>
                                            <th class="">#</th>
                                            <th class="wd-15p">Company Name</th>
                                            <th class="wd-15p">Plan</th>
                                            <th class="wd-15p">Start Date</th>
                                            <th class="wd-15p">End Date</th>
                                            <th class="wd-20p">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $serial = 1; @endphp
                                        @foreach($subscriptions as $sub)
                                            <tr>
                                                <th scope="row">{{$serial++}}</th>
                                                <td>{{$sub->getTenant->company_name ?? '' }}</td>
                                                <td>{{$sub->getSubscriptionPlan->price_name ?? '-'}}</td>
                                                <td class="text-success">{{date('d M, Y', strtotime($sub->start_date))}}</td>
                                                <td class="text-danger">{{date('d M, Y', strtotime($sub->end_date))}}</td>
                                                <td class="text-right">{{ number_format(ceil($sub->amount/100) - $sub->charge,2) ?? ''}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatable/datatable.js"></script>
    <script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
@endsection
