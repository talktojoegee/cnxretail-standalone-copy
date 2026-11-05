@extends('layouts.master-layout')
@section('active-page')
    Manage Bills
@endsection
@section('title')
    Manage Bills
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('bills-n-payment.partials._bill-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xl-4 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-flex mb-4">
                        <span class="brround align-self-center avatar-lg br-3 cover-image bg-orange">
                            <i class="ti-wallet"></i>
                        </span>
                        <div class="svg-icons text-right ml-auto">
                            <p class="text-muted mb-2">Declined</p>
                            <h2 class="mb-0 number-font">{{'₦'.number_format($bills->where('trash',1)->sum('bill_amount'))}}</h2>
                        </div>
                    </div>
                    <div class="progress h-1 mt-0 mb-0">
                        <div class="progress-bar bg-orange w-100" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body text-center">
                    <div class="d-flex mb-4">
                        <span class="brround align-self-center avatar-lg br-3 cover-image bg-success">
                            <i class="ti-wallet text-white"></i>
                        </span>
                        <div class="svg-icons text-right ml-auto">
                            <p class="text-muted mb-2">Posted</p>
                            <h2 class="mb-0 number-font">{{'₦'.number_format($bills->where('posted',1)->sum('bill_amount'))}}</h2>
                        </div>
                    </div>
                    <div class="progress h-1 mt-0 mb-0">
                        <div class="progress-bar bg-success w-100" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body text-center">
                    <div class="d-flex mb-4">
                        <span class="brround align-self-center avatar-lg br-3 cover-image bg-warning">
                            <i class="ti-wallet text-white"></i>
                        </span>
                        <div class="svg-icons text-right ml-auto">
                            <p class="text-muted mb-2">Total</p>
                            <h2 class="mb-0 number-font">{{'₦'.number_format($bills->sum('total'))}}</h2>
                        </div>
                    </div>
                    <div class="progress h-1 mt-0 mb-0">
                        <div class="progress-bar  bg-warning w-100" role="progressbar"></div>
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
                                <tr role="row">
                                    <th class="sorting_asc">S/No.</th>
                                    <th class="sorting_asc">Date</th>
                                    <th class="sorting">Bill No.</th>
                                    <th class="sorting">Name</th>
                                    <th class="sorting">Bill Amount</th>
                                    <th class="sorting">Amount Paid</th>
                                    <th class="sorting">Balance</th>
                                    <th class="sorting">Status</th>
                                    <th class="sorting">Trans. Ref</th>
                                    <th class="sorting">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $serial = 1;
                                @endphp
                                @foreach($bills as $bill)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{!is_null($bill->created_at) ? date('d M,Y', strtotime($bill->created_at)) : '-'}}</td>
                                        <td>{{$bill->bill_no ?? ''}}</td>
                                        <td>
                                            {{$bill->getVendor->company_name ?? ''}}
                                        </td>
                                        <td class="text-right">{{number_format($bill->bill_amount,2)}}</td>
                                        <td class="text-right text-success">{{number_format($bill->paid_amount,2)}}</td>
                                        <td class="text-right text-warning">{{number_format($bill->bill_amount - $bill->paid_amount,2)}}</td>
                                        <td>
                                            @switch($bill->status)
                                                @case(0)
                                                <label for="" class="label label-warning">Pending</label>
                                                @break
                                                @case(1)
                                                <label for="" class="label label-success">Fully-paid</label>
                                                @break
                                                @case(2)
                                                <label for="" class="label label-info">Partly-paid</label>
                                                @break
                                                @case(3)
                                                <label for="" class="label label-danger">Declined</label>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>{{$bill->ref_no ?? ''}}</td>
                                        <td>
                                            <div class="dropdown-secondary dropdown">
                                                <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item waves-light waves-effect" href="{{route('view-bill', $bill->slug)}}"><i class="ti-printer"></i> View Bill</a>
                                                    @if($bill->posted == 1 && $bill->paid_amount < $bill->bill_amount)
                                                        <a class="dropdown-item waves-light waves-effect" href="{{route('make-payment', $bill->slug)}}"><i class="ti-receipt"></i> Make Payment</a>
                                                    @endif
                                                </div>
                                            </div>
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
