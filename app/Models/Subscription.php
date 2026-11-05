<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    public function getSubscriptionPlan(){
        return $this->belongsTo(Pricing::class, 'plan_id');
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }


    public function renewSubscription($tenant_id, $plan, $sub_key, $start, $end, $status, $amount, $charge){
        $renew = new Subscription();
        $renew->tenant_id = $tenant_id;
        $renew->plan_id = $plan;
        $renew->sub_key = $sub_key;
        $renew->start_date = $start;
        $renew->end_date = $end;
        $renew->status = $status;
        $renew->amount = $amount - $charge;
        $renew->charge = $charge;
        $renew->save();
    }

    public function getTenantSubscriptions(){
        return Subscription::orderBy('id', 'DESC')->get();
    }

}
