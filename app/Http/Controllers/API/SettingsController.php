<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\MailchimpSettings;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->tenant = new Tenant();
        $this->mailchimpsettings = new MailchimpSettings();
        $this->bank = new Bank();

    }

    public function getTenantInfo()
    {
        try {
            $id = $request->id ?? 0;
            $tenant = $this->tenant->getTenantById(Auth::user()->tenant_id);
            $mailchimp = $this->mailchimpsettings->getTenantMailChimpFirstApiKey();
            $mailchimpKeys = $this->mailchimpsettings->getTenantMailChimpInfo();
            $banks = $this->bank->getAllTenantBanks();
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success Tenant loaded",
                'data' => [
                    "tenant" => $tenant,
                    "mailchimp" => $mailchimp,
                    "mailchimpkeys" => $mailchimpKeys,
                    "banks" => $banks,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function saveSettings(Request $request)
    {
        $validator = validator::make($request->all(), [
            'company_name' => 'required',
            'phone_no' => 'required'
        ], [
            'company.required' => 'Enter your company or business name',
            'phone_no.required' => 'Enter your company or business phone number'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $this->tenant->updateTenantDetails($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success!, updated",
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function savePaymentIntegration(Request $request)
    {
        $validator = validator::make($request->all(), [
            'public_key' => 'required',
            'secret_key' => 'required'
        ], [
            'public_key.required' => 'Enter your Paystack live public key',
            'secret_key.required' => 'Enter your Paystack live secret key'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $this->tenant->updateTenantPaymentIntegration($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success!, updated",
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function saveMailchimpSetting(Request $request)
    {
        $validator = validator::make($request->all(), [
            'api_key' => 'required',
            'list_id' => 'required'
        ], [
            'api_key.required' => 'Enter your Mailchimp API key',
            'list_id.required' => 'Enter your Mailchimp list ID'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $setting = $this->mailchimpsettings->setNewMailchimpSettings($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success!, updated",
                'data' => [
                    "mailchimpkeys" => $setting
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function updateMailchimpSetting(Request $request)
    {
        $this->validate($request, [
            'api_key' => 'required',
            'list_id' => 'required',
            'mailchimp' => 'required'
        ], [
            'api_key.required' => 'Enter your Mailchimp API key',
            'list_id.required' => 'Enter your Mailchimp list ID'
        ]);
        $this->mailchimpsettings->updateMailchimpSettings($request);
        session()->flash("success", "Your Mailchimp changes were saved successfully.");
        return back();
    }

    public function saveBank(Request $request)
    {
        $validator = validator::make($request->all(), [
            'bank_name' => 'required',
            'account_name' => 'required',
            'account_no' => 'required',
        ], [
            'bank_name.required' => 'Enter bank name',
            'account_name.required' => 'Enter account name',
            'account_no.required' => 'Enter account number'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $bank = $this->bank->setNewBank($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success!, Bank Added!",
                'data' => [
                    "banks" => $bank
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

    public function updateBank(Request $request)
    {
        $this->validate($request, [
            'bank_name' => 'required',
            'account_name' => 'required',
            'account_no' => 'required',
            'bank_id' => 'required'
        ], [
            'bank_name.required' => 'Enter bank name',
            'account_name.required' => 'Enter account name',
            'account_no.required' => 'Enter account number'
        ]);
        $this->bank->updateBank($request);
        session()->flash("success", "Your bank changes were saved successfully");
        return back();
    }

    public function updateBulkSmsSettings(Request $request)
    {
        $validator = validator::make($request->all(), [
            'sender_id' => 'required|max:10|unique:tenants,sender_id'
        ], [
            'sender_id.required' => 'Kindly enter sender ID',
            'sender_id.min' => 'The maximum number of characters allowed is 10 characters',
            'sender_id.unique' => "This sender ID is already taken. Choose another one"
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'code' => 400,
                'message' => $validator->errors()->first(),
                'data' => ""
            ]);
        }
        try {
            $this->tenant->updateSenderId($request);
            return response()->json([
                'success' => true,
                'code' => 200,
                'message' => "Success!, Sender ID Updated!",
                'data' => []
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'code' => 500,
                'message' => "Oops something bad happened, Please try again! ",
                'data' => ''
            ]);
        }
    }

}
