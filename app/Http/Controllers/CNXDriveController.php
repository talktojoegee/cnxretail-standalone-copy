<?php

namespace App\Http\Controllers;

use App\Models\FileModel;
use App\Models\FolderModel;
use Illuminate\Http\Request;

class CNXDriveController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->file = new FileModel();
        $this->folder = new FolderModel();
    }

    public function manageStorage(){
        return view('cnxdrive.index',[
            'folders'=>$this->folder->getAllFolders(),
            'files'=>$this->file->getIndexFiles()
        ]);
    }

    public function storeFiles(Request $request){
        $this->validate($request,[
            'attachments'=>'required',
            'folder'=>'required',
            'file_name'=>'required'
        ]);
        $this->file->uploadFiles($request);
        session()->flash("success", "<strong>Success!</strong> File(s) uploaded.");
        return back();
    }

    public function createFolder(Request $request){
        $this->validate($request,[
            'folder_name'=>'required',
            'visibility'=>'required'
        ]);

        $this->folder->setNewFolder($request);
        session()->flash("success", "<strong>Success!</strong> New folder created.");
        return back();
    }

    public function openFolder($slug){
        $folder = $this->folder->getFolderBySlug($slug);

        if(!empty($folder)){
            $files = $this->file->getFilesByFolderId($folder->id);
            $folders = $this->folder->getSubFoldersByParentId($folder->id);
            return view('cnxdrive.view-folder',['files'=>$files, 'folder'=>$folder, 'folders'=>$folders]);
        }else{
            session()->flash("error", "<strong>Ooops!</strong> No record found.");
            return back();
        }
    }


    public function downloadAttachment($file_name){
        try{
            return $this->file->downloadFile($file_name);
            session()->flash("success", "Processing request...");
            return back();
        }catch (\Exception $ex){
            session()->flash("error", "Ooops! File does not exist.");
            return back();
        }

    }


    public function deleteAttachment(Request $request){
        $this->validate($request,[
            'directory'=>'required',
            'key'=>'required'
        ]);
        $file = $this->file->getFileById($request->key);
        if(!empty($file)){
            #Unlink
            $this->file->deleteFile($file->filename);
            $file->delete();
            session()->flash("success", "File deleted.");
            return back();
        }else{
            session()->flash("error", "Ooops! File does not exist.");
            return back();
        }

    }


    public function deleteFolder(Request $request){
        $this->validate($request,[
            'folder_key'=>'required'
        ]);
        $folder = $this->folder->find($request->folder_key);
        if(!empty($folder)){
            $folder->delete();
            session()->flash("success", "<strong>Success!</strong> Folder deleted.");
            return back();
        }else{
            session()->flash("error", "Ooops! Folder does not exist.");
            return back();
        }
    }
}
