<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceiptDetail extends Model
{
    use HasFactory;




    public function getService(){
        return $this->belongsTo(Item::class, 'service_id');
    }


    public function setNewReceiptItems(Request $request, $receipt){
        for($n = 0; $n<count($request->item_name); $n++){
            $receipt_item = new ReceiptDetail();
            $receipt_item->receipt_id = $receipt->id;
            $receipt_item->tenant_id = Auth::user()->tenant_id;
            $receipt_item->service_id = $request->item_name[$n];
            $receipt_item->quantity = $request->quantity[$n];
            $receipt_item->unit_cost = $request->amount[$n];
            $receipt_item->payment = $request->quantity[$n] * $request->amount[$n];
            $receipt_item->save();
        }
    }

    public function getServiceById($id){
        return Item::find($id);
    }
}
