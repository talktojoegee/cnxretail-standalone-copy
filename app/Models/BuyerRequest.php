<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class BuyerRequest extends Model
{
    use HasFactory;




    public function setNewBuyerRequest(Request $request, $tenant_id){
        $buyer = new BuyerRequest();
        $buyer->product_id = $request->item;
        $buyer->first_name = $request->first_name ?? '';
        $buyer->email = $request->email ?? '';
        $buyer->phone_no = $request->mobile_no ?? '';
        $buyer->message = $request->message ?? '';
        $buyer->tenant_id = $tenant_id;
        $buyer->save();
    }
}
