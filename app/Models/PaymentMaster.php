<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentMaster extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function getVendor(){
        return $this->belongsTo(Contact::class, 'vendor_id');
    }

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function getBill(){
        return $this->belongsTo(BillMaster::class, 'bill_id');
    }


    public function createNewPayment($counter, $bill, Request $request){
        $payment = new PaymentMaster();
        $payment->payment_no = $counter;
        $payment->bill_id =  $bill->id;
        $payment->tenant_id =  $bill->tenant_id;
        $payment->payment_type = $request->payment_method;
        $payment->payment_date = $request->payment_date;
        $payment->ref_no = $request->reference_no;
        $payment->amount = $request->amount ?? 0;
        $payment->payment_type = $request->payment_type ?? 1;
        //$receipt->tenant_id = Auth::user()->tenant_id;
        $payment->slug = substr(sha1(time()),29,40);
        $payment->bank_id = $request->bank ?? '';
        $payment->issued_by = Auth::user()->id;
        $payment->vendor_id = $bill->vendor_id ?? '';
        $payment->user_id = Auth::user()->id;
        $payment->save();
        return $payment;
    }


    public function getLatestPayment(){
        $payment =  PaymentMaster::orderBy('id', 'DESC')->first();
        $payment_no = null;
        if(!empty($payment)){
            $payment_no = $payment->payment_no;
            return $payment_no + 1;
        }else{
            $payment_no = 100000;
            return $payment_no;
        }
    }

    public function getAllTenantPayments(){
        return PaymentMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
    }

    public function getAllTenantPaymentsByDateRange(Request $request){
        return PaymentMaster::where('tenant_id', Auth::user()->tenant_id)
            ->whereBetween('payment_date', [$request->from, $request->to])->get();
    }

    public function getPaymentBySlug($slug){
        return PaymentMaster::where('slug', $slug)->first();
    }

    public function updatePaymentStatus($payment_id, $status){
        $payment = PaymentMaster::find($payment_id);
        if($status == 'post'){
            $payment->posted = 1;
            $payment->posted_by = Auth::user()->id;
            $payment->date_posted = now();
            $payment->save();
        }else{
            $payment->trash = 1;
            $payment->trashed_by = Auth::user()->id;
            $payment->date_trash = now();
            $payment->save();
        }

    }


}
