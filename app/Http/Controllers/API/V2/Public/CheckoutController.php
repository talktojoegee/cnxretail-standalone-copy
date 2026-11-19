<?php

namespace App\Http\Controllers\API\V2\Public;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\BuyerRequest;
use App\Services\Marketplace\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends BaseController
{
    public function show(Request $request): JsonResponse
    {
        $cart = CartService::fromRequestHeader($request->header('X-Cart-Token'));
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return $this->fail('Your cart is empty.', 422)->header('X-Cart-Token', $cart->token());
        }

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => [
                'token' => $cart->token(),
                'total' => $cart->total(),
                'lines' => $lines->map(fn ($line) => [
                    'item' => (new ItemResource($line->item))->resolve(),
                    'qty' => $line->qty,
                    'line_total' => $line->line,
                ])->values(),
            ],
        ])->header('X-Cart-Token', $cart->token());
    }

    public function place(Request $request): JsonResponse
    {
        $cart = CartService::fromRequestHeader($request->header('X-Cart-Token'));
        $lines = $cart->lines();

        if ($lines->isEmpty()) {
            return $this->fail('Your cart is empty.', 422)->header('X-Cart-Token', $cart->token());
        }

        $data = $request->validate([
            'first_name' => 'required|string|max:80',
            'email' => 'required|email|max:120',
            'mobile_no' => 'required|string|max:30',
            'address' => 'required|string|max:180',
            'note' => 'nullable|string|max:180',
        ]);

        $reference = 'CNX-'.strtoupper(Str::random(6));
        $buyer = new BuyerRequest();

        foreach ($lines as $line) {
            $payload = new Request([
                'item' => $line->item->id,
                'first_name' => $data['first_name'],
                'email' => $data['email'],
                'mobile_no' => $data['mobile_no'],
                'message' => Str::limit(
                    $reference.' · Qty '.$line->qty.' · Deliver to '.$data['address'].($data['note'] ? ' · '.$data['note'] : ''),
                    240
                ),
            ]);
            $buyer->setNewBuyerRequest($payload, $line->item->tenant_id);
        }

        $cart->clear();

        return response()->json([
            'success' => true,
            'message' => 'Order submitted to sellers.',
            'data' => [
                'reference' => $reference,
            ],
        ], 201)->header('X-Cart-Token', $cart->token());
    }
}
