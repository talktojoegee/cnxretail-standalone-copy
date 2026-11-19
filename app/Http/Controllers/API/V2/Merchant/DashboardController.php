<?php

namespace App\Http\Controllers\API\V2\Merchant;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\BillMaster;
use App\Models\Contact;
use App\Models\InvoiceMaster;
use App\Models\PaymentMaster;
use App\Models\ReceiptMaster;
use App\Models\Reminder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends BaseController
{
    public function __invoke(): JsonResponse
    {
        $tenantId = Auth::guard('api')->user()->tenant_id;
        $receipts = (new ReceiptMaster())->getAllTenantReceiptsThisYear();
        $invoices = (new InvoiceMaster())->getTenantInvoices();
        $bills = (new BillMaster())->getTenantBills();
        $payments = (new PaymentMaster())->getAllTenantPayments();
        $contacts = (new Contact())->getTenantContacts($tenantId);
        $thisMonth = (new ReceiptMaster())->getAllTenantReceiptsThisMonth();
        $reminders = (new Reminder())->getAllTenantReminders();

        $income = 0;
        $unpaidInvoices = 0;
        $unpaidBills = 0;
        $expenses = 0;
        $thisMonthTotal = 0;

        foreach ($thisMonth as $month) {
            $thisMonthTotal += $month->amount;
        }
        foreach ($receipts as $receipt) {
            $income += $receipt->amount;
        }
        foreach ($invoices as $invoice) {
            if ((int) $invoice->posted === 1) {
                $unpaidInvoices += (($invoice->total) - ($invoice->paid_amount));
            }
        }
        foreach ($bills as $bill) {
            if ((int) $bill->posted === 1) {
                $unpaidBills += (($bill->bill_amount) - ($bill->paid_amount));
            }
        }
        foreach ($payments as $payment) {
            if ((int) $payment->posted === 1) {
                $expenses += $payment->amount;
            }
        }

        return $this->ok([
            'income' => (float) $income,
            'expenses' => (float) $expenses,
            'this_month' => (float) $thisMonthTotal,
            'unpaid_invoices' => (float) $unpaidInvoices,
            'unpaid_bills' => (float) $unpaidBills,
            'contacts' => is_countable($contacts) ? count($contacts) : 0,
            'reminders' => is_countable($reminders) ? count($reminders) : 0,
            'recent_receipts' => array_slice($receipts->toArray(), 0, 10),
        ]);
    }
}
