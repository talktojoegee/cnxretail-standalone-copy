<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'mobile_no' => $this->mobile_no,
            'slug' => $this->slug,
            'account_status' => isset($this->account_status) ? (int) $this->account_status : 1,
        ];
    }
}
