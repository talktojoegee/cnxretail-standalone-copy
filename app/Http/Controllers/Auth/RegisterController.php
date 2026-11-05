<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Newsletter\Facades\Newsletter;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        $this->user = new User();
        $this->tenant = new Tenant();
        $this->adminnotification = new AdminNotification();
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request){
        $this->validate($request,[
            'company_name'=>'required',
            'first_name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|confirmed',
            'terms'=>'required'
        ],[
            'company_name.required'=>'Enter your company name',
            'first_name.required'=>'Enter your first name',
            'email.required'=>'Enter a valid email address',
            'email.email'=>'Enter a valid email address',
            'email.unique'=>'Whoops! Another account exists with this email',
            'password.required'=>'Choose a password',
            'password.confirmed'=>'Your chosen password does not match re-type password',
            'terms.required'=>'Accept our terms & conditions to continue with this registration'
        ]);
        $tenant = $this->tenant->setNewTenant($request);
        $this->user->setNewUser($request, $tenant);
        #Notification
        $subject = "New registration";
        $body = "There's a new registration to CNX Retail. Kindly check it out.";
        $this->adminnotification->setNewAdminNotification($subject, $body, 'view-tenant', $tenant->slug, 1, 0);
        #Mailchimp welcome email
        try {
            if ( ! Newsletter::isSubscribed($request->email) ) {
                Newsletter::subscribe($request->email);
                Newsletter::subscribe($request->email, ['FNAME'=>$request->first_name]);
            }
        }catch (\Exception $exception){

        }

        session()->flash("success", "Your business or company is now live on ".config('app.name').". We sent you an email with your subscription details. You may now proceed to <a href='".route('login')."'>login</a>.");
        return back();
    }
}
