<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;

class AdminUser extends Authenticatable
{
    use HasFactory;



    public function createNewAdminUser(Request $request){
        $admin = new AdminUser;
        $admin->full_name = $request->full_name ?? '';
        $admin->email = $request->email ?? '';
        $admin->mobile_no = $request->mobile_no ?? '';
        $admin->password = bcrypt($request->password);
        $admin->slug = substr(time(),28,40);
        $admin->save();
    }

    public function getAllAdminUsers(){
        return AdminUser::orderBy('full_name', 'ASC')->get();
    }

    public function getAdminUserBySlug($slug){
        return AdminUser::where('slug', $slug)->first();
    }

}
