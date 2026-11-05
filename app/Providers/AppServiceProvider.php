<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Tenant;
use App\Support\MarketplaceCart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFour();

        View::composer([
            'layouts.marketplace-layout',
            'frontend.marketplace',
            'frontend.view-item',
            'frontend.cart',
            'frontend.checkout',
            'partials.marketplace.*',
        ], function ($view) {
            static $shared;
            if ($shared === null) {
                $shared = [
                    'marketCategories' => Category::query()->orderBy('category_name')->get(),
                    'marketVendors' => Tenant::query()
                        ->where('account_status', 1)
                        ->whereHas('products', function ($query) {
                            $query->where('item_type', 1);
                        })
                        ->withCount(['products' => function ($query) {
                            $query->where('item_type', 1);
                        }])
                        ->orderByDesc('products_count')
                        ->orderBy('company_name')
                        ->get(),
                    'cartCount' => app(MarketplaceCart::class)->count(),
                ];
            }
            $view->with($shared);
        });
    }
}
