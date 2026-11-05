<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Pricing;
use App\Models\Tenant;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Yabacon\Paystack;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
        $this->activitylog = new ActivityLog();
        $this->user = new User();
        $this->pricing = new Pricing();
        $this->tenant = new Tenant();
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
        $user = $this->user->getUserByEmail($request->email);
        if(!empty($user)){
            if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password], $request->remember)){
                $users = $this->user->getAllTenantUsersByTenantId(Auth::user()->tenant_id);
                if(strtotime(now())  > strtotime(Auth::user()->getTenant->end_date)){
                    foreach($users as $use){
                        $use->account_status = 0; //inactive
                        $use->save();
                    }
                    Auth::logout();
                    $tenant = $this->tenant->getTenantById($user->tenant_id);
                    return redirect()->route('renew-subscription',['tenant_slug'=>$tenant->slug, 'user_slug'=>$user->slug]);
                }else{
                    foreach($users as $use){
                        $use->account_status = 1; //active
                        $use->save();
                    }
                }
                return redirect()->route('dashboard');
            }else{
                session()->flash("error", " Wrong or invalid login credentials. Try again.");
                return back();
            }
        }else{
            session()->flash("error", "There's no existing account with this login details. Try again.");
            return back();
        }
    }

    public function showRenewSubscriptionForm($tenant_slug,$user_slug){
        $tenant = $this->tenant->getTenantBySlug($tenant_slug);
        if( !empty($tenant)){
            $user = $this->user->getUserBySlugnTenantId($user_slug, $tenant->id);
            $message = "Your subscription has expired. Renew your subscription to continue using this service. Thank you.";
            return view('auth.renew-subscription',[
                'pricing'=>$this->pricing->getPricingExcludingTrial(),
                'user'=>$user,
                'message'=>$message
            ]);
        }else{
            session()->flash("Whoops! Something went wrong. Try again.");
            return back();
        }
    }

    public function processRenewSubscription(Request $request){
        $this->validate($request,[
            'email'=>'required|email',
            'pricing'=>'required'
        ],[
            'email.required'=>"Email field is missing."  ,
            'email.email'=>"Enter valid email address",
            'pricing.required'=>"Select pricing plan from the list provided."
        ]);
        $price = $request->pricing;
        $amount = $this->pricing->getPricingByPricingId($price);
        $charge = ceil($amount->price*1.5)/100;

        if(!empty($amount)){
            try{
                $paystack = new Paystack(config('app.paystack_secret_key'));
                $builder = new Paystack\MetadataBuilder();
                $builder->withUser($request->user);
                $builder->withCharge($charge);
                $builder->withTenant($request->tenant);
                $builder->withPricing($request->pricing);
                /*
                 * Transaction Type:
                 *  1 = New tenant subscription
                 *  2 = Subscription Renewal
                 *  3 = Invoice Payment
                 *  4 = SMS Top-up
                 */
                $builder->withTransaction(2);//renew subscription
                $metadata = $builder->build();
                $tranx = $paystack->transaction->initialize([
                    'amount'=>($amount->price+$charge)*100,       // in kobo
                    'email'=>$request->email,         // unique to customers
                    'reference'=>substr(sha1(time()),23,40), // unique to transactions
                    'metadata'=>$metadata
                ]);
                return redirect()->to($tranx->data->authorization_url)->send();
            }catch (Paystack\Exception\ApiException $exception){
                //print_r($exception->getResponseObject());
                //die($exception->getMessage());
                session()->flash("error", "Whoops! Something went wrong. Try again.");
                return back();
            }
        }else{
            session()->flash("error", "Whoops! Something went wrong. Try again.");
            return back();
        }
    }
}
