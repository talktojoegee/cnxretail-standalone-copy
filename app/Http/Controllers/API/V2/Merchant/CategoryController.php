<?php

namespace App\Http\Controllers\API\V2\Merchant;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends BaseController
{
    public function index(): JsonResponse
    {
        $categories = Category::query()
            ->where('tenant_id', Auth::guard('api')->user()->tenant_id)
            ->orderBy('category_name')
            ->get();

        return $this->ok(CategoryResource::collection($categories));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:120',
        ]);

        (new Category())->setNewCategory($request);
        $category = Category::query()
            ->where('tenant_id', Auth::guard('api')->user()->tenant_id)
            ->where('category_name', $request->input('category_name'))
            ->latest('id')
            ->first();

        return $this->created(new CategoryResource($category), 'Category created');
    }
}
