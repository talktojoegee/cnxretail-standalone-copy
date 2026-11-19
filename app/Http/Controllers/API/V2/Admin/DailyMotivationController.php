<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\DailyMotivation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyMotivationController extends BaseController
{
    public function index(): JsonResponse
    {
        return $this->ok((new DailyMotivation())->getAllDailyMotivations());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'time' => 'required|integer|in:1,3',
            'author' => 'required|string|max:120',
            'motivation' => 'required|string|max:2000',
        ]);

        (new DailyMotivation())->setNewDailyMotivation($request);
        $row = DailyMotivation::query()->latest('id')->first();

        return $this->created($row, 'Daily motivation added');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $request->merge(['daily' => $id]);
        $request->validate([
            'time' => 'required|integer|in:1,3',
            'author' => 'required|string|max:120',
            'motivation' => 'required|string|max:2000',
            'daily' => 'required|integer|exists:daily_motivations,id',
        ]);

        (new DailyMotivation())->editDailyMotivation($request);

        return $this->ok(DailyMotivation::query()->find($id), 'Daily motivation updated');
    }
}
