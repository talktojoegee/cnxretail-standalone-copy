<?php

namespace App\Http\Resources\Api\V2;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $gallery = $this->whenLoaded('getItemGalleryImages', function () {
            return $this->getItemGalleryImages->map(fn ($image) => [
                'id' => $image->id,
                'url' => url('/assets/drive/'.$image->attachment),
                'attachment' => $image->attachment,
            ])->values();
        });

        return [
            'id' => $this->id,
            'name' => $this->item_name,
            'slug' => $this->slug,
            'description' => $this->description,
            'selling_price' => (float) $this->selling_price,
            'cost_price' => isset($this->cost_price) ? (float) $this->cost_price : null,
            'quantity' => (int) ($this->quantity ?? 0),
            'item_type' => (int) $this->item_type,
            'item_type_label' => (int) $this->item_type === 2 ? 'service' : 'product',
            'category' => $this->whenLoaded('getCategory', fn () => $this->getCategory ? [
                'id' => $this->getCategory->id,
                'name' => $this->getCategory->category_name,
                'slug' => $this->getCategory->slug,
            ] : null),
            'vendor' => $this->whenLoaded('getTenant', fn () => $this->getTenant ? [
                'id' => $this->getTenant->id,
                'company_name' => $this->getTenant->company_name,
                'slug' => $this->getTenant->slug,
                'email' => $this->getTenant->email,
                'phone_no' => $this->getTenant->phone_no,
            ] : null),
            'gallery' => $gallery,
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}
