<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->user = new User();
        $this->reset = new PasswordReset();
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email'
        ],[
            'email.required'=>'Enter your registered email address',
            'email.email'=>'Enter a valid email address'
        ]);

        $user = $this->user->getUserByEmail($request->email);
        if(!empty($user)){
            $token = $this->reset->setNewPasswordResetRequest($request);
            #Send mail
            try{
                \Mail::to($user)->send(new ResetPassword($token,$user) );
                session()->flash("success", "<strong>Great!</strong> You're one step away. Click the link we sent to your email. We'll help you get back your account.");
                return back();
            }catch (\Exception $ex){
                session()->flash("error", "<strong>Whoops!</strong> We had trouble sending you a reset link. Try again later.");
                return back();
            }
        }else{
            session()->flash("error", "<strong>Whoops!</strong> We could not find any account associated with this email (<i>".$request->email."</i>).");
            return back();
        }

    }
}
