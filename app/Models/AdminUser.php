<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AdminUser extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'typ' => 'admin',
        ];
    }

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
