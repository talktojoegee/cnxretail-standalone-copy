<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\AdminUser;
use App\Models\DailyMotivation;
use App\Models\Pricing;
use App\Models\Subscription;
use App\Models\Tenant;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->adminuser = new AdminUser();
        $this->tenant = new Tenant();
        $this->subscription = new Subscription();
        $this->pricing = new Pricing();
        $this->dailymotivation = new DailyMotivation();
        $this->adminnotification = new AdminNotification();
    }

    public function notification(){
        return view('admin.notifications',['notifications'=>$this->adminnotification->getNotifications()]);
    }

    public function adminDashboard(){
        return view('admin.admin-dashboard',[
            'tenants'=>$this->tenant->getAllRegisteredTenants(),
            'thismonth'=>$this->tenant->getAllRegisteredTenantsThisMonth()
        ]);
    }

    public function showAddNewUserForm(){
        return view('admin.add-new-admin');
    }

    public function storeAdminUser(Request $request){
        $this->validate($request,[
            'full_name'=>'required',
            'email'=>'required|email|unique:admin_users,email',
            'mobile_no'=>'required',
            'password'=>'required'
        ],[
            'full_name.required'=>'Enter user full name',
            'email.required'=>'Enter a valid email address',
            'email.email'=>'Enter a valid email address',
            'email.unique'=>'This email address is already in use',
            'mobile_no.required'=>'Enter user mobile number',
            'password.required'=>'Choose password for user',
            //'password.confirmed'=>'Password'
        ]);
        $this->adminuser->createNewAdminUser($request);
        session()->flash("success","Your action was registered successfully.");
        return back();
    }

    public function manageTenants(){
        return view('admin.manage-tenants',['tenants'=>$this->tenant->getAllRegisteredTenants()]);
    }

    public function viewTenant($slug){
        $tenant = $this->tenant->getTenantBySlug($slug);
        if(!empty($tenant)){
            return view('admin.view-tenant', ['tenant'=>$tenant]);
        }else{
            session()->flash("error", "No record found.");
            return back();
        }
    }

    public function getTenantSubscriptions(){
        return view('admin.subscription',['subscriptions'=>$this->subscription->getTenantSubscriptions()]);
    }

    public function managePricing(){
        return view('admin.manage-pricing',['pricings'=>$this->pricing->getAllPricing()]);
    }

    public function addPricing(Request $request){
        $this->validate($request,[
            'name'=>'required|unique:pricings,price_name',
            'amount'=>'required',
            'duration'=>'required'
        ],[
            'name.required'=>'Enter price name',
            'name.unique'=>'Enter a unique price name',
            'amount.required'=>'Enter amount',
            'duration.required'=>'Enter duration for this pricing plan'
        ]);
        $this->pricing->setNewPricing($request);
        session()->flash("success", "New pricing plan added successfully");
        return back();
    }

    public function editPricing(Request $request){
        $this->validate($request,[
            'name'=>'required',
            'amount'=>'required',
            'duration'=>'required',
            'price'=>'required'
        ],[
            'name.required'=>'Enter price name',
            'amount.required'=>'Enter amount',
            'duration.required'=>'Enter duration for this pricing plan'
        ]);
        $this->pricing->editPricing($request);
        session()->flash("success", "Your changes were saved successfully");
        return back();
    }

    public function manageDailyMotivations(){
        return view('admin.manage-daily-motivation',['motivations'=>$this->dailymotivation->getAllDailyMotivations()]);
    }

    public function addDailyMotivation(Request $request){
        $this->validate($request,[
            'time'=>'required',
            'author'=>'required',
            'motivation'=>'required'
        ],[
            'time.required'=>'Select time of day',
            'author.required'=>'Enter the name of the author or type Unknown',
            'motivation.required'=>'Enter motivation here...'
        ]);
        $this->dailymotivation->setNewDailyMotivation($request);
        session()->flash("success", "Daily motivation added successfully.");
        return back();
    }
    public function updateDailyMotivation(Request $request){
        $this->validate($request,[
            'time'=>'required',
            'author'=>'required',
            'motivation'=>'required'
        ],[
            'time.required'=>'Select time of day',
            'author.required'=>'Enter the name of the author or type Unknown',
            'motivation.required'=>'Enter motivation here...'
        ]);
        $this->dailymotivation->editDailyMotivation($request);
        session()->flash("success", "Your changes were saved.");
        return back();
    }

    public function manageAdminUsers(){
        return view('admin.manage-admin-users',['users'=>$this->adminuser->getAllAdminUsers()]);
    }

}
