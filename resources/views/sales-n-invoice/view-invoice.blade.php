@extends('layouts.master-layout')
@section('active-page')
    Invoice
@endsection
@section('title')
    Invoice Details
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._invoice-menu')
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
                            <h3 class="card-title mb-0">#INV-{{$invoice->invoice_no ?? '' }}</h3>
                        </div>
                        <div class="float-right">
                            <p class="mb-1"><span class="font-weight-bold">Invoice Date :</span> {{date('d M, Y', strtotime($invoice->issue_date))}}</p>
                            <p class="mb-0"><span class="font-weight-bold">Due Date :</span> {{date('d M, Y', strtotime($invoice->due_date))}}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6 ">
                            <p class="h3">Invoice From:</p>
                            <address>
                                {{$invoice->getTenant->company_name ?? '' }}<br>
                                {{$invoice->getTenant->address ?? '' }}<br>
                                {{$invoice->getTenant->phone_no ?? '' }}<br>
                                {{$invoice->getTenant->email ?? '' }}
                            </address>
                        </div>
                        <div class="col-lg-6 text-right">
                            <p class="h3">Invoice To:</p>
                            <address>
                                {{$invoice->getContact->company_name ?? '' }}<br>
                                {{$invoice->getContact->company_address ?? '' }}<br>
                                {{$invoice->getContact->company_phone ?? '' }}<br>
                                {{$invoice->getContact->company_email ?? '' }}
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
                            @foreach($invoice->getInvoiceItems as $item)
                             <tr>
                                <td class="text-center">{{$serial++}}</td>
                                <td>
                                    <p class="font-w600 mb-1">{{$item->getService->item_name ?? '' }}</p>
                                </td>
                                <td class="text-center">{{$item->quantity ?? '' }}</td>
                                <td class="text-right number-font1">{{$item->unit_cost ?? '' }}</td>
                                <td class="text-right number-font1">{{number_format($item->quantity * $item->unit_cost,2)}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4" class="font-weight-bold text-uppercase text-right">Total</td>
                                <td class="font-weight-bold text-right h4 number-font1">{{ number_format($invoice->getInvoiceItems->sum('total')) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-right">
                    @if($invoice->posted == 1)
                        <a href="{{route('manage-invoices')}}" class="btn btn-primary mb-1" ><i class="ti-back-left"></i> Back</a>
                        <button type="button" class="btn btn-danger mb-1" onclick="generatePDF()"><i class="si si-printer"></i> Print Invoice</button>
                        <a href="{{route('send-invoice', $invoice->slug)}}"  class="btn btn-success mb-1" ><i class="si si-paper-plane"></i> Send Invoice</a>
                    @endif
                    @if((($invoice->total) - ($invoice->paid_amount) > 0) && ($invoice->posted == 1))
                        <a href="{{route('receive-payment', $invoice->slug)}}"  class="btn btn-warning mb-1" ><i class="si si-paper-plane"></i> Make Payment</a>

                    @endif

                    @if($invoice->posted == 0 && $invoice->trashed == 0)
                        <a href="{{route('decline-invoice', $invoice->slug)}}" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i> Decline Invoice</a>
                        <a href="{{route('approve-invoice', $invoice->slug)}}" class="btn btn-success btn-mini"><i class="ti-check mr-2"></i> Approve Invoice</a>
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
                filename:     "Invoice_No_{{$invoice->invoice_no}}"+".pdf",
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, logging: true, dpi: 192, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
            });
        }
    </script>
@endsection
