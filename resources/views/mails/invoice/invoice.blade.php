<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{$invoice->getTenant->company_name.' - Invoice'}}</title>

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
</head>

<body>
<div class="invoice-box" id="receiptWrapper">
    <div class="card-body" id="">
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
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center contain">
            <a href="{{route('online-payment')}}" style="text-decoration: none; padding: 5px; border-radius: 10px; border: 1px solid #fff; color: #fff; background: #FC6E51; width: 200px; height: 84px;"  class="btn btn-primary btn-sm">Make Payment</a>
        </div>
    </div>
</div>
</body>
</html>
