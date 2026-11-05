<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BillDetail;
use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\PaymentMaster;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillsAndPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->vendor = new Contact();
        //$this->vendor = new Vendor();
        $this->billmaster = new BillMaster();
        $this->billdetail = new BillDetail();
        $this->bank = new Bank();
        $this->paymentmaster = new PaymentMaster();
    }

    public function manageBills(){
        return view('bills-n-payment.manage-bills',[
            'bills'=>$this->billmaster->getTenantBills()
        ]);
    }

    public function showAddNewBillForm(){
        return view('bills-n-payment.add-new-bill',[
            'vendors'=>$this->vendor->getTenantContacts(Auth::user()->tenant_id)
        ]);
    }

    public function saveNewBill(Request $request){
        $this->validate($request,[
            'vendor_type'=>'required',
            'issue_date'=>'required|date',
            'items.*'=>'required'
        ],[
            'vendor_type.required'=>'Select vendor type',
            'issue_date.required'=>'Choose issue date',
            'issue_date.date'=>'Enter a valid date format',
           // 'due_date.required'=>'Choose due date',
            //'due_date.date'=>'Enter a valid date format'
        ]);
        $bill = $this->billmaster->setNewBill($request);
        $this->billdetail->setNewBillItems($request, $bill);
        session()->flash("success", " Bill generation was carried out successfully.");
        return back();
    }

    public function viewBill($slug){
        $bill = $this->billmaster->getBillBySlug($slug);
        if(!empty($bill)){
            return view('bills-n-payment.view-bill',['bill'=>$bill]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function declineBill($slug){
        $bill = $this->billmaster->getBillBySlug($slug);
        if(!empty($bill)){
            $this->billmaster->updateBillStatus($bill->id, 'decline');
            session()->flash("success", "<strong>Success!</strong> Bill declined.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }
    public function approveBill($slug){
        $bill = $this->billmaster->getBillBySlug($slug);
        if(!empty($bill)){
            $this->billmaster->updateBillStatus($bill->id, 'post');
            session()->flash("success", "<strong>Success!</strong> Bill posted.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }


    public function showMakePaymentForm($slug){
        $bill = $this->billmaster->getBillBySlug($slug);
        if(!empty($bill)){
            return view('bills-n-payment.make-payment',['bill'=>$bill, 'banks'=>$this->bank->getAllTenantBanks()]);
        }else{
            session()->flash("error", "Whoops! No record found.");
            return back();
        }
    }

    public function makePayment(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'payment_date'=>'required|date',
            'reference_no'=>'required',
            'bank'=>'required',
            'bill'=>'required',
            'payment_method'=>'required'
        ],[
            'amount.required'=>'Enter amount paid.',
            'payment_date.required'=>'Choose payment date',
            'payment_date.date'=>'Choose a valid date format',
            'reference_no.required'=>'Enter a reference number for this transaction',
            'bank.required'=>'Select bank from the list provided',
            'payment_method.required'=>'Select payment method'
        ]);
        $bill = $this->billmaster->getBillById($request->bill);
        if(!empty($bill)){
            $balance = $bill->bill_amount - $bill->paid_amount;
            if($request->amount > $balance){
                session()->flash("error", "Whoops! The amount you entered is more than balance.");
                return back();
            }else{
                $this->billmaster->updateBillPayment($bill, $request->amount);
                $counter = $this->paymentmaster->getLatestPayment();
                $payment = $this->paymentmaster->createNewPayment($counter, $bill, $request);
                if(!empty($payment)){
                    session()->flash("success", " Your payment request was generated successfully.");
                    return back();
                }else{
                    session()->flash("error", "We could not process this request. Try again later");
                    return back();
                }
            }
        }else{
            session()->flash("error", " Bill does not exist. Try again.");
            return back();
        }
    }

    public function managePayments(){
        return view('bills-n-payment.manage-payments',['payments'=>$this->paymentmaster->getAllTenantPayments()]);
    }

    public function viewPayment($slug){
        $payment = $this->paymentmaster->getPaymentBySlug($slug);
        if(!empty($payment)){
            return view('bills-n-payment.view-payment',['payment'=>$payment]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> No record found.");
            return back();
        }
    }

    public function declinePayment($slug){
        $payment = $this->paymentmaster->getPaymentBySlug($slug);
        if(!empty($payment)){
            $this->paymentmaster->updatePaymentStatus($payment->id, 'decline');
            session()->flash("success", "Payment declined.");
            return back();
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }
    public function approvePayment($slug){
        $payment = $this->paymentmaster->getPaymentBySlug($slug);
        if(!empty($payment)){
            $this->paymentmaster->updatePaymentStatus($payment->id, 'post');
            session()->flash("success", " Payment posted.");
            return back();
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function paymentReport(){
        return view('bills-n-payment.payment-report',[
            'bills'=>$this->billmaster->getTenantBills(),
            'payments'=>$this->paymentmaster->getAllTenantPayments(),
            'from'=>now(),
            'to'=>now()
        ]);
    }

    public function filterPaymentReport(Request $request){
        $this->validate($request,[
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);
        return view('bills-n-payment.payment-report',[
            'bills'=>$this->billmaster->getTenantBillsByDateRange($request),
            'payments'=>$this->paymentmaster->getAllTenantPaymentsByDateRange($request),
            'from'=>$request->from,
            'to'=>$request->to
        ]);
    }
}
