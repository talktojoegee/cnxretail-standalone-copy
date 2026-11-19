<?php

namespace App\Http\Controllers\API\V2\Public;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\Item;
use App\Services\Marketplace\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends BaseController
{
    private function cart(Request $request): CartService
    {
        return CartService::fromRequestHeader($request->header('X-Cart-Token'));
    }

    private function payload(CartService $cart): array
    {
        $lines = $cart->lines()->map(fn ($line) => [
            'item' => new ItemResource($line->item),
            'qty' => $line->qty,
            'line_total' => $line->line,
        ])->values();

        return [
            'token' => $cart->token(),
            'count' => $cart->count(),
            'total' => $cart->total(),
            'lines' => $lines,
        ];
    }

    private function withToken(JsonResponse $response, CartService $cart): JsonResponse
    {
        return $response->header('X-Cart-Token', $cart->token());
    }

    public function show(Request $request): JsonResponse
    {
        $cart = $this->cart($request);

        return $this->withToken($this->ok($this->payload($cart)), $cart);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item' => 'required|integer|exists:items,id',
            'qty' => 'nullable|integer|min:1|max:99',
        ]);

        $item = Item::query()->where('id', $data['item'])->where('item_type', 1)->firstOrFail();
        $qty = (int) ($data['qty'] ?? 1);
        $max = (int) $item->quantity > 0 ? (int) $item->quantity : null;
        if ($max !== null) {
            $qty = min($qty, $max);
        }

        $cart = $this->cart($request);
        $cart->add($item->id, $qty, $max);

        return $this->withToken($this->ok($this->payload($cart), 'Item added to cart'), $cart);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item' => 'required|integer|exists:items,id',
            'qty' => 'required|integer|min:0|max:99',
        ]);

        $item = Item::query()->findOrFail($data['item']);
        $max = (int) $item->quantity > 0 ? (int) $item->quantity : null;
        $cart = $this->cart($request);
        $cart->update((int) $item->id, (int) $data['qty'], $max);

        return $this->withToken($this->ok($this->payload($cart), 'Cart updated'), $cart);
    }

    public function remove(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item' => 'required|integer',
        ]);

        $cart = $this->cart($request);
        $cart->remove((int) $data['item']);

        return $this->withToken($this->ok($this->payload($cart), 'Item removed'), $cart);
    }
}
