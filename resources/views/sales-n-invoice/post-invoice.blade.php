@extends('layouts.master-layout')
@section('active-page')
    Post Invoice
@endsection
@section('title')
    Post Invoice
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._invoice-menu')
@endsection

@section('main-content')
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
                                    <th class="sorting">Invoice No.</th>
                                    <th class="sorting">Name</th>
                                    <th class="sorting">Total</th>
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
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{!is_null($invoice->created_at) ? date('d M,Y', strtotime($invoice->created_at)) : '-'}}</td>
                                        <td>{{$invoice->invoice_no ?? ''}}</td>
                                        <td>
                                            {{$invoice->getContact->company_name ?? ''}}
                                        </td>
                                        <td class="text-right">{{number_format($invoice->total,2)}}</td>
                                        <td class="text-right text-success">{{number_format($invoice->paid_amount,2)}}</td>
                                        <td class="text-right text-warning">{{number_format($invoice->total - $invoice->paid_amount,2)}}</td>
                                        <td>
                                            @switch($invoice->status)
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
                                        <td>{{$invoice->ref_no ?? ''}}</td>
                                        <td>
                                            <div class="dropdown-secondary dropdown">
                                                <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item waves-light waves-effect" href="{{route('view-invoice', $invoice->slug)}}"><i class="ti-printer"></i> View Invoice</a>
                                                    @if($invoice->posted == 1 && $invoice->paid_amount < $invoice->total)
                                                        <a class="dropdown-item waves-light waves-effect" href="{{route('receive-payment', $invoice->slug)}}"><i class="ti-receipt"></i> Receive Payment</a>
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
