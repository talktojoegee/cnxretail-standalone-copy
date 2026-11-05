<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BulkSmsAccount;
use App\Models\InvoiceMaster;
use App\Models\Pricing;
use App\Models\ReceiptMaster;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yabacon\Paystack;

class OnlinePaymentController extends Controller
{


    public function __construct()
    {
        $this->bulksmsaccount = new BulkSmsAccount();
        $this->subscription = new Subscription();
        $this->pricing = new Pricing();
        $this->user = new User();
        $this->tenant = new Tenant();
        $this->invoice = new InvoiceMaster();
        $this->receipt = new ReceiptMaster();
        $this->bank = new Bank();
    }

    public function initializePaystack(){

    }
    public function processOnlinePayment(Request $request){
        /*
         * Transaction Type (Transaction):
         *  1 = New tenant subscription
         *  2 = Subscription Renewal
         *  3 = Invoice Payment
         *  4 = SMS Top-up
         */
        $reference = isset($request->reference) ? $request->reference : '';
        if(!$reference){
            die('No reference supplied');
        }
        $paystack = new Paystack(config('app.paystack_secret_key'));
        try {
            // verify using the library
            $tranx = $paystack->transaction->verify([
                'reference'=>$reference, // unique to transactions
            ]);
        }catch (Paystack\Exception\ApiException $exception){
            session()->flash("error", "Whoops! Something went wrong.");
            return redirect()->route('top-up');
        }
        if ('success' === $tranx->data->status) {
            try {
                $transaction_type = $tranx->data->metadata->transaction ;
                switch ($transaction_type){
                    case 2:
                        $transaction_type = $tranx->data->metadata->transaction ;
                        $active_key = "key_".substr(sha1(time()),23,40);
                        $tenant_id = $tranx->data->metadata->tenant ;
                        $plan_id = $tranx->data->metadata->pricing ;
                        $charge = $tranx->data->metadata->charge ;
                        $plan = $this->pricing->getPricingByPricingId($plan_id);
                        $now = Carbon::now();
                        $end = $now->addDays($plan->duration);
                        $this->subscription->renewSubscription($tenant_id, $plan_id, $active_key, now(),
                            $end->toDateTimeString(), 3, $tranx->data->amount, $charge);
                        $this->user->updateTenantActiveKey($tenant_id, $active_key, now(), $end->toDateTimeString());
                        $this->tenant->updateTenantSubscriptionPeriod($tenant_id, $active_key, now(), $end->toDateTimeString(), $plan_id);
                        break;
                    case 3:
                        $invioce_id = $tranx->data->metadata->invoice;
                        $bank_id = $tranx->data->metadata->bank;
                        $amount = $tranx->data->amount;
                        $invoice = $this->invoice->getInvoiceById($invioce_id);
                        $this->invoice->updateInvoicePayment($invoice, $amount);
                        $counter = $this->receipt->getLatestReceipt();
                        $receipt = $this->receipt->createNewReceiptOnline($counter, $invoice, $amount, $bank_id);
                        break;
                    case 4:
                        $this->bulksmsaccount->creditAccount($request->reference,$tranx->data->amount,$tranx->data->metadata->cost);
                        break;
                }
                // transaction was successful...
                // please check other things like whether you already gave value for this ref
                // if the email matches the customer who owns the product etc
                // Give value
                //return dd($tranx->data->amount);
                //return dd($tranx->data->metadata->cost);
                switch ($transaction_type){
                    case 2:
                        session()->flash("success", "Your subscription was renewed successfully.");
                        return redirect()->route('login');
                        break;
                    case 3:
                        session()->flash("success", "Your payment was received. Thank you.");
                        return redirect()->route('login');
                        break;
                    case 4:
                        session()->flash("success", "Your top-up transaction was successful.");
                        return redirect()->route('top-up');
                        break;
                }
            }catch (Paystack\Exception\ApiException $ex){

            }

        }
    }

    /*
    * process online payment
    */
    public function onlinePayment($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            $settings = $this->tenant->getTenantPaymentGatewaySettings($invoice->tenant_id);
            //if(!empty($settings->secret_key) && !empty($settings->public_key)){
            if(!empty($settings->secret_key) && !empty($settings->public_key)){
                //$paystack = new Paystack($settings->secret_key);
                //$paystack = new Paystack(config('app.paystack_secret_key'));
                #Public key
                //$this->setEnv('PAYSTACK_PUBLIC_KEY', $company_payment_int->ps_public_key);
                #Secret key
                //$this->setEnv('PAYSTACK_SECRET_KEY', $company_payment_int->ps_secret_key);
                return view('sales-n-invoice.online-payment',['invoice'=>$invoice]);
            }else{
                session()->flash("error", "<h3 class='text-center'>Whoops! Kindly contact Admin. Something went wrong.</h3> ");
                return back();
            }

        }else{
            abort(404, 'Resource not found.');
        }
    }

    /*public function onlinePayment($slug){
        $invoice = $this->invoice->getInvoiceBySlug($slug);
        if(!empty($invoice)){
            $settings = $this->tenant->getTenantPaymentGatewaySettings();
            //if(!empty($settings->secret_key) && !empty($settings->public_key)){
            if(!empty($settings->secret_key) && !empty($settings->public_key)){
                $paystack = new Paystack($settings->secret_key);
                //$paystack = new Paystack(config('app.paystack_secret_key'));
                #Public key
                //$this->setEnv('PAYSTACK_PUBLIC_KEY', $company_payment_int->ps_public_key);
                #Secret key
                //$this->setEnv('PAYSTACK_SECRET_KEY', $company_payment_int->ps_secret_key);
                return view('sales-n-invoice.online-payment',['invoice'=>$invoice]);
            }else{
                session()->flash("error", "<h3 class='text-center'>Whoops! Kindly contact Admin. Something went wrong.</h3> ");
                return back();
            }

        }else{
            abort(404, 'Resource not found.');
        }
    }*/


    /*
     * Charge invoice online
     */
    public function chargeInvoiceOnline(Request $request){
        $this->validate($request,[
            'amount'=>'required',
            'invoice'=>'required'
        ],[
            'units.required'=>"Enter the amount you wish to pay"
        ]);

        $invoice = $this->invoice->getInvoiceById($request->invoice);
        if(!empty($invoice)){
            $bank = $this->bank->getFirstBankByTenantId($invoice->tenant_id);
            if(empty($bank)){
                abort(404, 'Something went wrong');
            }
            $settings = $this->tenant->getTenantPaymentGatewaySettings($invoice->tenant_id);
            if(!empty($settings->secret_key) && !empty($settings->public_key)){
            try{
                $paystack = new Paystack(config($settings->secret_key));
                $amount = $request->amount;
                $builder = new Paystack\MetadataBuilder();
                $builder->withInvoice($request->invoice);
                $builder->withBank($bank->id);
                /*
                 * Transaction Type:
                 *  1 = New tenant subscription
                 *  2 = Subscription Renewal
                 *  3 = Invoice Payment
                 *  4 = SMS Top-up
                 */
                $builder->withTransaction(3);
                $metadata = $builder->build();
                //$charge = ceil($cost*1.5)/100;
                $tranx = $paystack->transaction->initialize([
                    'amount'=>$amount*100,       // in kobo
                    'email'=>$invoice->getContact->email,         // unique to customers
                    'reference'=>substr(sha1(time()),23,40), // unique to transactions
                    'metadata'=>$metadata
                ]);
                return redirect()->to($tranx->data->authorization_url)->send();
            }catch (Paystack\Exception\ApiException $exception){
                //print_r($exception->getResponseObject());
                //die($exception->getMessage());
                session()->flash("error", "Whoops! Something went wrong. Try again.");
                return back();
            }
            }else{
                abort(404, 'Something went wrong. Contact seller.');
            }
        }else{
            abort(404, 'Resource not found.');
        }

    }
}
