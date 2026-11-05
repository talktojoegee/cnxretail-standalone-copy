<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Imprest extends Model
{
    use HasFactory;

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function getUser(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getResponsibleOfficer(){
        return $this->belongsTo(User::class, 'responsible_officer');
    }

    public function setNewImprest(Request $request){
        $imprest = new Imprest;
        $imprest->amount = str_replace(",","",$request->amount);
        $imprest->transaction_date = $request->date;
        $imprest->description = $request->description ?? '';
        $imprest->user_id = Auth::user()->id;
        $imprest->tenant_id = Auth::user()->tenant_id;
        $imprest->responsible_officer = $request->responsible_person;
        $imprest->bank_id = $request->bank ?? '';
        $imprest->slug = substr(sha1(time()),27,40);
        $imprest->save();
    }

    public function getMyImprest($user_id){
        return Imprest::where('user_id', $user_id)->orderBy('id', 'DESC')->get();
    }

    public function getImprestBySlug($slug){
        return Imprest::where('slug', $slug)->where('tenant_id', Auth::user()->tenant_id)->first();
    }

    public function getAllTenantImprests($tenant_id){
        return Imprest::where('tenant_id', $tenant_id)->orderBy('id', 'DESC')->get();
    }

    public function getImprestById($id){
        return Imprest::find($id);
    }

    public function getAllTenantImpressesByDateRange(Request $request){
        return Imprest::where('tenant_id', Auth::user()->tenant_id)
            ->whereBetween('transaction_date', [$request->from, $request->to])->get();
    }

}
