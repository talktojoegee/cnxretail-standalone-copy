<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemGallery extends Model
{
    use HasFactory;

    /*
     * Use-case methods
     */

    public function getProductImageById($id){
        return ItemGallery::where('id',$id)->where('tenant_id', Auth::user()->tenant_id)->first();
    }

    public function deleteImage($id){
        $product = $this->getProductImageById($id);
        if(!empty($product)){
            $product->delete();
                if(\File::exists(public_path('assets/drive/'.$product->attachment))){
                    \File::delete(public_path('assets/drive/'.$product->attachment));
                }
            return true;
        }else{
            return false;
        }
    }

}
