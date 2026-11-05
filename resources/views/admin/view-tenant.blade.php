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
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user">
                        <div class="card">
                            <div class="card-header bg-primary br-tr-3 br-tl-3">
                                <h3 class="card-title text-white">Tenant Info</h3>
                                <div class="card-options ">
                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="">Company Name:</label>
                                    <input type="text" readonly value="{{$tenant->company_name ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Email:</label>
                                    <input type="text" readonly value="{{$tenant->email ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone No.:</label>
                                    <input type="text" readonly value="{{$tenant->phone_no ?? '-' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Company Address:</label>
                                    <input type="text" readonly value="{{$tenant->address ?? '-' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Description:</label>
                                    <p>{{$tenant->description ?? '-' }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Start Date:</label>
                                    <p class="text-success">{{ date('d M, Y', strtotime($tenant->start_date))  }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">End Date:</label>
                                    <p class="text-danger">{{ date('d M, Y', strtotime($tenant->end_date))  }}</p>
                                </div>
                                <div class="form-group">
                                    <label for="">Account Status:</label>
                                    <p class="text-danger">{!! $tenant->account_status == 1 ? "<span class='text-success'>Active</span>" : "<span class='text-danger'>Inactive</span>" !!}</p>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if(!empty($tenant->website))
                                    <a href="http://{{$tenant->website}}" target="_blank">Visit website</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">Subscription</a></li>
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
                                            <th class="wd-15p">Plan</th>
                                            <th class="wd-15p">Start Date</th>
                                            <th class="wd-15p">End Date</th>
                                            <th class="wd-20p">Amount</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $serial = 1; @endphp
                                        @foreach($tenant->getTenantSubscriptions as $sub)
                                            <tr>
                                                <th scope="row">{{$serial++}}</th>
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
