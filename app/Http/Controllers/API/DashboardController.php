<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\DailyMotivation;
use App\Models\InvoiceMaster;
use App\Models\PaymentMaster;
use App\Models\ReceiptMaster;
use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->receiptmaster = new ReceiptMaster();
        $this->invoicemaster = new InvoiceMaster();
        $this->billmaster = new BillMaster();
        $this->paymentmaster = new PaymentMaster();
        $this->contact = new Contact();
        $this->dailymotivation = new DailyMotivation();
        $this->reminder = new Reminder();
    }

    public function summary(): \Illuminate\Http\JsonResponse
    {
        try {

            $receipts = $this->receiptmaster->getAllTenantReceiptThisYearApi();
            $invoices = $this->invoicemaster->getTenantInvoices();
            $bills = $this->billmaster->getTenantBills();
            $payments = $this->paymentmaster->getAllTenantPayments();
            $contacts = $this->contact->getTenantContacts(Auth::user()->tenant_id);
            $thisMonth = $this->receiptmaster->getAllTenantReceiptsThisMonth();
            $reminders = $this->reminder->getAllTenantReminders();
            $income = 0;
            $unpaidInvoices = 0;
            $unpaidBills = 0;
            $expenses = 0;
            $thisMonthTotal = 0;
            $recentOrders = array_slice($receipts->toArray(), 0, 10);


            foreach ($thisMonth as $month)
            {
                $thisMonthTotal += $month->amount;
            }
            foreach ($receipts as $receipt)
            {
                $income += $receipt->amount;
            }
            foreach ($invoices as $invoice)
            {
                if($invoice->posted == 1)
                {
                    $unpaidInvoices += (($invoice->total) - ($invoice->paid_amount));
                }
            }
            $totalBills  = 0;
            foreach ($bills as $bill)
            {
                if($bill->posted == 1)
                {
                    $unpaidBills += (($bill->bill_amount) - ($bill->paid_amount));
                    //$totalBills += $bill->total;
                }
            }
            foreach ($payments as $payment)
            {
                if($payment->posted ==  1)
                {
                    $expenses += ($payment->amount);
                }
            }

          /*  for($i=0; $i< 10;  $i++)
            {
                $recentOrders[] = $receipts[$i];
            }*/

            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Dashboard",
                'data' => [
                    /*"receipts" => $receipts,
                    "invoices" => $invoices,
                    "bills" => $bills,
                    "payments" => $payments,
                    "thisMonth" => $thisMonth,
                    "contacts" => $contacts,
                    "reminders" => $reminders,*/
                    "thisMonthTotal"=>$thisMonthTotal,
                    "income" => $income,
                    "unpaidInvoices" => $unpaidInvoices,
                    "unpaidBills" => $unpaidBills,
                    "expenses" => $expenses,
                    "recentOrders"=>$recentOrders
                ],
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
