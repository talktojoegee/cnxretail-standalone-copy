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

class ReceiptController extends Controller
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


    public function getReceipts(Request $request){
        try{
            $id = $request->id??0;
            $results = $this->receipt->getAllTenantReceipts(true, (int)$id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success",
                'data' => [
                    "receipts" => $results["receipts"],
                    "count" => $results["count"]
                ]
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }


    public function getReceiptDetails($id)
    {
        try {
            $details = $this->receiptitem->getReceiptDetails($id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success get Receipt details",
                'data' => [
                    "receiptDetails" => $details,
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

    public function createReceipt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contact' => 'required',
            'payment_date' => 'required|date',
            'reference_no' => 'required',
            'bank' => 'required',
            'total' => 'required',
            'payment_method' => 'required',
            'items.*' => 'required'
        ], [
            'contact.required' => 'Select contact',
            'payment_date.required' => 'Choose payment date',
            'payment_date.date' => 'Choose a valid date format',
            'reference_no.required' => 'Enter a reference number for this transaction',
            'bank.required' => 'choose bank from the list provided',
            'payment_method.required' => 'Select payment method',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $receipt = $this->receipt->createNewReceiptAPI($request);
            $this->receiptitem->setReceiptItemsAPI($request, $receipt);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Receipt created successfully",
                'data' => [
                    "receipt" => $receipt
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

    public function declineReceipt(Request $request)
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
            $receipt = $this->receipt->getReceiptById($request->id);
            if (!empty($receipt)) {
                $_receipt = $this->receipt->updateReceiptStatus($receipt->id, 'decline');
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Receipt updated",
                    'data' => [
                        //"receipt" => $_receipt
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Receipt not found",
                    'data' => ''
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

    public function approveReceipt(Request $request)
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
            $receipt = $this->receipt->getReceiptById($request->id);
            if (!empty($receipt)) {
                $_receipt = $this->receipt->updateReceiptStatus($receipt->id, 'post');
                if($_receipt->posted == 1){
                    $this->marginreport->registerReport(1,$receipt->amount, $receipt);
                }
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Receipt updated",
                    'data' => [
                        //"receipt" => $_receipt
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Receipt not found",
                    'data' => ''
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


    public function sendReceipt(Request $request)
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
            $receipt = $this->receipt->getReceiptById($request->id);
            if (!empty($receipt)) {
                #Contact
                $contact = $this->contact->getContactById($receipt->contact_id);
                if (!empty($contact)) {
                    //return dd($contact);
                    $this->invoice->sendInvoiceAsEmailService($contact, $receipt);
                    return response()->json([
                        'success' => true,
                        'code' => 200,
                        'message' => "Sent Successfully",
                        'data' => ""
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => "Could not send",
                        'data' => ""
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Receipt not found",
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

    public function getContactReceipts(Request $request){
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
        try{
            $id = $request->id??0;
            $contact = $request->contact_id;
            $results = $this->receipt->getContactReceipts($contact,true, (int)$id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Receipts",
                'data' => [
                    "receipts" => $results['receipts'],
                    "count"=> $results['count'],
                ]
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
