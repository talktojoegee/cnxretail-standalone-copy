<?php

namespace App\Http\Resources\Api\V2;

use App\Support\AdminMetrics;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $plan = $this->relationLoaded('getTenantPlan') ? $this->getTenantPlan : null;
        $subscribed = method_exists($this->resource, 'isSubscriptionActive')
            ? $this->resource->isSubscriptionActive()
            : ($this->end_date && now()->startOfDay()->lte($this->end_date));

        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'slug' => $this->slug,
            'address' => $this->address ?? null,
            'account_status' => (int) $this->account_status,
            'start_date' => $this->formatDate($this->start_date),
            'end_date' => $this->formatDate($this->end_date),
            'subscribed' => (bool) $subscribed,
            'plan' => $plan ? [
                'id' => $plan->id,
                'name' => $plan->price_name,
                'price' => (float) $plan->price,
                'duration' => (int) $plan->duration,
                'mrr' => AdminMetrics::monthlyValue($plan->price, $plan->duration),
            ] : null,
            'products_count' => $this->when(isset($this->products_count), (int) $this->products_count),
            'services_count' => $this->when(isset($this->services_count), (int) $this->services_count),
            'users_count' => $this->when(isset($this->users_count), (int) $this->users_count),
            'buyer_requests_count' => $this->when(isset($this->buyer_requests_count), (int) $this->buyer_requests_count),
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }

    private function formatDate(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        try {
            return \Carbon\Carbon::parse($value)->toDateString();
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }
}
