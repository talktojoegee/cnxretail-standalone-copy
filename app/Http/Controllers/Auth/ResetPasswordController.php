<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;



    public function showResetForm($token)
    {
        $request = $this->reset->getPasswordResetRequestByToken($token);
        if(!empty($request)){
            return view('auth.passwords.reset',['token'=>$token]);
        }else{
            session()->flash("error", "<strong>Whoops!</strong> Password reset link has expired or is no longer valid.");
            return redirect()->route('password.request');
        }
    }

    public function reset(Request $request)
    {
        $this->validate($request,[
            'password'=>'required|confirmed',
            'token'=>'required'
        ],[
            'password.required'=>'Choose a new password',
            'password.confirmed'=>'Your chosen password does not match re-typed password.'
        ]);
        #Validate slug
        $token = $this->reset->getPasswordResetRequestByToken($request->token);
        if(!empty($token)){
            $email = $token->email;
            $user = $this->user->getUserByEmail($email);
            $user->password = bcrypt($request->password);
            $user->save();
            #Delete password reset request
            PasswordReset::where('email', $email)->delete();
            session()->flash("success", "<strong>Congratulations!</strong> You've successfully reset your password. Proceed to login.");
            return redirect()->route('login');
        }else{
            session()->flash("error", "<strong>Whoops!</strong> Password reset link has expired or is no longer valid.");
            return redirect()->route('reset-password');
        }
    }
}
