<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    public function getItemGalleryImages(){
        return $this->hasMany(ItemGallery::class, 'item_id');
    }

    public function getAddedBy(){
        return $this->belongsTo(User::class, 'added_by');
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function getCategory(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    /*
     * Use-case methods
     */

public function setNewItem(Request $request){
    $item = new Item();
    $item->item_name = $request->item_name;
    $item->tenant_id = Auth::user()->tenant_id;
    $item->item_type = $request->item_type;
    if($request->item_type == 1) {$item->category_id = $request->category ?? '';}
    $item->quantity = $request->quantity ?? 0;
    $item->cost_price = $request->item_type == 2 ? 0 : str_replace(",", "", $request->cost_price);
    $item->selling_price = $request->item_type == 1 ? str_replace(",", "",$request->selling_price) : str_replace(",","",$request->service_fee);
    $item->description = $request->item_type == 1 ? $request->p_description : $request->description;
    $item->added_by = Auth::user()->id;
    $item->slug = Str::slug($request->item_name).'-'.substr(sha1(time()),32,40);
    $item->save();
    #Upload Gallery
    $this->uploadGallery($request, $item);
}

    public function updateProduct(Request $request){
        $item = Item::find($request->product);
        $item->item_name = $request->item_name;
        $item->tenant_id = Auth::user()->tenant_id;
        $item->item_type = $request->item_type;
        if($request->item_type == 1) {$item->category_id = $request->category ?? '';}
        $item->quantity = $request->quantity ?? 0;
        $item->cost_price = $request->cost_price ?? 0;
        $item->description = $request->item_type == 1 ? $request->p_description : $request->description;
        $item->selling_price = $request->item_type == 1 ? $request->selling_price : $request->service_fee;
        $item->slug = Str::slug($request->item_name).'-'.substr(sha1(time()),32,40);
        $item->save();
        #Upload Gallery
        $this->uploadGallery($request, $item);
    }

    public function uploadGallery(Request $request, $item){

        if ($request->hasFile('attachments')) {
            foreach($request->attachments as $attachment){
                $extension = $attachment->getClientOriginalExtension();
                $filename = uniqid() . '.' . $extension;
                $dir = 'assets/drive/';
                $attachment->move(public_path($dir), $filename);
                $gallery = new ItemGallery();
                $gallery->attachment = $filename;
                $gallery->item_id = $item->id;
                $gallery->tenant_id = Auth::user()->tenant_id;
                $gallery->save();
            }
        }
    }

    public function getItemByItemType($type){
        return Item::where('item_type', $type)->where('tenant_id', Auth::user()->tenant_id)->orderBy('id', 'DESC')->get();
    }

    public function getItemFirstGalleryImage($item_id){
        return ItemGallery::where('item_id', $item_id)->first();
    }

    public function getTenantItemBySlug($slug){
        return Item::where('tenant_id', Auth::user()->tenant_id)->where('slug', $slug)->first();
    }
    public function getItemBySlug($slug){
        return Item::where('slug', $slug)->first();
    }

    public function getItemById($id){
    return Item::find($id);
    }

    public function getAllTenantItems($tenant_id){
        return Item::where('tenant_id', $tenant_id)->get();
    }

    public function getItemsAtRandom(){
        return Item::where('item_type',1)->inRandomOrder()->paginate(50);
    }

    public function getItemsByCategoryId($cat_id){
        return Item::where('item_type',1)->where('category_id', $cat_id)->inRandomOrder()->paginate(50);
    }

    public function searchForProduct($keyword){
        return Item::where('item_name', 'like', '%' . $keyword . '%')->get();
    }
}
