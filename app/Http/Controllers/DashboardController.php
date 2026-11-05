<?php

namespace App\Http\Controllers;

use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\DailyMotivation;
use App\Models\InvoiceMaster;
use App\Models\PaymentMaster;
use App\Models\ReceiptMaster;
use App\Models\Reminder;
use App\Models\TenantNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->receiptmaster = new ReceiptMaster();
        $this->invoicemaster = new InvoiceMaster();
        $this->billmaster = new BillMaster();
        $this->paymentmaster = new PaymentMaster();
        $this->contact = new Contact();
        $this->dailymotivation = new DailyMotivation();
        $this->reminder = new Reminder();
    }

    public function index(){
        $theDate = date("H");
        $period = null;
        if($theDate < 12)
            $period = 1; //morning
        else if($theDate < 18)
            $period = 2; //afternoon
        else
            $period = 3; //evening|late night

        return view('dashboard',[
            'receipts'=>$this->receiptmaster->getAllTenantReceiptsThisYear(),
            'invoices'=>$this->invoicemaster->getTenantInvoices(),
            'bills'=>$this->billmaster->getTenantBills(),
            'payments'=>$this->paymentmaster->getAllTenantPayments(),
            'contacts'=>$this->contact->getTenantContacts(Auth::user()->tenant_id),
            'motivation'=>$this->dailymotivation->getDailyRandomMotivation($period),
            'thisMonth'=>$this->receiptmaster->getAllTenantReceiptsThisMonth(),
            'reminders'=>$this->reminder->getAllTenantReminders()
        ]);
    }

}
