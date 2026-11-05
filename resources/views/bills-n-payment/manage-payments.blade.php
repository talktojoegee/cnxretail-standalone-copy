@extends('layouts.master-layout')
@section('active-page')
    Manage Payments
@endsection
@section('title')
    Manage Payments
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
        <div class="col-xl-6 col-sm-6">
            <div class="card">
                <div class="card-body text-center">
                    <div class="d-flex mb-4">
                        <span class="brround align-self-center avatar-lg br-3 cover-image bg-orange">
                            <i class="ti-wallet"></i>
                        </span>
                        <div class="svg-icons text-right ml-auto">
                            <p class="text-muted mb-2">Declined</p>
                            <h2 class="mb-0 number-font">{{'₦'.number_format($payments->where('trash',1)->sum('amount'))}}</h2>
                        </div>
                    </div>
                    <div class="progress h-1 mt-0 mb-0">
                        <div class="progress-bar bg-orange w-100" role="progressbar"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-sm-6">
            <div class="card overflow-hidden">
                <div class="card-body text-center">
                    <div class="d-flex mb-4">
                        <span class="brround align-self-center avatar-lg br-3 cover-image bg-success">
                            <i class="ti-wallet text-white"></i>
                        </span>
                        <div class="svg-icons text-right ml-auto">
                            <p class="text-muted mb-2">Posted</p>
                            <h2 class="mb-0 number-font">{{'₦'.number_format($payments->where('posted',1)->sum('amount'))}}</h2>
                        </div>
                    </div>
                    <div class="progress h-1 mt-0 mb-0">
                        <div class="progress-bar bg-success w-100" role="progressbar"></div>
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
                                    <th class="sorting_asc">Payment No.</th>
                                    <th class="sorting">Amount</th>
                                    <th class="sorting_asc">Name</th>
                                    <th class="sorting">Trans. Ref</th>
                                    <th class="sorting">Status</th>
                                    <th class="sorting">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $serial = 1;
                                @endphp
                                @foreach($payments as $payment)
                                    <tr role="row" class="odd">
                                        <td>{{$serial++}}</td>
                                        <td>{{ date('d M, Y', strtotime($payment->payment_date)) }}</td>
                                        <td>{{$payment->payment_no ?? ''}}</td>
                                        <td class="text-right">{{number_format($payment->amount,2)}}</td>
                                        <td>
                                            {{$payment->getVendor->company_name ?? '' }}
                                        </td>
                                        <td>{{strtoupper($payment->ref_no)}}</td>
                                        <td>{!! $payment->posted == 1 ? "<label class='label label-success'>Posted</label>" : '-' !!}
                                            {!! $payment->trashed == 1 ? "<label class='label label-danger'>Trashed</label>" : '-' !!}
                                        </td>
                                        <td>
                                            <div class="dropdown-secondary dropdown">
                                                <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item waves-light waves-effect" href="{{route('view-payment', $payment->slug)}}"><i class="ti-printer"></i> View Payment</a>
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
