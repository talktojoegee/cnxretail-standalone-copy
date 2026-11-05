@extends('layouts.admin-layout')
@section('active-page')
    Admin Dashboard
@endsection

@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-order">
                        <h6 class="mb-2">Tenants</h6>
                        <h2 class="text-right "><i class="mdi mdi-account-multiple icon-size float-left text-primary text-primary-shadow"></i><span>{{number_format($tenants->count())}}</span></h2>
                        <p class="mb-0">All time</p>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card ">
                <div class="card-body">
                    <div class="card-widget">
                        <h6 class="mb-2">Tenants</h6>
                        <h2 class="text-right"><i class="mdi mdi-account-multiple icon-size float-left text-success text-success-shadow"></i><span>{{number_format($tenants->where('account_status',1)->count())}}</span></h2>
                        <p class="mb-0">Active Subscription</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-widget">
                        <h6 class="mb-2">Tenants</h6>
                        <h2 class="text-right"><i class="icon-size mdi mdi-account-multiple   float-left text-warning text-warning-shadow"></i><span>{{number_format($tenants->where('account_status',0)->count())}}</span></h2>
                        <p class="mb-0">Inactive Subscription</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card ">
                <div class="card-body">
                    <div class="card-widget">
                        <h6 class="mb-2">New Tenants</h6>
                        <h2 class="text-right"><i class="mdi mdi-account-multiple icon-size float-left text-danger text-danger-shadow"></i><span>{{number_format($thismonth->count())}}</span></h2>
                        <p class="mb-0">This Month</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Registrations</h3>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Plan</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $serial = 1; @endphp
                        @foreach($tenants->take(10) as $tenant)
                        <tr>
                            <th scope="row">{{$serial++}}</th>
                            <td>{{$tenant->company_name ?? '' }}</td>
                            <td>{{$tenant->getTenantPlan->price_name ?? '-'}}</td>
                            <td class="text-success">{{date('d M, Y', strtotime($tenant->start_date))}}</td>
                            <td class="text-danger">{{date('d M, Y', strtotime($tenant->end_date))}}</td>
                            <td>
                                <a href="{{route('view-tenant', $tenant->slug)}}" class="btn btn-info btn-sm"><i class="ti-eye"></i></a>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
            <div class="card">
                <div class="card-header border-bottom-0">
                    <h3 class="card-title">Upcoming Renewals</h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table  mb-0 text-nowrap">
                            <tbody>
                            @foreach($tenants as $tent)
                                @if(round(abs(strtotime($tent->end_date) - strtotime(now()))/86400) <= 14)
                                <tr>
                                    <td>
                                        <h6 class="mb-0 font-weight-semibold"><a href="{{route('view-tenant', $tent->slug)}}">{{$tent->company_name ?? ''}}</a></h6>
                                        <small class="fs-11 text-muted">{{$tent->getTenantPlan->price_name ?? ''}}</small>
                                    </td>
                                    <td>
                                        <small class="fs-11 text-muted"><small class="ml-1 fs-11 badge badge-secondary">{{round(abs(strtotime($tenant->end_date) - strtotime(now()))/86400) }} days</small> remaining</small>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('extra-scripts')
    <script src="/assets/js/circle-progress.min.js"></script>
    <script src="/assets/plugins/chart/Chart.bundle.js"></script>
    <script src="/assets/plugins/chart/utils.js"></script>
    <script src="/assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script src="/assets/plugins/peitychart/peitychart.init.js"></script>
    <script src="/assets/js/apexcharts.js"></script>
    <script src="/assets/js/index1.js"></script>
    <script src="/assets/js/custom.js"></script>
    <script>
        $(document).ready(function(){
            var now = new Date();
            var hrs = now.getHours();
            var msg = "";
            var src = "";
            if (hrs >  0){
                msg = "Morning";
                src = "/assets/drive/good-morning.jpg";

            } // REALLY early
            if (hrs >  6) {msg = "Good morning"; src = "/assets/drive/good-morning.jpg"; }     // After 6am
            if (hrs >= 12) {msg = "Good afternoon"; src = "/assets/drive/good-afternoon.jpg";}    // After 12pm
            if (hrs >= 16) {msg = "Good evening"; src = "/assets/drive/good-evening.jpg";}      // After 5pm
            if (hrs > 22) {msg = "Well done!"; src = "/assets/drive/late-night.jpg"; }        // After 10pm
            $('#greeting').text(msg);
            $('#greeting-image').attr('src',src);
            console.log(hrs);
        });

    </script>
@endsection
