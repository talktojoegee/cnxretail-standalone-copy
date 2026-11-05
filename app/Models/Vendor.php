<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Vendor extends Model
{
    use HasFactory;



    /*
  * Use-case methods
  */
    public function setNewVendor(Request $request){
        $contact = new Vendor();
        $contact->added_by = Auth::user()->id;
        $contact->tenant_id = Auth::user()->tenant_id;
        $contact->company_name = $request->company_name ?? '';
        $contact->company_address = $request->address ?? '';
        $contact->company_email = $request->company_email ?? '';
        $contact->company_phone = $request->company_phone_no ?? '';
        $contact->company_website = $request->website ?? '';
        $contact->contact_first_name = $request->first_name ?? '';
        $contact->contact_last_name = $request->last_name ?? '';
        $contact->contact_position = $request->position ?? '';
        $contact->contact_email = $request->email ?? '';
        $contact->contact_mobile = $request->phone_no ?? '';
        $contact->communication_channel = $request->communication_channel ?? '';
        $contact->whatsapp_contact = $request->whatsapp_contact ?? '';
        $contact->hear_about_us = $request->hear_about_us ?? '';
        $contact->preferred_time = $request->preferred_time ?? now();
        $contact->slug = substr(sha1(time()),23,40);
        $contact->save();
    }

    public function updateVendor(Request $request){
        $contact = Vendor::find($request->vendor);
        $contact->added_by = Auth::user()->id;
        $contact->tenant_id = Auth::user()->tenant_id;
        $contact->company_name = $request->company_name ?? '';
        $contact->company_address = $request->address ?? '';
        $contact->company_email = $request->company_email ?? '';
        $contact->company_phone = $request->company_phone_no ?? '';
        $contact->company_website = $request->website ?? '';
        $contact->contact_first_name = $request->first_name ?? '';
        $contact->contact_last_name = $request->last_name ?? '';
        $contact->contact_position = $request->position ?? '';
        $contact->contact_email = $request->email ?? '';
        $contact->contact_mobile = $request->phone_no ?? '';
        $contact->communication_channel = $request->communication_channel ?? '';
        $contact->whatsapp_contact = $request->whatsapp_contact ?? '';
        $contact->hear_about_us = $request->hear_about_us ?? '';
        $contact->preferred_time = $request->preferred_time ?? now();
        $contact->save();
    }

    public function getVendorBySlug($slug){
        return Vendor::where('slug', $slug)->first();
    }

    public function getVendorById($id){
        return Vendor::find($id);
    }

    public function getAllTenantVendors(){
        return Vendor::where('tenant_id', Auth::user()->tenant_id)->orderBy('company_name', 'ASC')->get();
    }
}
