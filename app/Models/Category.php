<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    /*
     * Use-case methods
     */

    public function setNewCategory(Request $request){
        $cat = new Category();
        $cat->tenant_id = Auth::user()->tenant_id;
        $cat->category_name = $request->category_name;
        $cat->slug = Str::slug($request->category_name);
        $cat->save();
    }
    public function updateCategory(Request $request){
        $cat =  Category::where('id',$request->category)->where('tenant_id', Auth::user()->tenant_id)->first();
        $cat->category_name = $request->category_name;
        $cat->save();
    }

    public function getCategoryById($id){
        return Category::find($id);
    }
    public function getCategoryBySlug($slug){
        return Category::where('slug',$slug)->first();
    }

    public function getAllCategories(){
        return Category::orderBy('category_name', 'ASC')->get();
    }
    public function getAllGeneralCategories(){
        return Category::orderBy('category_name', 'ASC')->get();
    }
}
