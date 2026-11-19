<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyerRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'phone_no' => $this->phone_no,
            'message' => $this->message,
            'seller' => $this->whenLoaded('tenant', fn () => $this->tenant ? [
                'id' => $this->tenant->id,
                'company_name' => $this->tenant->company_name,
                'slug' => $this->tenant->slug,
                'email' => $this->tenant->email,
            ] : null),
            'item' => $this->whenLoaded('item', fn () => $this->item ? [
                'id' => $this->item->id,
                'name' => $this->item->item_name,
                'slug' => $this->item->slug,
                'selling_price' => (float) ($this->item->selling_price ?? 0),
                'item_type' => (int) ($this->item->item_type ?? 1),
            ] : null),
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}
