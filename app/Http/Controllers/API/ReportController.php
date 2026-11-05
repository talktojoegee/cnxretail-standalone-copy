<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BillDetail;
use App\Models\BillMaster;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Imprest;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\MarginReport;
use App\Models\PaymentMaster;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function __construct()
    {

        $this->category = new Category();
        $this->item = new Item();
        $this->itemgallery = new ItemGallery();
        $this->contact = new Contact();
        $this->invoice = new InvoiceMaster();
        $this->invoiceitem = new InvoiceDetail();
        $this->receipt = new ReceiptMaster();
        $this->receiptitem = new ReceiptDetail();
        $this->vendor = new Contact();
        $this->billmaster = new BillMaster();
        $this->billdetail = new BillDetail();
        $this->paymentmaster = new PaymentMaster();
        $this->bank = new Bank();
        $this->marginreport = new MarginReport();
        $this->imprest = new Imprest();
    }

    public function salesReport(Request $request)
    {
        try{
            $sumInvoices = $this->invoice->getTotalPaidSumPostedInvoices();
            $sumReceipts = $this->receipt->getTotalPaidSumPostedReceipts();
            $id = $request->id??0;
            $receipts = $this->receipt->getAllTenantReceipts(true, $id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Report Received",
                'data' => [
                    "sumInvoices" => $sumInvoices,
                    "sumReceipts" => $sumReceipts,
                    "receipts" => $receipts['receipts']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function filterSalesReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
            $invoices = $this->invoice->getTenantInvoicesByDateRange($request);
            $receipts = $this->receipt->getAllTenantReceiptsByDateRange($request);
            $sumReceipts = 0;
            $sumInvoices = 0;
            foreach ($receipts as $receipt)
            {
                $sumReceipts =  $sumReceipts + $receipt->amount;
            }

            foreach ($invoices as $invoice)
            {
                $sumInvoices =  $sumInvoices + $invoice->paid_amount;
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Sales Report",
                'data' => [
                    "sumInvoices" => $sumInvoices,
                    "sumReceipts" => $sumReceipts,
                    "receipts" => $receipts
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function paymentReport(Request $request)
    {
        try{
            $sumBills = $this->billmaster->getTotalPaidSumPostedBills();
            $sumPayments = $this->paymentmaster->getTotalPaidSumPostedPayments();
            $id = $request->id??0;
            $payments = $this->paymentmaster->getAllTenantPayments(true, $id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Report Received",
                'data' => [
                    "sumBills" => $sumBills,
                    "sumPayments" => $sumPayments,
                    "Payments" => $payments['Payments']
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function filterPaymentReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
            $bills = $this->billmaster->getTenantBillsByDateRange($request);
            $payments = $this->paymentmaster->getAllTenantPaymentsByDateRange($request);
            $sumPayments = 0;
            $sumBills = 0;
            foreach ($payments as $payment)
            {
                $sumPayments =  $sumPayments + $payment->amount;
            }

            foreach ($bills as $bill)
            {
                $sumBills =  $sumBills + $bill->paid_amount;
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Payment Report ",
                'data' => [
                    "sumBills" => $sumBills,
                    "sumPayments" => $sumPayments,
                    "Payments" => $payments
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function imprestsReport(Request $request)
    {
        try{
            $sumImprest = $this->imprest->getPostedImprestTotalSum();
            $id = $request->id??0;
            $imprests = $this->imprest->getTenantImprests(true,$id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Imprest Report Received",
                'data' => [
                    'Imprest'=>$imprests['imprests'],
                    'sumImprest'=>$sumImprest
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function filterImpressReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
            $imprests = $this->imprest->getAllTenantImpressesByDateRange($request);
            $sumImprest = 0;
            foreach ($imprests as $imprest)
            {
                $sumImprest =  $sumImprest + $imprest->amount;
            }

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Imprest Report ",
                'data' => [
                    "sumImprest" => $sumImprest,
                    "Imprest" => $imprests
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
