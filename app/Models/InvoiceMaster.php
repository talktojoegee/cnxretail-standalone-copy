<?php

namespace App\Models;

use App\Mail\InvoiceMailer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceMaster extends Model
{
    use HasFactory;

    public function getContact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function getInvoiceItems(){
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }

    /*
     * Use-case
     */

    public function setNewInvoice(Request $request){
        $invoice_no = $this->getLatestInvoice();
        $total = $this->getInvoiceItemTotal($request) ?? 0;
        $invoice = new InvoiceMaster();
        $invoice->contact_id = $request->contact ?? '';
        $invoice->tenant_id = Auth::user()->tenant_id ?? '';
        $invoice->issued_by = Auth::user()->id;
        $invoice->invoice_no = $invoice_no;
        $invoice->ref_no = substr(sha1(time()),29,40);
        $invoice->issue_date = $request->issue_date ?? '';
        $invoice->due_date = $request->due_date ?? '';
        $invoice->total = $total;
        $invoice->slug = substr(sha1(time()),25,40);
        $invoice->save();
        return $invoice;

    }

    public function getInvoiceBySlug($slug){
        return InvoiceMaster::where('slug', $slug)->first();
    }

    public function getInvoiceById($id){
        return InvoiceMaster::find($id);
    }

    public function getInvoiceByStatus($status){
        return InvoiceMaster::where('tenant_id', Auth::user()->tenant_id)->where('status', $status)->orderBy('id', 'DESC')->get();
    }

    public function getLatestInvoice(){
        $invoice =  InvoiceMaster::orderBy('id', 'DESC')->first();
        $invoice_no = null;
        if(!empty($invoice)){
            $invoice_no = $invoice->invoice_no;
            return $invoice_no + 1;
        }else{
            $invoice_no = 100000;
            return $invoice_no;
        }
    }

    public function getInvoiceItemTotal(Request $request){
        $sum = 0;
        for ( $i = 0; $i<count($request->item_name); $i++){
            $sum += $request->quantity[$i] * $request->amount[$i];
        }
        return $sum;
    }



    public function getTenantInvoices(){
        return InvoiceMaster::where('tenant_id', Auth::user()->id)->orderBy('id', 'DESC')->get();
    }
    public function getTenantInvoicesByStatus($status){
        return InvoiceMaster::where('tenant_id', Auth::user()->id)->where('status',$status)->orderBy('id', 'DESC')->get();
    }

    public function getTenantInvoicesByDateRange(Request $request){
        return InvoiceMaster::where('trash',0)->where('tenant_id', Auth::user()->tenant_id)
            ->whereBetween('issue_date', [$request->from, $request->to])->get();
    }

    public function updateInvoiceStatus($invoice_id, $status){
        if($status == 'post'){
            $invoice = InvoiceMaster::find($invoice_id);
            $invoice->posted = 1;
            $invoice->posted_by = Auth::user()->id;
            $invoice->post_date = now();
            $invoice->save();
        }else{
            $invoice = InvoiceMaster::find($invoice_id);
            $invoice->trashed = 1;
            $invoice->trashed_by = Auth::user()->id;
            $invoice->trash_date = now();
            #Deduct paid amount
            $invoice->total -= $invoice->paid_amount;
            $invoice->status = 3; //0=pending,1=paid,2=partly-paid, 3=declined
            $invoice->save();

        }

    }

    public function sendInvoiceAsEmailService($contact, $invoice){
        try{

            \Mail::to($contact->company_email)->send(new InvoiceMailer($contact, $invoice));
        }catch (\Exception $exception){

        }

    }

    public function updateInvoicePayment(InvoiceMaster $invoice, $amount){
        $invoice->paid_amount += ($amount) ?? 0;
        $invoice->status = $invoice->paid_amount >= $invoice->total  ? 1 : 2; //0=pending,1=fully-paid,2=partly-paid, 3=declined
        $invoice->save();
    }


}
