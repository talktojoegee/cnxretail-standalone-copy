@extends('layouts.master-layout')
@section('active-page')
    Bill
@endsection
@section('title')
    Bill Details
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    @include('bills-n-payment.partials._bill-menu')
@endsection

@section('main-content')
    @if(session()->has('success'))
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body" id="receiptWrapper">
                    <div class="clearfix">
                        <div class="float-left">
                            <h3 class="card-title mb-0">#BILL-{{$bill->bill_no ?? '' }}</h3>
                        </div>
                        <div class="float-right">
                            <p class="mb-1"><span class="font-weight-bold">Bill Date :</span> {{date('d M, Y', strtotime($bill->issue_date))}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 ">
                            <p class="h3">From:</p>
                            <address>
                                {{$bill->getVendor->company_name ?? '' }}<br>
                                {{$bill->getVendor->company_address ?? '' }}<br>
                                {{$bill->getVendor->company_phone ?? '' }}<br>
                                {{$bill->getVendor->company_email ?? '' }}
                            </address>

                        </div>
                        <div class="col-lg-6 text-right">
                            <p class="h3">To:</p>
                            <address>
                                {{$bill->getTenant->company_name ?? '' }}<br>
                                {{$bill->getTenant->address ?? '' }}<br>
                                {{$bill->getTenant->phone_no ?? '' }}<br>
                                {{$bill->getTenant->email ?? '' }}
                            </address>
                        </div>
                    </div>
                    <div class="table-responsive push">
                        <table class="table table-bordered table-hover mb-0 text-nowrap">
                            <tbody><tr class=" ">
                                <th class="text-center"></th>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Sub Total</th>
                            </tr>
                            @php $serial = 1; @endphp
                            @foreach($bill->getBillItems as $item)
                                <tr>
                                    <td class="text-center">{{$serial++}}</td>
                                    <td>
                                        <p class="font-w600 mb-1">{{$item->description ?? '' }}</p>
                                    </td>
                                    <td class="text-center">{{$item->quantity ?? '' }}</td>
                                    <td class="text-right number-font1">{{$item->unit_cost ?? '' }}</td>
                                    <td class="text-right number-font1">{{number_format($item->quantity * $item->unit_cost,2)}}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="font-weight-bold text-uppercase text-right">Total</td>
                                <td class="font-weight-bold text-right h4 number-font1">{{ '₦'.number_format($bill->getBillItems->sum('total'),2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <a href="{{route('manage-bills')}}" class="btn btn-primary mb-1" ><i class="ti-back-left"></i> Back</a>
                    @if($bill->posted == 1)
                        <button type="button" class="btn btn-danger mb-1" onclick="generatePDF()"><i class="si si-printer"></i> Print Bill</button>
                        <a href="route('send-invoice', $bill->slug)}}"  class="btn btn-success mb-1" ><i class="si si-paper-plane"></i> Send Bill</a>
                    @endif
                    @if((($bill->bill_amount) - ($bill->paid_amount) > 0) && ($bill->posted == 1))
                        <a href="{{route('make-payment', $bill->slug)}}"  class="btn btn-warning mb-1" ><i class="si si-paper-plane"></i> Make Payment</a>
                    @endif

                    @if($bill->posted == 0 && $bill->trash == 0)
                        <a href="{{route('decline-bill', $bill->slug)}}" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i> Decline Bill</a>
                        <a href="{{route('approve-bill', $bill->slug)}}" class="btn btn-success btn-mini"><i class="ti-check mr-2"></i> Approve Bill</a>
                    @endif

                </div>
            </div>
        </div><!-- COL-END -->
    </div>
@endsection

@section('extra-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script>
        function generatePDF(){
            var element = document.getElementById('receiptWrapper');
            html2pdf(element,{
                margin:       10,
                filename:     "Bill_No_{{$bill->bill_no}}"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
@endsection
