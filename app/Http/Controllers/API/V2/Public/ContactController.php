<?php

namespace App\Http\Controllers\API\V2\Public;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\BuyerRequest;
use App\Models\ContactUs;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends BaseController
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:80',
            'email' => 'required|email|max:120',
            'subject' => 'required|string|max:160',
            'mobile_no' => 'required|string|max:30',
            'message' => 'required|string|max:2000',
        ]);

        (new ContactUs())->setNewContactUs(new Request($data));

        return $this->created(null, 'Message sent successfully');
    }

    public function buyerRequest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item' => 'required|integer|exists:items,id',
            'first_name' => 'required|string|max:80',
            'email' => 'required|email|max:120',
            'mobile_no' => 'required|string|max:30',
            'message' => 'required|string|max:1000',
        ]);

        $item = Item::query()->find($data['item']);
        if ($item === null) {
            return $this->fail('Item not found.', 404);
        }

        (new BuyerRequest())->setNewBuyerRequest(new Request($data), $item->tenant_id);

        return $this->created(null, 'Buyer request submitted');
    }
}
