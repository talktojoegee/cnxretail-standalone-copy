<?php

namespace App\Http\Controllers\API\V2\Public;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tenant;
use App\Services\Marketplace\CatalogQuery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MarketplaceController extends BaseController
{
    public function __construct(private CatalogQuery $catalog)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'keyword' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:120',
            'vendor' => 'nullable|string|max:160',
            'sort' => 'nullable|in:newest,price_asc,price_desc,name',
            'per_page' => 'nullable|integer|min:1|max:48',
        ]);

        $filtered = $request->hasAny(['keyword', 'category', 'vendor', 'sort']);
        $slides = ! $filtered && (int) $request->input('page', 1) === 1
            ? $this->catalog->products(new Request())->whereHas('getItemGalleryImages')->latest()->take(4)->get()
            : collect();

        $paginator = $this->catalog->products($request)
            ->paginate((int) $request->input('per_page', 12))
            ->withQueryString();

        return $this->ok([
            'items' => ItemResource::collection($paginator->getCollection()),
            'slides' => ItemResource::collection($slides),
            'heading' => 'Products',
        ], 'OK', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    public function facets(): JsonResponse
    {
        $facets = $this->catalog->facets();

        return $this->ok([
            'categories' => $facets['categories']->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->category_name,
                'slug' => $c->slug,
            ])->values(),
            'vendors' => $facets['vendors']->map(fn ($t) => [
                'id' => $t->id,
                'company_name' => $t->company_name,
                'slug' => $t->slug,
                'email' => $t->email,
                'products_count' => (int) $t->products_count,
            ])->values(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $item = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('slug', $slug)
            ->whereIn('item_type', [1, 2])
            ->first();

        if ($item === null) {
            return $this->fail('Product not found.', 404);
        }

        $related = Item::query()
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->where('item_type', $item->item_type)
            ->where('id', '!=', $item->id)
            ->when($item->category_id, fn ($q) => $q->where('category_id', $item->category_id))
            ->latest()
            ->take(4)
            ->get();

        return $this->ok([
            'item' => new ItemResource($item),
            'related' => ItemResource::collection($related),
        ]);
    }

    public function store(Request $request, string $slug): JsonResponse
    {
        $vendor = Tenant::query()->where('slug', $slug)->first();
        if ($vendor === null) {
            return $this->fail('Store not found.', 404);
        }

        $request->merge(['vendor' => $vendor->slug]);
        $paginator = $this->catalog->products($request)
            ->paginate((int) $request->input('per_page', 12))
            ->withQueryString();

        return $this->ok([
            'vendor' => [
                'id' => $vendor->id,
                'company_name' => $vendor->company_name,
                'slug' => $vendor->slug,
                'email' => $vendor->email,
                'phone_no' => $vendor->phone_no,
            ],
            'items' => ItemResource::collection($paginator->getCollection()),
        ], 'OK', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    public function category(Request $request, string $slug): JsonResponse
    {
        $category = Category::query()->where('slug', $slug)->first();
        if ($category === null) {
            return $this->fail('Category not found.', 404);
        }

        $request->merge(['category' => $category->slug]);
        $paginator = $this->catalog->products($request)
            ->paginate((int) $request->input('per_page', 12))
            ->withQueryString();

        return $this->ok([
            'category' => [
                'id' => $category->id,
                'name' => $category->category_name,
                'slug' => $category->slug,
            ],
            'items' => ItemResource::collection($paginator->getCollection()),
        ], 'OK', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'keyword' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:120',
            'vendor' => 'nullable|string|max:160',
            'sort' => 'nullable|in:newest,price_asc,price_desc,name',
            'per_page' => 'nullable|integer|min:1|max:48',
        ]);

        $paginator = $this->catalog->products($request)
            ->paginate((int) $request->input('per_page', 12))
            ->withQueryString();

        return $this->ok([
            'heading' => $request->filled('keyword')
                ? 'Results for “'.$request->input('keyword').'”'
                : 'Products',
            'items' => ItemResource::collection($paginator->getCollection()),
        ], 'OK', 200, [
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
        ]);
    }
}
