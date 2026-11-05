<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pricing extends Model
{
    use HasFactory;


    public function setNewPricing(Request $request){

        $price = new Pricing();
        $price->price_name = $request->name ?? '' ;
        $price->price = $request->amount ?? 0;
        $price->duration = $request->duration ?? 30 ; //14 days trial
        $price->description = $request->description ?? '';
        $price->save();
    }

    public function editPricing(Request $request){
        $price = Pricing::find($request->price);
        $price->price_name = $request->name ?? '' ;
        $price->price = $request->amount ?? 0;
        $price->duration = $request->duration ?? 30 ; //14 days trial
        $price->description = $request->description ?? '';
        $price->save();
    }

    public function getPricingExcludingTrial(){
        return Pricing::where('duration', '>', 30)->orderBy('duration', 'ASC')->get();
    }
    public function getAllPricing(){
        return Pricing::orderBy('duration', 'ASC')->get();
    }

    public function getPricingByPricingId($id){
        return Pricing::find($id);
    }


}
