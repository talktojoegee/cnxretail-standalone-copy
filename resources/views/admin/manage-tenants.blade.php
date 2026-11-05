@extends('layouts.admin-layout')
@section('active-page')
    Manage Tenants
@endsection
@section('title')
    Manage Tenants
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
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
                        <h2 class="text-right "><i class="mdi mdi-account-multiple icon-size float-left text-primary text-primary-shadow"></i><span>6</span></h2>
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
                        <h2 class="text-right"><i class="mdi mdi-account-multiple icon-size float-left text-success text-success-shadow"></i><span>6</span></h2>
                        <p class="mb-0">Last Month</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-widget">
                        <h6 class="mb-2">Tenants</h6>
                        <h2 class="text-right"><i class="icon-size mdi mdi-account-multiple   float-left text-warning text-warning-shadow"></i><span>0</span></h2>
                        <p class="mb-0">This Month</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card ">
                <div class="card-body">
                    <div class="card-widget">
                        <h6 class="mb-2">New Tenants</h6>
                        <h2 class="text-right"><i class="mdi mdi-account-multiple icon-size float-left text-danger text-danger-shadow"></i><span>1</span></h2>
                        <p class="mb-0">This Week</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="wd-15p">Company Name</th>
                                    <th class="wd-15p">Plan</th>
                                    <th class="wd-15p">Start Date</th>
                                    <th class="wd-15p">End Date</th>
                                    <th class="wd-20p">Email</th>
                                    <th class="wd-15p">Phone No.</th>
                                    <th class="wd-25p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $serial = 1; @endphp
                                @foreach($tenants as $tenant)
                                    <tr>
                                        <th scope="row">{{$serial++}}</th>
                                        <td>{{$tenant->company_name ?? '' }}</td>
                                        <td>{{$tenant->getTenantPlan->price_name ?? '-'}}</td>
                                        <td class="text-success">{{date('d M, Y', strtotime($tenant->start_date))}}</td>
                                        <td class="text-danger">{{date('d M, Y', strtotime($tenant->end_date))}}</td>
                                        <td>{{$tenant->email ?? ''}}</td>
                                        <td>{{$tenant->phone_no ?? '-'}}</td>
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
