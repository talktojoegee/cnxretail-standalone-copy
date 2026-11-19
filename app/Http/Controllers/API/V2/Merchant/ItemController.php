<?php

namespace App\Http\Controllers\API\V2\Merchant;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends BaseController
{
    public function index(): JsonResponse
    {
        $items = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('tenant_id', Auth::guard('api')->user()->tenant_id)
            ->latest('id')
            ->get();

        return $this->ok(ItemResource::collection($items));
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('tenant_id', Auth::guard('api')->user()->tenant_id)
            ->where('id', $id)
            ->first();

        if ($item === null) {
            return $this->fail('Item not found.', 404);
        }

        return $this->ok(new ItemResource($item));
    }

    public function store(Request $request): JsonResponse
    {
        if ((int) $request->input('item_type') === 1) {
            $data = $request->validate([
                'item_name' => 'required|string|max:160',
                'category' => 'required',
                'quantity' => 'required|integer|min:0',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0',
                'attachments' => 'required',
                'attachments.*' => 'required',
                'description' => 'nullable|string',
            ]);
        } else {
            $data = $request->validate([
                'item_name' => 'required|string|max:160',
                'service_fee' => 'required|numeric|min:0',
                'description' => 'nullable|string',
            ]);
        }

        (new Item())->setNewItem($request);
        $item = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('tenant_id', Auth::guard('api')->user()->tenant_id)
            ->latest('id')
            ->first();

        return $this->created(new ItemResource($item), 'Item created');
    }
}
