<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderModel extends Model
{
    use HasFactory;


    /*
     * Use-case methods
     */
    public function setNewFolder(Request $request){
        $folder = new FolderModel();
        $folder->folder = $request->folder_name ?? '';
        $folder->created_by = Auth::user()->id;
        $folder->parent_id = $request->parent_folder ?? 0;
        $folder->slug = substr(sha1(time()),27,40);
        $folder->tenant_id = Auth::user()->tenant_id;
        $folder->save();
    }

    public function getAllFolders(){
        return FolderModel::where('tenant_id', Auth::user()->tenant_id)->orderBy('folder', 'ASC')->get();
    }

    public function getFolderBySlug($slug){
        return FolderModel::where('slug', $slug)->first();
    }
    public function getSubFoldersByParentId($id){
        return FolderModel::where('parent_id', $id)->where('tenant_id', Auth::user()->tenant_id)->get();
    }


}
