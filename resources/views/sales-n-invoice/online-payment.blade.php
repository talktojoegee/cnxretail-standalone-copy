@extends('layouts.guest-layout')
@section('title')
    View Invoice | Invoice No: {{$invoice->invoice_no ?? ''}}
@endsection

@section('active-page')
    Invoice No: {{$invoice->invoice_no ?? ''}}
@endsection
@section('current-page-brief')

@endsection

@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="\assets\css\component.css">
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
@endsection
@section('main-content')
    <div class="row">
        <div class="col-md-6 offset-md-3">

            @if($invoice->total == $invoice->paid_amount)
                <div class="alert alert-success background-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    Good news! This invoice has no pending payment.
                </div>
            @endif
            @if(session()->has('success'))
                <div class="alert alert-success background-success">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    {!! session()->get('success') !!}
                </div>
            @endif
            @if(session()->has('error'))
                <div class="alert alert-warning background-warning">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled text-white"></i>
                    </button>
                    {!! session()->get('error') !!}
                </div>
            @endif
        </div>
    </div>
    <form action="{{route('charge-invoice-online')}}" method="post">
        @csrf
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
                                    <td class="font-weight-bold text-right h4 number-font1">{{'₦'. number_format($invoice->getInvoiceItems->sum('total'),2) }}</td>
                                </tr>
                                @if($invoice->paid_amount > 0)
                                    <tr>
                                        <td colspan="4" class="font-weight-bold text-uppercase text-right">Paid</td>
                                        <td class="font-weight-bold text-right h4 number-font1">{{'₦'. number_format($invoice->paid_amount,2) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="font-weight-bold text-uppercase text-right">Balance</td>
                                    <td class="font-weight-bold text-right h4 number-font1">{{'₦'.number_format(($invoice->total) - ($invoice->paid_amount) ,2)}}</td>
                                </tr>
                                <tr class="">
                                    <td colspan="4"></td>
                                    <td>
                                        @if(($invoice->total) - ($invoice->paid_amount) <= 0)
                                            <p class="text-center text-danger">This invoice is fully-paid.</p>
                                        @else
                                            <div class="form-group">
                                                <label for="">Amount<sup class="text-danger">*</sup> </label>
                                                <input type="number" step="0.01" name="amount" id="amount" placeholder="Enter Amount" class="form-control">
                                                @error('amount')<i class="text-danger">{{$message}}</i>@enderror
                                                <input type="hidden" name="invoice" value="{{$invoice->id}}">
                                                <input type="hidden" id="hidden-balance" value="{{$invoice->total - $invoice->paid_amount }}">
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        @if($invoice->posted == 1)
                            <button type="button" class="btn btn-danger mb-1" onclick="generatePDF()"><i class="si si-printer"></i> Print Invoice</button>
                        @endif
                        @if((($invoice->total) - ($invoice->paid_amount) > 0) && ($invoice->posted == 1))
                            <a href="{{route('receive-payment', $invoice->slug)}}"  class="btn btn-warning mb-1" ><i class="si si-paper-plane"></i> Make Payment</a>

                        @endif


                    </div>
                </div>
            </div><!-- COL-END -->
        </div>
    </form>
@endsection

@section('extra-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.8.0/html2pdf.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $('#amount').on('blur', function(e){
                var balance = $('#hidden-balance').val();
                var amount = $(this).val();
                if(parseFloat(amount) > parseFloat(balance)){
                    alert("The amount you entered is more than balance amount.");
                    $(this).val('');
                }
            });
        });
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
