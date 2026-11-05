<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\Imprest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImprestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->imprest = new Imprest();
        $this->bank = new Bank();
        $this->user = new User();
    }

    public function showMyImprest(){
        return view('imprest.my-imprest',[
            'imprests'=>$this->imprest->getMyImprest(Auth::user()->id),
            'banks'=>$this->bank->getAllTenantBanks(),
            'users'=>$this->user->getAllTenantUsersByTenantId(Auth::user()->tenant_id)
        ]);
    }

    public function storeNewImprest(Request $request){
        $this->validate($request,[
           'amount'=>'required',
           'responsible_person'=>'required',
           'bank'=>'required',
           'date'=>'required|date'
        ],[
            'amount.required'=>'Enter amount',
            'responsible_person.required'=>'Select someone for this imprest',
            'bank.required'=>'Select the bank that will be affected as a result of this transaction',
            'date.required'=>'Enter transaction date',
            'date.date'=>'Enter a valid date format'
        ]);
        $amount = str_replace(",", "", $request->amount);
        if(is_numeric($amount)){
            $this->imprest->setNewImprest($request);
            session()->flash("success", "Your imprest was successfully registered.");
            return back();
        }else{
            session()->flash("error", "Enter a valid amount.");
            return back();
        }

    }

    public function manageImprests(){
        return view('imprest.manage-imprests',[
            'imprests'=>$this->imprest->getAllTenantImprests(Auth::user()->tenant_id)
        ]);
    }

    public function processImprest($action, $slug){
        $imprest = $this->imprest->getImprestBySlug($slug);
        if(!empty($imprest)){
            if($action == 'approve'){
                /*$pay = new PayMaster;
                $pay->tenant_id = $imprest->tenant_id;
                $pay->bank_id = $imprest->bank_id;
                $pay->vendor_id = 0; //imprest;
                $pay->exchange_rate = 1;
                //$pay->currency_id = Auth::user()->tenant->currency->id;
                $pay->date_inputed = $imprest->transaction_date;
                $pay->amount = $imprest->amount;
                $pay->ref_no = strtoupper(substr(sha1(time()),34,40));
                $pay->payment_type = 1; //cash
                $pay->user_id = $imprest->user_id;
                $pay->posted = 1; //yes
                $pay->posted_date = now();
                $pay->slug = substr(sha1(time()),34,40);
                $pay->type = 2; //imprest,1=bill
                $pay->save();*/
                #update imprest
                $imprest->status = 1;
                $imprest->save();
                session()->flash("success", "Your imprest was approved.");
                return back();
            }else{
                $imprest->status = 2;//declined
                $imprest->save();
                session()->flash("success", "Your imprest was declined.");
                return back();
            }
        }

    }

    public function impressReport(){
        return view('imprest.impress-report',[
            'impresses'=>$this->imprest->getAllTenantImprests(Auth::user()->tenant_id),
            'from'=>now(),
            'to'=>now()
        ]);
    }

    public function filterImpressReport(Request $request){
        $this->validate($request,[
            'from'=>'required|date',
            'to'=>'required|date'
        ],[
            'from.required'=>'Select start date',
            'from.date'=>'Choose a valid date',
            'to.required'=>'Select end date',
            'to.date'=>'Choose a valid date'
        ]);
        return view('imprest.impress-report',[
            'impresses'=>$this->imprest->getAllTenantImpressesByDateRange($request),
            'from'=>$request->from,
            'to'=>$request->to
        ]);
    }
}
