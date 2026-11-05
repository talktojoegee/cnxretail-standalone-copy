@extends('layouts.master-layout')
@section('active-page')
    Dashboard
@endsection

@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <!-- ROW-1 -->
    <div class="row">
        <div class="col-md-12">
            <div class="card  banner">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-3 col-lg-2 text-center">
                            <img src="/assets/images/pngs/profit.png" id="greeting-image" alt="img" class="w-95">
                        </div>
                        <div class="col-xl-9 col-lg-10 pl-lg-0">
                            <div class="row">
                                <div class="col-xl-7 col-lg-6">
                                    <div class="text-left text-white mt-xl-4">
                                        <h3 class="font-weight-semibold"><span id="greeting"></span> {{Auth::user()->first_name ?? '' }}</h3>
                                        <q>{{$motivation->motivation ?? ''}}</q>
                                        <blockquote class="mt-3">{{$motivation->author ?? 'Unknown'}}</blockquote>
                                    </div>
                                </div>
                                <div class="col-xl-5 col-lg-6 text-lg-center mt-xl-4">
                                    <h5 class="font-weight-semibold mb-1 text-white">This Month's Sales</h5>
                                    <h2 class="display-2 mb-3 number-font text-white">{{'₦'.number_format($thisMonth->sum('amount'),2)}}</h2>
                                    <div class="btn-list mb-xl-0">
                                        <a href="{{route('manage-receipts')}}" class="btn btn-dark mb-xl-0">Check Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 End-->

    <!-- Row -->
    <div class="row">

        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Revenue <small>(Inflow)</small></p>
                            <h3 class="mb-0 number-font">{{'₦'.number_format($receipts->where('trash', 0)->sum('amount'))}}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary1">
                                <i class="bx bxs-wallet"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">This Year</span></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Expenses <small>(Outflow)</small></p>
                            <h3 class="mb-0 number-font">{{'₦'.number_format($payments->where('posted',1)->sum('amount'))}}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-orange">
                                <i class="bx bxs-shopping-bags"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <span class="text-muted fs-12 ml-0 mt-1">This Year</span></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Unpaid Invoices</p>
                            <h3 class="mb-0 number-font">{{'₦'.number_format(($invoices->where('posted',1)->sum('total')) - ($invoices->where('posted',1)->sum('paid_amount')))}}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary">
                                <i class="bx bxs-badge-dollar"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">  <span class="text-muted fs-12 ml-0 mt-1">This Year</span></span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Unpaid Bills</p>
                            <h3 class="mb-0 number-font">{{'₦'.number_format(($bills->where('posted',1)->sum('bill_amount')) - ($bills->where('posted',1)->sum('paid_amount')))}}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-warning">
                                <i class="bx bxs-credit-card-front"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted"> <strong>1.05%</strong><i class="mdi mdi-arrow-up"></i> <span class="text-muted fs-12 ml-0 mt-1">than last week</span></span>
                </div>
            </div>
        </div>
    </div>
    <!-- Row-1 End -->

    <!-- ROW-4 -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Recent Orders</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-nowrap mb-0">
                            <thead>
                            <tr>
                                <th>Receipt No.</th>
                                <th>Customer</th>
                                <th>Bank</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                                @foreach($receipts->take(10) as $receipt)
                                    <tr>
                                    <td>#{{$receipt->receipt_no ?? '' }}</td>
                                    <td>{{$receipt->getContact->company_name ?? '' }}</td>
                                    <td>{{$receipt->getBank->bank ?? '' }} ({{$receipt->getBank->account_no ?? ''}})</td>
                                    <td class="">{{'₦'.number_format($receipt->amount,2)}}</td>
                                    <td>{{date('d M, Y', strtotime($receipt->created_at))}}</td>
                                    <td>
                                        <a href="{{route('view-receipt', $receipt->slug)}}" class=""><i class="ti-eye"></i></a>
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

@endsection
@section('extra-scripts')
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

        });

    </script>
@endsection
