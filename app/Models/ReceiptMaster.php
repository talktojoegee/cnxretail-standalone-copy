<?php

namespace App\Models;

use App\Mail\ReceiptMailer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptMaster extends Model
{
    use HasFactory;

    public function getContact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }


    public function getInvoice(){
        return $this->belongsTo(InvoiceMaster::class, 'invoice_id');
    }
    public function getDetailReceipt(){
        return $this->hasMany(ReceiptDetail::class, 'receipt_id');
    }

  /*  public function getReceiptItems(){
        return $this->hasMany(ReceiptDetail::class, 'receipt_id');
    }*/





    public function getReceiptItemTotal(Request $request){
        $sum = 0;
        for ( $i = 0; $i<count($request->item_name); $i++){
            $sum += $request->quantity[$i] * $request->amount[$i];
        }
        return $sum;
    }


    public function getLastReceipt(){
         return ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->first();
    }

    public function createNewReceipt($counter, $invoice, Request $request){
                $receipt = new ReceiptMaster();
                $receipt->receipt_no = $counter;
                $receipt->invoice_id =  $invoice->id;
                $receipt->tenant_id =  $invoice->tenant_id;
                $receipt->payment_type = $request->payment_method;
                $receipt->payment_date = $request->payment_date;
                $receipt->issue_date = $request->payment_date;
                $receipt->ref_no = $request->reference_no;
                $receipt->amount = $request->amount ?? 0;
                $receipt->receipt_type = $request->receipt_type ?? 1;
                //$receipt->tenant_id = Auth::user()->tenant_id;
                $receipt->slug = substr(sha1(time()),29,40);
                $receipt->bank_id = $request->bank ?? '';
                $receipt->issued_by = Auth::user()->id;
                $receipt->contact_id = $invoice->contact_id ?? '';
                $receipt->save();
                return $receipt;
    }
    public function createNewReceiptOnline($counter, $invoice, $amount, $bank){
        $receipt = new ReceiptMaster();
        $receipt->receipt_no = $counter;
        $receipt->invoice_id =  $invoice->id;
        $receipt->tenant_id =  $invoice->tenant_id;
        $receipt->payment_type = 1; //unknown
        $receipt->payment_date = now();
        $receipt->issue_date = now();
        $receipt->ref_no = $invoice->ref_no;
        $receipt->amount = $amount ?? 0;
        $receipt->receipt_type =  1;
        $receipt->slug = substr(sha1(time()),29,40);
        $receipt->bank_id = $bank ?? '';
        $receipt->issued_by = $invoice->issued_by;
        $receipt->contact_id = $invoice->contact_id ?? '';
        $receipt->save();
        return $receipt;
    }

    public function createNewDirectReceipt( Request $request){
        $receipt = new ReceiptMaster();
        $receipt->receipt_no = $this->getLatestReceipt() ?? 10000;
        $receipt->invoice_id = null;
        $receipt->tenant_id =  Auth::user()->tenant_id;
        $receipt->payment_type = $request->payment_method;
        $receipt->payment_date = $request->payment_date;
        $receipt->issue_date = $request->payment_date;
        $receipt->ref_no = $request->reference_no;
        $receipt->amount = $this->getReceiptItemTotal($request) ?? 0;
        $receipt->receipt_type = $request->receipt_type ?? 1;
        $receipt->tenant_id = Auth::user()->tenant_id;
        $receipt->slug = substr(sha1(time()),29,40);
        $receipt->bank_id = $request->bank ?? '';
        $receipt->issued_by = Auth::user()->id;
        $receipt->contact_id = $request->contact ?? '';
        $receipt->save();
        return $receipt;
    }

    public function generateTransactionRef(){
        return substr(sha1(time()),32,40);
    }

    public function getLatestReceipt(){
        $receipt =  ReceiptMaster::orderBy('id', 'DESC')->first();
        $receipt_no = null;
        if(!empty($receipt)){
            $receipt_no = $receipt->receipt_no;
            return $receipt_no + 1;
        }else{
            $receipt_no = 100000;
            return $receipt_no;
        }
    }

    public function getAllTenantReceipts(){
        return ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
    }
    public function getAllTenantReceiptsThisYear(){
        return ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)
            ->whereYear('created_at', date('Y'))
            ->orderBy('id', 'DESC')->get();
    }
    public function getAllTenantReceiptsThisMonth(){
        return ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->orderBy('id', 'DESC')->get();
    }

    public function getAllTenantReceiptsByDateRange(Request $request){
        return ReceiptMaster::where('tenant_id', Auth::user()->tenant_id)
            ->whereBetween('issue_date', [$request->from, $request->to])->get();
    }

    public function getReceiptBySlug($slug){
        return ReceiptMaster::where('slug', $slug)->first();
    }

    public function updateReceiptStatus($receipt_id, $status){
        $receipt = ReceiptMaster::find($receipt_id);
        if($status == 'post'){
            $receipt->posted = 1;
            $receipt->posted_by = Auth::user()->id;
            $receipt->date_posted = now();
            $receipt->save();
        }else{
            $receipt->trash = 1;
            $receipt->trashed_by = Auth::user()->id;
            $receipt->date_trashed = now();
            $receipt->save();
        }

    }

    public function sendReceiptAsEmailService($receipt, $contact){
        try{
            \Mail::to($contact)->send(new ReceiptMailer($receipt, $contact));
        }catch (\Exception $exception){

        }

    }

}

