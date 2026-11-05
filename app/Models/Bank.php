<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Bank extends Model
{
    use HasFactory;


    /*
     * use-case methods
     */

    public function setNewBank(Request $request){
        $bank = new Bank();
        $bank->bank = $request->bank_name;
        $bank->account_name = $request->account_name;
        $bank->account_no = $request->account_no;
        $bank->tenant_id = Auth::user()->tenant_id;
        $bank->save();
    }

    public function updateBank(Request $request){
        $bank = Bank::find($request->bank_id);
        $bank->bank = $request->bank_name;
        $bank->account_name = $request->account_name;
        $bank->account_no = $request->account_no;
        $bank->tenant_id = Auth::user()->tenant_id;
        $bank->save();
    }

    public function getAllTenantBanks(){
        return Bank::where('tenant_id', Auth::user()->tenant_id)->orderBy('bank', 'ASC')->get();
    }

    public function getFirstBankByTenantId($tenant_id){
        return Bank::where('tenant_id', $tenant_id)->first();
    }
}
