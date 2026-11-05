<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\MailchimpSettings;
use App\Models\Tenant;
use Illuminate\Http\Request;

class AppSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->tenant = new Tenant();
        $this->mailchimpsettings = new MailchimpSettings();
        $this->bank = new Bank();
    }

    public function showSettingsView(){
        return view('settings.settings');
    }

    public function saveSettings(Request $request){
        $this->validate($request,[
           'company_name'=>'required',
            'phone_no'=>'required'
        ],[
            'company.required'=>'Enter your company or business name',
            'phone_no.required'=>'Enter your company or business phone number'
        ]);
        $this->tenant->updateTenantDetails($request);
        session()->flash("success", "Your changes were saved successfully.");
        return back();
    }

    public function savePaymentIntegration(Request $request){
        $this->validate($request,[
           'public_key'=>'required',
            'secret_key'=>'required'
        ],[
            'public_key.required'=>'Enter your Paystack live public key',
            'secret_key.required'=>'Enter your Paystack live secret key'
        ]);
        $this->tenant->updateTenantPaymentIntegration($request);
        session()->flash("success", "Your payment integration changes were saved successfully. Please don't forget to use <a href=''>http://app.cnxretail.com/process/payment</a> as your Callback URL");
        return back();
    }
    public function saveMailchimpSetting(Request $request){
        $this->validate($request,[
           'api_key'=>'required',
            'list_id'=>'required'
        ],[
            'api_key.required'=>'Enter your Mailchimp API key',
            'list_id.required'=>'Enter your Mailchimp list ID'
        ]);
        $this->mailchimpsettings->setNewMailchimpSettings($request);
        session()->flash("success", "Your Mailchimp settings were saved successfully.");
        return back();
    }

    public function updateMailchimpSetting(Request $request){
        $this->validate($request,[
           'api_key'=>'required',
            'list_id'=>'required',
            'mailchimp'=>'required'
        ],[
            'api_key.required'=>'Enter your Mailchimp API key',
            'list_id.required'=>'Enter your Mailchimp list ID'
        ]);
        $this->mailchimpsettings->updateMailchimpSettings($request);
        session()->flash("success", "Your Mailchimp changes were saved successfully.");
        return back();
    }

    public function saveBank(Request $request){
        $this->validate($request,[
            'bank_name'=>'required',
            'account_name'=>'required',
            'account_no'=>'required',
        ],[
            'bank_name.required'=>'Enter bank name',
            'account_name.required'=>'Enter account name',
            'account_no.required'=>'Enter account number'
        ]);
        $this->bank->setNewBank($request);
        session()->flash("success", "Your bank was registered successfully");
        return back();
    }
    public function updateBank(Request $request){
        $this->validate($request,[
            'bank_name'=>'required',
            'account_name'=>'required',
            'account_no'=>'required',
            'bank_id'=>'required'
        ],[
            'bank_name.required'=>'Enter bank name',
            'account_name.required'=>'Enter account name',
            'account_no.required'=>'Enter account number'
        ]);
        $this->bank->updateBank($request);
        session()->flash("success", "Your bank changes were saved successfully");
        return back();
    }

    public function updateBulkSmsSettings(Request $request){
        $this->validate($request,[
            'sender_id'=>'required|max:10|unique:tenants,sender_id'
        ],[
            'sender_id.required'=>'Kindly enter sender ID',
            'sender_id.min'=>'The maximum number of characters allowed is 10 characters',
            'sender_id.unique'=>"This sender ID is already taken. Choose another one"
        ]);
        $this->tenant->updateSenderId($request);
        session()->flash("success", "Your sender ID settings were updated successfully. Hold on for verification.");
        return back();
    }
}
