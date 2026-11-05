<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Imprest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImprestController extends Controller
{
    public function __construct()
    {
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
       try{
           $amount = str_replace(",", "", $request->amount);
           if(is_numeric($amount)){
               $imprest = $this->imprest->setNewImprest($request);
               return response()->json([
                   'success' => true,
                   'code' => 200,
                   'message' => "Success Imprest Created",
                   'data' => [
                       'imprest'=>$imprest
                   ]
               ]);

           }else{
               return response()->json([
                   'success' => false,
                   'code' => 500,
                   'message' => "Oops something bad happened, Please try again! ",
                   'data' => ''
               ]);
           }
       }
       catch (\Exception $e){
           return response()->json([
               'success' => false,
               'code' => 500,
               'message' => "Oops something bad happened, Please try again! ",
               'data' => ''
           ]);
       }

    }

    public function getImprests(Request $request){
       try{
           $id = $request->id ?? 0;
           $results =$this->imprest->getTenantImprests(true, $id);
           return response()->json([
            'success' => true,
            'code' => 200,
            'message' => "Success imprests",
            'data' => [
                "imprest"=> $results["imprests"],
                "count"=> $results["count"]
            ],
        ]);
        } catch (\Exception $e) {
           return response()->json([
               'success' => false,
               'code' => 500,
               'message' => "Oops something bad happened, Please try again! ",
               'data' => ''
           ]);
       }
    }

    public function getUsers(){
        try{
            $users = $this->user->getAllTenantUsersByTenantId(Auth::user()->tenant_id);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success users",
                'data' => [
                    'user' => $users
                ]
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }



    public function approveImprest(Request $request){
        $this->validate($request,[
            'id'=>'required',
        ],[
            'id.required'=>'Imprest ID is required',
        ]);
        try{
            $id = $request->id;
            $this->imprest->approveDeclineImprest($id, "approve");
                return response()->json([
                    'success' => true,
                    'code' => 200,
                    'message' => "Approved Successfully",
                    'data' => []
                ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }

    }

    public function declineImprest(Request $request){
        $this->validate($request,[
            'id'=>'required',
        ],[
            'id.required'=>'Imprest ID is required',
        ]);
        try{
            $id = $request->id;
            $this->imprest->approveDeclineImprest($id, "decline");
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Declined Successfully",
                'data' => []
            ]);
        }
        catch (\Exception $e){
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
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
