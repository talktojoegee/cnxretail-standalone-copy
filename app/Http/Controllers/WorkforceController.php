<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WorkforceController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
        $this->user = new User();
        $this->tenant = new Tenant();
    }

    public function manageWorkforce(){
        return view('workforce.index',['users'=>$this->user->getAllTenantUsersByTenantId(Auth::user()->tenant_id)]);
    }

    public function viewProfile($slug){
        $user = $this->user->getUserBySlug($slug);
        if(!empty($user)){
            return view('workforce.view',['user'=>$user]);
        }else{
            session()->flash("error", "No record found");
            return back();
        }
    }

    public function showNewTeamMemberForm(){
        return view('workforce.add-new-team-member');
    }

    public function saveNewTeamMember(Request $request){
        $this->validate($request,[
            'first_name'=>'required',
            'last_name'=>'required',
            'phone_no'=>'required',
            'email'=>'required|email|unique:users,email',
            'gender'=>'required',
        ],[
            'last_name.required'=>'Enter your surname',
            'first_name.required'=>'Enter your first name',
            'email.required'=>'Enter a valid email address',
            'email.email'=>'Enter a valid email address',
            'email.unique'=>'Whoops! Another account exists with this email',
            'gender.required'=>'Select gender',
            'phone_no.required'=>'Enter mobile number'
        ]);
        $this->user->setNewTeamMember($request);
        session()->flash("success", "A new team member was added to your workforce successfully.");
        return back();
    }

    public function updateProfile(Request $request){
        $this->validate($request,[
           'first_name'=>'required',
           'last_name'=>'required',
            'mobile_no'=>'required',
            'address'=>'required',
            'gender'=>'required'
        ],[
            'first_name.required'=>'Enter your first name',
            'last_name.required'=>'Enter your surname',
            'mobile_no.required'=>'Enter your mobile number',
            'address.required'=>'Enter your address here',
            'gender.required'=>'Select your gender'
        ]);
        $this->user->updateProfile($request);
        session()->flash("success", "Your changes were saved successfully");
        return back();
    }

    public function changeAvatar(Request $request){
        $this->validate($request,[
            'avatar'=>'required'
        ],[
            'avatar.required'=>'Choose profile picture to upload.'
        ]);
        if($request->hasFile('avatar')){
            $this->user->updateAvatar($request);
        }
        session()->flash("success", "Your profile picture was changed.");
        return back();
    }

    public function changePassword(Request $request){
        $this->validate($request,[
            'current_password'=>'required',
            'password'=>'required|confirmed',
            'password_confirmation'=>'required'
        ],[
            'current_password.required'=>'Enter your current password for this account.',
            'password.required'=>'Choose a new password.',
            'password.confirmed'=>'New password does not match with confirm/re-type password.',
            'password_confirmation.required'=>'Re-type password'
        ]);
        if (Hash::check($request->current_password, Auth::user()->password)) {
            Auth::user()->password = bcrypt($request->password);
            Auth::user()->save();
            session()->flash("success", "<strong>Congratulations!</strong> You've successfully changed your password.");
            return back();
        }else{
            session()->flash("error", "<strong>Whoops!</strong> The password you entered does not match our record.");
            return back();
        }
    }


}
