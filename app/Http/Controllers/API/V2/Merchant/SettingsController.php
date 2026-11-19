<?php

namespace App\Http\Controllers\API\V2\Merchant;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\TenantResource;
use App\Models\Bank;
use App\Models\MailchimpSettings;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends BaseController
{
    public function show(): JsonResponse
    {
        $tenant = (new Tenant())->getTenantById(Auth::guard('api')->user()->tenant_id);
        if ($tenant === null) {
            return $this->fail('Tenant not found.', 404);
        }

        $tenant->load('getTenantPlan');

        $tenantId = Auth::guard('api')->user()->tenant_id;

        return $this->ok([
            'tenant' => new TenantResource($tenant),
            'mailchimp' => (new MailchimpSettings())->getTenantMailChimpFirstApiKey(),
            'mailchimp_keys' => MailchimpSettings::query()->where('tenant_id', $tenantId)->get(),
            'banks' => (new Bank())->getAllTenantBanks(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'company_name' => 'required|string|max:160',
            'phone_no' => 'required|string|max:40',
            'address' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:120',
        ]);

        (new Tenant())->updateTenantDetails($request);
        $tenant = (new Tenant())->getTenantById(Auth::guard('api')->user()->tenant_id);
        $tenant->load('getTenantPlan');

        return $this->ok(new TenantResource($tenant), 'Settings updated');
    }
}
