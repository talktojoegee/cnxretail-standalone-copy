<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Tenant extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Item::class, 'tenant_id');
    }

    public function getTenantPlan(){
        return $this->belongsTo(Pricing::class, 'plan_id');
    }

    public function getTenantSubscriptions(){
        return $this->hasMany(Subscription::class, 'tenant_id')->orderBy('id', 'DESC');
    }

    /*
     * Use-case methods
     */

    public function setNewTenant(Request $request){
        $active_key = "key_".substr(sha1(time()),23,40);
        $tenant = new Tenant();
        $tenant->company_name = $request->company_name;
        $tenant->email = $request->email;
        $tenant->slug = Str::slug($request->company_name).'-'.substr(sha1(time()),33,40);
        $tenant->start_date = now();
        $tenant->end_date = Carbon::now()->addDays(30);
        $tenant->active_sub_key = $active_key;
        $tenant->save();
        return $tenant;
    }

    public function updateTenantDetails(Request $request){
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $tenant->company_name = $request->company_name;
        $tenant->phone_no = $request->phone_no ?? Auth::user()->getTenant->phone_no;
        $tenant->description = $request->description ?? Auth::user()->getTenant->description;
        $tenant->tagline = $request->tagline ?? Auth::user()->getTenant->tagline;
        $tenant->address = $request->address ?? Auth::user()->getTenant->address;
        $tenant->website = $request->website ?? Auth::user()->getTenant->website;
        $tenant->save();
    }
    public function updateTenantPaymentIntegration(Request $request){
        $tenant = Tenant::find(Auth::user()->tenant_id);
        $tenant->secret_key = $request->secret_key ?? Auth::user()->getTenant->phone_no;
        $tenant->public_key = $request->public_key ?? Auth::user()->getTenant->public_key;
        $tenant->save();
    }

    public function updateSenderId(Request $request){
        $tenant = Tenant::find(Auth::user()->id);
        $tenant->sender_id = $request->sender_id ?? '';
        $tenant->save();
    }

    public function getAllRegisteredTenants(){
        return Tenant::orderBy('id', 'DESC')->get();
    }
    public function getAllActiveRegisteredTenants(){
        return Tenant::where('account_status',1)->orderBy('company_name', 'ASC')->get();
    }

    public function getAllRegisteredTenantsThisMonth(){
        return Tenant::whereMonth('created_at', date('m'))->orderBy('id', 'DESC')->get();
    }

    public function getTenantBySlug($slug){
        return Tenant::where('slug', $slug)->first();
    }

    public function getTenantById($id){
        return Tenant::find($id);
    }

    public function updateTenantSubscriptionPeriod($tenant_id, $key, $start, $end,$plan){
        $tenant = Tenant::find($tenant_id);
        $tenant->active_sub_key = $key;
        $tenant->start_date = $start;
        $tenant->end_date = $end;
        $tenant->account_status = 1; //active
        $tenant->plan_id = $plan;
        $tenant->save();
    }

    public function getTenantPaymentGatewaySettings($tenant_id){
        return Tenant::where('id', $tenant_id)->first();
    }


}
