<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailchimpSettings extends Model
{
    use HasFactory;


    /*
     * Use-case methods
     */
    public function setNewMailchimpSettings(Request $request){
        $settings = new MailchimpSettings();
        $settings->tenant_id = Auth::user()->tenant_id;
        $settings->mailchimp_api_key = $request->api_key;
        $settings->mailchimp_list_id = $request->list_id;
        $settings->updated_by = Auth::user()->id;
        $settings->save();
    }

    public function updateMailchimpSettings(Request $request){
        $settings = MailchimpSettings::find($request->mailchimp);
        $settings->tenant_id = Auth::user()->tenant_id;
        $settings->mailchimp_api_key = $request->api_key;
        $settings->mailchimp_list_id = $request->list_id;
        $settings->updated_by = Auth::user()->id;
        $settings->save();
    }

    public function getTenantMailChimpFirstApiKey(){
        return MailchimpSettings::where('tenant_id', Auth::user()->tenant_id)->first();
    }


}
