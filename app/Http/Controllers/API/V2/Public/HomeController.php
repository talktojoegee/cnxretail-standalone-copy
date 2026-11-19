<?php

namespace App\Http\Controllers\API\V2\Public;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\ItemResource;
use App\Models\Category;
use App\Models\Item;
use App\Models\Tenant;
use App\Services\Marketplace\CatalogQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function __construct(private CatalogQuery $catalog)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $productCategories = $this->catalog->topGroups(
            Item::query()->where('item_type', 1)->whereNotNull('category_id'),
            'category_id',
            Category::class
        );
        $serviceSellers = $this->catalog->topGroups(
            Item::query()->where('item_type', 2),
            'tenant_id',
            Tenant::class
        );

        $activeProduct = $productCategories->firstWhere('slug', $request->input('products'));
        $activeSeller = $serviceSellers->firstWhere('slug', $request->input('services'));

        $products = Item::query()
            ->where('item_type', 1)
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages'])
            ->when($activeProduct, fn (Builder $q) => $q->where('category_id', $activeProduct->id))
            ->inRandomOrder()
            ->limit(8)
            ->get();

        $services = Item::query()
            ->where('item_type', 2)
            ->with(['getTenant', 'getItemGalleryImages'])
            ->when($activeSeller, fn (Builder $q) => $q->where('tenant_id', $activeSeller->id))
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return $this->ok([
            'products' => ItemResource::collection($products),
            'services' => ItemResource::collection($services),
            'product_categories' => $productCategories->map(fn ($c) => [
                'id' => $c->id,
                'name' => $c->category_name,
                'slug' => $c->slug,
            ])->values(),
            'service_sellers' => $serviceSellers->map(fn ($t) => [
                'id' => $t->id,
                'company_name' => $t->company_name,
                'slug' => $t->slug,
            ])->values(),
            'active_product_category' => $activeProduct?->slug,
            'active_service_seller' => $activeSeller?->slug,
        ]);
    }
}
