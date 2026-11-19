<?php

namespace App\Services\Marketplace;

use App\Models\Category;
use App\Models\Item;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogQuery
{
    public function products(Request $request): Builder
    {
        $query = Item::query()
            ->where('item_type', 1)
            ->with(['getCategory', 'getTenant', 'getItemGalleryImages']);

        if ($request->filled('keyword')) {
            $term = '%'.$request->input('keyword').'%';
            $query->where(function (Builder $builder) use ($term) {
                $builder->where('item_name', 'like', $term)
                    ->orWhere('description', 'like', $term);
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('getCategory', function (Builder $builder) use ($request) {
                $builder->where('slug', $request->input('category'));
            });
        }

        if ($request->filled('vendor')) {
            $query->whereHas('getTenant', function (Builder $builder) use ($request) {
                $builder->where('slug', $request->input('vendor'));
            });
        }

        return match ($request->input('sort')) {
            'price_asc' => $query->orderBy('selling_price'),
            'price_desc' => $query->orderByDesc('selling_price'),
            'name' => $query->orderBy('item_name'),
            default => $query->latest(),
        };
    }

    public function topGroups(Builder $items, string $column, string $model): Collection
    {
        $ids = (clone $items)
            ->select($column)
            ->selectRaw('count(*) as aggregate')
            ->groupBy($column)
            ->orderByDesc('aggregate')
            ->limit(4)
            ->pluck($column);

        return $model::query()
            ->whereIn('id', $ids)
            ->get()
            ->sortBy(fn ($row) => $ids->search($row->id))
            ->values();
    }

    public function facets(): array
    {
        return [
            'categories' => Category::query()->orderBy('category_name')->get(['id', 'category_name', 'slug']),
            'vendors' => Tenant::query()
                ->where('account_status', 1)
                ->withCount(['products as products_count' => fn (Builder $q) => $q->where('item_type', 1)])
                ->having('products_count', '>', 0)
                ->orderBy('company_name')
                ->get(['id', 'company_name', 'slug', 'email']),
        ];
    }
}
