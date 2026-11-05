<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\BillDetail;
use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\MarginReport;
use App\Models\PaymentMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BillController extends Controller
{
    public function __construct()
    {
        $this->vendor = new Contact();
        //$this->vendor = new Vendor();
        $this->billmaster = new BillMaster();
        $this->billdetail = new BillDetail();
        $this->bank = new Bank();
        $this->paymentmaster = new PaymentMaster();
        $this->marginreport = new MarginReport();
    }

    public function getBills(Request $request)
    {
        try {
            $id = $request->id ?? 0;
            $results = $this->billmaster->getTenantBills(true, (int)$id);
            $totalSumBills = $this->billmaster->getTotalSumPostedBills();
            $totalPaidAmount = $this->billmaster->getTotalPaidSumPostedBills();
            $totalAllBills = $this->billmaster->getAllBillsTotalSum();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Bills loaded",
                'data' => [
                    "totalSumBills" => $totalSumBills,
                    "totalPaidAmount" => $totalPaidAmount,
                    "totalAllBills" => $totalAllBills,
                    "bills" => $results["bills"],
                    "count" => $results["count"],
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

    public function createBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_type' => 'required',
            'issue_date' => 'required|date',
            'items.*' => 'required'
        ], [
            'vendor_type.required' => 'Select vendor type',
            'issue_date.required' => 'Choose issue date',
            'issue_date.date' => 'Enter a valid date format',
            // 'due_date.required'=>'Choose due date',
            //'due_date.date'=>'Enter a valid date format'
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
            $bill = $this->billmaster->createBillAPI($request);
            $this->billdetail->setNewBillItemsAPI($request, $bill);
            $totalSumBills = $this->billmaster->getTotalSumPostedBills();
            $totalPaidAmount = $this->billmaster->getTotalPaidSumPostedBills();
            $totalAllBills = $this->billmaster->getAllBillsTotalSum();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Billmaster",
                'data' => [
                    "totalSumInvoices" => $totalSumBills,
                    "totalPaidAmount" => $totalPaidAmount,
                    "totalAllInvoices" => $totalAllBills,
                    "bills" => $bill,
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

    public function declineBill(Request $request)
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
            $bill = $this->billmaster->getBillById($request->id);
            if (!empty($bill)) {
                $_bill = $this->billmaster->updateBillStatus($bill->id, 'decline');
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Successfully Declined",
                    'data' => [
                        'bill' => $_bill
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Could not Decline!",
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

    public function approveBill(Request $request)
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
            $bill = $this->billmaster->getBillById($request->id);
            if (!empty($bill)) {
                $_bill = $this->billmaster->updateBillStatus($bill->id, 'post');
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Successfully Declined",
                    'data' => [
                        'bill' => $_bill
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Could not Decline!",
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

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required',
            'payment_date' => 'required|date',
            'reference_no' => 'required',
            'bank' => 'required',
            'bill' => 'required',
            'payment_method' => 'required'
        ], [
            'amount.required' => 'Enter amount paid.',
            'payment_date.required' => 'Choose payment date',
            'payment_date.date' => 'Choose a valid date format',
            'reference_no.required' => 'Enter a reference number for this transaction',
            'bank.required' => 'Choose bank from the list provided',
            'payment_method.required' => 'Select payment method'
        ]);

        try {
            $bill = $this->billmaster->getBillById($request->bill);
            if (!empty($bill)) {
                $balance = $bill->bill_amount - $bill->paid_amount;
                if ($request->amount > $balance) {
                    return response()->json([
                        'success' => false,
                        'code' => 400,
                        'message' => "The amount you entered is more than balance",
                        'data' => []
                    ]);
                } else {
                    $this->billmaster->updateBillPayment($bill, $request->amount);
                    $counter = $this->paymentmaster->getLatestPayment();
                    $payment = $this->paymentmaster->createNewPayment($counter, $bill, $request);
                    if (!empty($payment)) {
                        $totalSumBills = $this->billmaster->getTotalSumPostedBills();
                        $totalPaidAmount = $this->billmaster->getTotalPaidSumPostedBills();
                        $totalAllBills = $this->billmaster->getAllBillsTotalSum();
                        return response()->json([
                            'success' => true,
                            'code' => 200,
                            'message' => "Payment request successful",
                            'data' => [
                                'bill' => $payment,
                                "totalSumBills" => $totalSumBills,
                                "totalPaidAmount" => $totalPaidAmount,
                                "totalAllBills" => $totalAllBills,
                            ]
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'code' => 400,
                            'message' => "Could not process request",
                            'data' => ''
                        ]);
                    }
                }
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Bill not found, could not process request",
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

    public function getBillDetails($id)
    {
        try {
            $details = $this->billdetail->getBillDetails($id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success get bill details",
                'data' => [
                    "billDetails" => $details,
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

}
