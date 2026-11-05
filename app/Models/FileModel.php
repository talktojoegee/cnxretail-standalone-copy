<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FileModel extends Model
{
    use HasFactory;




    /*
     * Use-case methods
     */

    public function uploadFiles(Request $request)
    {
        if ($request->hasFile('attachments')) {
            foreach($request->attachments as $attachment){
                //$extension = $attachment->file('attachments');
                $extension = $attachment->getClientOriginalExtension();
                $size = $attachment->getSize();
                $filename = config('app.name').'_' . uniqid() . '_' . time() . '_' . date('Ymd') . '.' . $extension;
                $dir = 'assets/drive/';
                $attachment->move(public_path($dir), $filename);
                $file = new FileModel();
                $file->filename = $filename;
                $file->name = $request->file_name;
                $file->folder_id = $request->folder;
                $file->uploaded_by = Auth::user()->id;
                $file->slug = substr(sha1(time()),32,40);
                $file->size = $size;
                $file->tenant_id = Auth::user()->tenant_id;
                $file->save();
            }
        }

    }


    public function getFilesByFolderId($id){
        return FileModel::where('folder_id', $id)->where('tenant_id', Auth::user()->tenant_id)->get();
    }

    public function getFileById($id){
        return FileModel::find($id);
    }

    public function getIndexFiles(){
        return FileModel::where('folder_id',0)->where('tenant_id', Auth::user()->tenant_id)->get();
    }

    public function downloadFile($file_name) {
        $file_path = public_path('assets/drive/'.$file_name);
        if(file_exists($file_path)){
            return response()->download($file_path);
        }else{
            return 0; //file not found.
        }
    }

    public function deleteFile($file){
        if(\File::exists(public_path('assets/drive/'.$file))){
            \File::delete(public_path('assets/drive/'.$file));
            return 1;
        }else{
            return 0;
        }
    }

}
