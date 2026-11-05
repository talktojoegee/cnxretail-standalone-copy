<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(){

        return view('auth.admin.login');
    }

    public function login(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required'
        ],[
            'email.required'=>'Enter your registered email address',
            'email.email'=>'Enter a valid email address',
            'password.required'=>'Enter your password for this account'
        ]);
        $user = AdminUser::where('email', $request->email)->first();

        if(!empty($user)){
            if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password'=>$request->password, 'account_status'=>1], $request->remember)){
                return redirect()->route('admin.dashboard');
            }else{
                session()->flash("error", "<strong>Error! </strong> Wrong or invalid login credentials. Try again.");
                return back();
            }
        }else{
            session()->flash("error", "<strong>Ooops!</strong> There's no existing account with this details.");
            return back();
        }

    }
}
