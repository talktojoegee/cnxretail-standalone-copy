<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\BuyerRequestResource;
use App\Http\Resources\Api\V2\TenantResource;
use App\Support\AdminMetrics;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseController
{
    public function __invoke(AdminMetrics $metrics): JsonResponse
    {
        $data = $metrics->dashboard();

        return $this->ok([
            'stats' => $data['stats'],
            'chart' => [
                'labels' => $data['chartLabels'],
                'values' => $data['chartValues'],
            ],
            'plan_mix' => $data['planMix'],
            'recent_tenants' => TenantResource::collection($data['recentTenants']),
            'expiring_tenants' => TenantResource::collection($data['expiringTenants']),
            'recent_buyer_requests' => BuyerRequestResource::collection($data['recentBuyerRequests']),
        ]);
    }
}
