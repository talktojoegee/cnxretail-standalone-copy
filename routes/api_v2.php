<?php

use App\Http\Controllers\API\V2\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\API\V2\Admin\AdminUserController;
use App\Http\Controllers\API\V2\Admin\BuyerRequestController as AdminBuyerRequestController;
use App\Http\Controllers\API\V2\Admin\ContactInquiryController;
use App\Http\Controllers\API\V2\Admin\DailyMotivationController;
use App\Http\Controllers\API\V2\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\API\V2\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\API\V2\Admin\PricingController;
use App\Http\Controllers\API\V2\Admin\SubscriptionController as AdminSubscriptionController;
use App\Http\Controllers\API\V2\Admin\TenantController as AdminTenantController;
use App\Http\Controllers\API\V2\Auth\AdminAuthController;
use App\Http\Controllers\API\V2\Auth\MerchantAuthController;
use App\Http\Controllers\API\V2\Merchant\CategoryController as MerchantCategoryController;
use App\Http\Controllers\API\V2\Merchant\DashboardController as MerchantDashboardController;
use App\Http\Controllers\API\V2\Merchant\ItemController as MerchantItemController;
use App\Http\Controllers\API\V2\Merchant\SettingsController as MerchantSettingsController;
use App\Http\Controllers\API\V2\Public\CartController;
use App\Http\Controllers\API\V2\Public\CheckoutController;
use App\Http\Controllers\API\V2\Public\ContactController;
use App\Http\Controllers\API\V2\Public\HomeController;
use App\Http\Controllers\API\V2\Public\MarketplaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API v2 Routes (Next.js ready)
|--------------------------------------------------------------------------
|
| Prefixed with /api/v2 by RouteServiceProvider. Legacy /api/* stays in api.php.
|
*/

Route::prefix('public')->middleware('throttle:api-v2-public')->group(function () {
    Route::get('/home', HomeController::class);
    Route::get('/marketplace', [MarketplaceController::class, 'index']);
    Route::get('/marketplace/facets', [MarketplaceController::class, 'facets']);
    Route::get('/products/{slug}', [MarketplaceController::class, 'show']);
    Route::get('/stores/{slug}', [MarketplaceController::class, 'store']);
    Route::get('/categories/{slug}', [MarketplaceController::class, 'category']);
    Route::get('/search', [MarketplaceController::class, 'search']);

    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart/items', [CartController::class, 'add']);
    Route::patch('/cart/items', [CartController::class, 'update']);
    Route::delete('/cart/items', [CartController::class, 'remove']);

    Route::get('/checkout', [CheckoutController::class, 'show']);
    Route::post('/checkout', [CheckoutController::class, 'place']);

    Route::post('/contact', [ContactController::class, 'store']);
    Route::post('/buyer-requests', [ContactController::class, 'buyerRequest']);
});

Route::prefix('auth')->middleware('throttle:api-v2-auth')->group(function () {
    Route::post('/register', [MerchantAuthController::class, 'register']);
    Route::post('/login', [MerchantAuthController::class, 'login']);

    Route::middleware(['auth:api', 'jwt.typ:merchant'])->group(function () {
        Route::get('/me', [MerchantAuthController::class, 'me']);
        Route::post('/logout', [MerchantAuthController::class, 'logout']);
        Route::post('/refresh', [MerchantAuthController::class, 'refresh']);
    });
});

Route::prefix('merchant')->middleware(['auth:api', 'jwt.typ:merchant', 'throttle:api'])->group(function () {
    Route::get('/dashboard', MerchantDashboardController::class);
    Route::get('/items', [MerchantItemController::class, 'index']);
    Route::post('/items', [MerchantItemController::class, 'store']);
    Route::get('/items/{id}', [MerchantItemController::class, 'show'])->whereNumber('id');
    Route::get('/categories', [MerchantCategoryController::class, 'index']);
    Route::post('/categories', [MerchantCategoryController::class, 'store']);
    Route::get('/settings/tenant', [MerchantSettingsController::class, 'show']);
    Route::patch('/settings/tenant', [MerchantSettingsController::class, 'update']);
});

Route::prefix('admin/auth')->middleware('throttle:api-v2-auth')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware(['auth:admin_api', 'jwt.typ:admin'])->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });
});

Route::prefix('admin')->middleware(['auth:admin_api', 'jwt.typ:admin', 'throttle:api'])->group(function () {
    Route::get('/dashboard', AdminDashboardController::class);

    Route::get('/tenants', [AdminTenantController::class, 'index']);
    Route::get('/tenants/{slug}', [AdminTenantController::class, 'show']);
    Route::patch('/tenants/{slug}/status', [AdminTenantController::class, 'updateStatus']);
    Route::post('/tenants/{slug}/extend', [AdminTenantController::class, 'extend']);

    Route::get('/subscriptions', [AdminSubscriptionController::class, 'index']);
    Route::get('/buyer-requests', [AdminBuyerRequestController::class, 'index']);
    Route::get('/contact-inquiries', [ContactInquiryController::class, 'index']);
    Route::get('/activity-logs', [AdminActivityLogController::class, 'index']);

    Route::get('/pricing', [PricingController::class, 'index']);
    Route::post('/pricing', [PricingController::class, 'store']);
    Route::patch('/pricing/{id}', [PricingController::class, 'update'])->whereNumber('id');

    Route::get('/daily-motivations', [DailyMotivationController::class, 'index']);
    Route::post('/daily-motivations', [DailyMotivationController::class, 'store']);
    Route::patch('/daily-motivations/{id}', [DailyMotivationController::class, 'update'])->whereNumber('id');

    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users', [AdminUserController::class, 'store']);

    Route::get('/notifications', [AdminNotificationController::class, 'index']);
});
