<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->vendor = new Vendor();
    }

    public function allVendors(){
        return view('vendors.index',[
            'vendors'=>$this->vendor->getAllTenantVendors()
        ]);
    }

    public function showAddNewVendorForm(){
        return view('vendors.add-new-vendor');
    }

    public function saveVendor(Request $request){
        $this->validate($request,[
            'company_name'=>'required',
            'company_phone_no'=>'required',
            'company_email'=>'required',
            'address'=>'required',
            'first_name'=>'required',
            'phone_no'=>'required',
            'last_name'=>'required',
            'email'=>'required',
        ],[
            'company_name.required'=>'Enter company name',
            'company_phone_no.required'=>'Enter company phone number',
            'company_email.required'=>'Enter a valid company email address',
            'address.required'=>'Enter the company office address',
            'first_name.required'=>'Enter contact person first name',
            'phone_no.required'=>'Enter contact person phone number',
            'last_name.required'=>'Enter contact person last name',
            'email.required'=>'Enter a valid email address for the contact person'
        ]);
        $this->vendor->setNewVendor($request);
        session()->flash("success", "Your new vendor was registered successfully.");
        return back();
    }
}
