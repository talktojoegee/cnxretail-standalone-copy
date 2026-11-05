<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Category;
use App\Models\Contact;
use App\Models\InvoiceDetail;
use App\Models\InvoiceMaster;
use App\Models\Item;
use App\Models\ItemGallery;
use App\Models\MarginReport;
use App\Models\ReceiptDetail;
use App\Models\ReceiptMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
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
        $this->bank = new Bank();
        $this->marginreport = new MarginReport();
    }

    public function getInvoices(Request $request)
    {
        try {
            $id = $request->id ?? 0;
            $results = $this->invoice->getTenantInvoices(true, (int)$id);
            $totalSumInvoices = $this->invoice->getTotalSumPostedInvoices();
            $totalPaidAmount = $this->invoice->getTotalPaidSumPostedInvoices();
            $totalAllInvoices = $this->invoice->getAllInvoicesTotalSum();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success get invoices",
                'data' => [
                    "totalSumInvoices" => $totalSumInvoices,
                    "totalPaidAmount" => $totalPaidAmount,
                    "totalAllInvoices" => $totalAllInvoices,
                    "invoices" => $results["invoices"],
                    "count" => $results["count"]
                ],
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

    public function getInvoiceDetails($id)
    {
        try {
            $details = $this->invoiceitem->getInvoiceDetails($id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success get invoice details",
                'data' => [
                    "details" => $details,
                ],
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

    public function getBanks(){
        try {
            $banks = $this->bank->getAllTenantBanks();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success banks",
                'data' => [
                    "banks" => $banks,
                ],
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function createInvoice(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'contact_type' => 'required',
            'issue_date' => 'required|date',
            'due_date' => 'required|date',
            'items' => 'required',
            'total' => 'required',
            'contact' => 'required',
        ], [
            'contact_type.required' => 'Select contact type',
            'issue_date.required' => 'Choose issue date',
            'issue_date.date' => 'Enter a valid date format',
            'due_date.required' => 'Choose due date',
            'due_date.date' => 'Enter a valid date format'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $invoice = $this->invoice->createInvoiceAPI($request);
            $this->invoiceitem->setNewInvoiceItemsAPI($request, $invoice);
            $totalSumInvoices = $this->invoice->getTotalSumPostedInvoices();
            $totalPaidAmount = $this->invoice->getTotalPaidSumPostedInvoices();
            $totalAllInvoices = $this->invoice->getAllInvoicesTotalSum();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Invoice Created",
                'data' => [
                    "totalSumInvoices" => $totalSumInvoices,
                    "totalPaidAmount" => $totalPaidAmount,
                    "totalAllInvoices" => $totalAllInvoices,
                    "invoices" => $invoice,
                ],
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

    public function declineInvoice(Request $request)
    {

        //validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $invoice = $this->invoice->getInvoiceById($request->id);
            if (!empty($invoice)) {
                $_invoice = $this->invoice->updateInvoiceStatus($invoice->id, 'decline');
                $totalSumInvoices = $this->invoice->getTotalSumPostedInvoices();
                $totalPaidAmount = $this->invoice->getTotalPaidSumPostedInvoices();
                $totalAllInvoices = $this->invoice->getAllInvoicesTotalSum();
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Declined Successfully",
                    'data' => [
                        "invoice" => $_invoice,
                        "totalSumInvoices" => $totalSumInvoices,
                        "totalPaidAmount" => $totalPaidAmount,
                        "totalAllInvoices" => $totalAllInvoices,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Invoice not found",
                    'data' => ""
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }

    public function approveInvoice(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $invoice = $this->invoice->getInvoiceById($request->id);
            if (!empty($invoice)) {
                $_invoice = $this->invoice->updateInvoiceStatus($invoice->id, 'post');
                $totalSumInvoices = $this->invoice->getTotalSumPostedInvoices();
                $totalPaidAmount = $this->invoice->getTotalPaidSumPostedInvoices();
                $totalAllInvoices = $this->invoice->getAllInvoicesTotalSum();
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Approved Successfully",
                    'data' => [
                        "invoice" => $_invoice,
                        "totalSumInvoices" => $totalSumInvoices,
                        "totalPaidAmount" => $totalPaidAmount,
                        "totalAllInvoices" => $totalAllInvoices,
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Invoice not found",
                    'data' => ""
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }

    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required',
            'payment_date' => 'required|date',
            'reference_no' => 'required',
            'bank' => 'required',
            'invoice' => 'required',
            'payment_method' => 'required'
        ], [
            'amount.required' => 'Enter amount paid.',
            'payment_date.required' => 'Choose payment date',
            'payment_date.date' => 'Choose a valid date format',
            'reference_no.required' => 'Enter a reference number for this transaction',
            'bank.required' => "choose a bank from the list provided",
            'payment_method.required' => 'Select payment method'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }

        try {
            $invoice = $this->invoice->getInvoiceById($request->invoice);
            if (!empty($invoice)) {
                $balance = $invoice->total - $invoice->paid_amount;
                if ($request->amount > $balance) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => "Amount is more than the balance",
                        'data' => ""
                    ]);
                } else {
                    $this->invoice->updateInvoicePayment($invoice, $request->amount);
                    $counter = $this->receipt->getLatestReceipt();
                    $receipt = $this->receipt->createNewReceipt($counter, $invoice, $request);
                    if (!empty($receipt)) {
                        $totalSumInvoices = $this->invoice->getTotalSumPostedInvoices();
                        $totalPaidAmount = $this->invoice->getTotalPaidSumPostedInvoices();
                        $totalAllInvoices = $this->invoice->getAllInvoicesTotalSum();
                        return response()->json([
                            'success' => true,
                            'code' => 200,
                            'message' => "Your receipt request was generated successfully",
                            'data' => [
                                "receipt"=> $receipt,
                                "totalSumInvoices" => $totalSumInvoices,
                                "totalPaidAmount" => $totalPaidAmount,
                                "totalAllInvoices" => $totalAllInvoices,
                            ]
                        ]);

                    } else {

                        return response()->json([
                            'success' => true,
                            'code' => 400,
                            'message' => "Could not process the request",
                            'data' => ""
                        ]);

                    }
                }
            } else {

                return response()->json([
                    'success' => true,
                    'code' => 400,
                    'message' => "Invoice does not exist. Try again.",
                    'data' => ""
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }

    public function sendInvoice(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try{
            $invoice = $this->invoice->getInvoiceById($request->id);
            if(!empty($invoice)){
                #Contact
                $contact = $this->contact->getContactById($invoice->contact_id);
                if(!empty($contact)){
                    //return dd($contact);
                    $this->invoice->sendInvoiceAsEmailService($contact, $invoice);
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => "Success Invoice Sent",
                        'data' => ""
                    ]);
                }else{
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => "Invoice Contact not found",
                        'data' => ""
                    ]);
                }
            }else{
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Invoice Not found",
                    'data' => ""
                ]);
            }
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function getContactInvoice(Request $request){
        $validator = Validator::make($request->all(), [
            'contact_id'=>'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $contact = $request->contact_id;
            $id  = $request->id??0;
            $results = $this->invoice->getContactInvoices($contact,true,$id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success invoices",
                'data' => [
                    "invoices" => $results["invoices"],
                    "count" => $results["count"]
                ],
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
