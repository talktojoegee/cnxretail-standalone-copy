<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $displayNet = max(0, ceil(($this->amount ?? 0) / 100) - ($this->charge ?? 0));
        $active = $this->end_date && \Carbon\Carbon::parse($this->end_date)->startOfDay()->gte(now()->startOfDay());

        return [
            'id' => $this->id,
            'tenant' => $this->whenLoaded('getTenant', fn () => $this->getTenant ? [
                'id' => $this->getTenant->id,
                'company_name' => $this->getTenant->company_name,
                'slug' => $this->getTenant->slug,
                'email' => $this->getTenant->email,
            ] : null),
            'plan' => $this->whenLoaded('getSubscriptionPlan', fn () => $this->getSubscriptionPlan ? [
                'id' => $this->getSubscriptionPlan->id,
                'name' => $this->getSubscriptionPlan->price_name,
                'price' => (float) $this->getSubscriptionPlan->price,
                'duration' => (int) $this->getSubscriptionPlan->duration,
            ] : null),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $active ? 'active' : 'expired',
            'sub_key' => $this->sub_key,
            'gross' => (float) ($displayNet + ($this->charge ?? 0)),
            'charge' => (float) ($this->charge ?? 0),
            'net' => (float) $displayNet,
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}
