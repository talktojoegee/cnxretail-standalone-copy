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

class PaymentController extends Controller
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

    public function getPayments(Request $request){
        try{
            $id = $request->id??0;
            $payments = $this->paymentmaster->getAllTenantPayments(true, (int)$id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success All payments",
                'data' => [
                    "payments" => $payments
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

    public function declinePayment(Request $request)
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
            $payment = $this->paymentmaster->getPaymentById($request->id);
            if (!empty($payment)) {
                $_payment = $this->paymentmaster->updatePaymentStatus($payment->id, 'decline');
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Payment updated",
                    'data' => [
                        "payment" => $_payment
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Payment not found",
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
    public function approvePayment(Request $request)
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
            $payment = $this->paymentmaster->getPaymentById($request->id);
            if (!empty($payment)) {
                $_payment = $this->paymentmaster->updatePaymentStatus($payment->id, 'post');
                if($_payment->posted == 1){
                    $this->marginreport->registerReport(2,$payment->amount, $payment->vendor_id);
                }
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Payment updated",
                    'data' => [
                        "payment" => $_payment
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'code' => 400,
                    'message' => "Payment not found",
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
}
