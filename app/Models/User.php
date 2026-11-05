<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function getTenantMailchimpSettings(){
        return $this->hasMany(MailchimpSettings::class, 'tenant_id', 'tenant_id');
    }

    public function getTenantBanks(){
        return $this->hasMany(Bank::class, 'tenant_id', 'tenant_id');
    }

    public function getTenantContacts(){
        return $this->hasMany(Contact::class, 'tenant_id', 'tenant_id')->orderBy('id', 'DESC');
    }

    public function getTenantNotifications(){
        return $this->hasMany(TenantNotification::class, 'tenant_id');
    }

    /*
     * Use-case methods
     */
    public function setNewUser(Request $request, $tenant){
        $user = new User();
        $user->first_name = $request->first_name ;
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->start_date = $tenant->start_date;
        $user->end_date = $tenant->end_date;
        $user->tenant_id = $tenant->id;
        $user->active_sub_key = $tenant->active_sub_key;
        $user->slug = Str::slug($request->first_name).'-'.substr(sha1(time()),32,40);
        $user->save();
    }

    public function setNewTeamMember(Request $request){
        $user = new User();
        $user->first_name = $request->first_name ;
        $user->surname = $request->last_name  ?? '';
        $user->mobile_no = $request->phone_no ?? '' ;
        $user->password = bcrypt('password123');
        $user->email = $request->email ?? '' ;
        $user->start_date = Auth::user()->start_date;
        $user->end_date = Auth::user()->end_date;
        $user->tenant_id = Auth::user()->id;
        $user->active_sub_key = Auth::user()->active_sub_key;
        $user->slug = Str::slug($request->first_name).'-'.substr(sha1(time()),32,40);
        $user->address = $request->address ?? '';
        $user->save();
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->first_name = $request->first_name ?? Auth::user()->first_name;
        $user->surname = $request->last_name ?? Auth::user()->surname;
        $user->mobile_no = $request->mobile_no ?? Auth::user()->mobile_no;
        $user->address = $request->address ?? Auth::user()->address;
        $user->gender = $request->gender ?? Auth::user()->gender;
        $user->save();
    }
    public function updateAvatar(Request $request){
        if($request->hasFile('avatar'))
        {
            $extension = $request->avatar->getClientOriginalExtension();
            $filename = Str::slug(Auth::user()->first_name).'_' . uniqid(). '.' . $extension;
            $dir = 'assets/drive/';
            $request->avatar->move(public_path($dir), $filename);
            $avatar = User::find(Auth::user()->id);
            $avatar->avatar = $filename;
            $avatar->save();
        }
    }

    public function getUserByEmail($email){
        return User::where('email', $email)->first();
    }

    public function getAllTenantUsersByTenantId($tenant_id){
        return User::where('tenant_id', $tenant_id)->get();
    }

    public function getUserBySlug($slug){
        return User::where('slug', $slug)->where('tenant_id', Auth::user()->tenant_id)->first();
    }

    public function getUserBySlugnTenantId($slug,$tenant_id){
        return User::where('slug', $slug)->where('tenant_id', $tenant_id)->first();
    }

    public function updateTenantActiveKey($tenant_id, $active_key, $start, $end){
        $users = User::where('tenant_id', $tenant_id)->get();
        if(!empty($tenants)){
            foreach ($users as $user){
                $user->active_sub_key = $active_key;
                $user->account_status = 1;
                $user->start_date = $start;
                $user->end_date = $end;
                $user->save();
            }
        }
    }


}
