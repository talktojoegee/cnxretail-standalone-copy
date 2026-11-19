<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\AdminActivityLog;
use App\Models\Pricing;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->ok((new Pricing())->getAllPricing());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|unique:pricings,price_name',
            'amount' => 'required',
            'duration' => 'required',
        ]);

        (new Pricing())->setNewPricing($request);
        AdminActivityLog::record('Pricing', 'Added plan '.$request->name);

        return $this->created(null, 'Pricing plan created');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->merge(['price' => $id]);
        $request->validate([
            'name' => 'required',
            'amount' => 'required',
            'duration' => 'required',
            'price' => 'required|integer|exists:pricings,id',
        ]);

        (new Pricing())->editPricing($request);
        AdminActivityLog::record('Pricing', 'Updated plan '.$request->name);

        return $this->ok(Pricing::query()->find($id), 'Pricing plan updated');
    }
}
