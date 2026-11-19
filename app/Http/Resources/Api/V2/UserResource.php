<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? trim(($this->first_name ?? '').' '.($this->surname ?? '')),
            'first_name' => $this->first_name ?? null,
            'surname' => $this->surname ?? null,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no ?? null,
            'slug' => $this->slug ?? null,
            'tenant_id' => $this->tenant_id ?? null,
            'account_status' => isset($this->account_status) ? (int) $this->account_status : null,
            'avatar_url' => ! empty($this->avatar) ? url('/assets/drive/'.$this->avatar) : null,
            'tenant' => $this->whenLoaded('getTenant', fn () => $this->getTenant
                ? new TenantResource($this->getTenant)
                : null),
        ];
    }
}
