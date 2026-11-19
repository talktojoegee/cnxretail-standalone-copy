<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\BuyerRequestResource;
use App\Http\Resources\Api\V2\TenantResource;
use App\Models\AdminActivityLog;
use App\Models\BuyerRequest;
use App\Models\Pricing;
use App\Models\Tenant;
use App\Support\AdminMetrics;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TenantController extends BaseController
{
    public function __construct(private AdminMetrics $metrics)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'status' => 'nullable|in:enabled,suspended,subscribed,expired,expiring',
            'plan' => 'nullable|integer|exists:pricings,id',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $scoped = $this->scoped($request);

        $tenants = (clone $scoped)
            ->select([
                'id', 'company_name', 'email', 'phone_no', 'slug', 'plan_id',
                'account_status', 'start_date', 'end_date', 'created_at', 'address',
            ])
            ->with('getTenantPlan:id,price_name,price,duration')
            ->withCount([
                'products as products_count' => fn (Builder $query) => $query->where('item_type', 1),
                'products as services_count' => fn (Builder $query) => $query->where('item_type', 2),
                'users as users_count',
                'buyerRequests as buyer_requests_count',
            ])
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return $this->ok([
            'tenants' => TenantResource::collection($tenants->getCollection()),
            'stats' => $this->metrics->tenantSummary(clone $scoped),
            'plans' => Cache::remember('admin.pricing.options', 300, fn () => Pricing::query()->orderBy('duration')->get(['id', 'price_name', 'price', 'duration'])),
        ], 'OK', 200, [
            'current_page' => $tenants->currentPage(),
            'per_page' => $tenants->perPage(),
            'total' => $tenants->total(),
            'last_page' => $tenants->lastPage(),
            'from' => $tenants->firstItem(),
            'to' => $tenants->lastItem(),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $tenant = Tenant::query()
            ->with(['getTenantPlan', 'getTenantSubscriptions.getSubscriptionPlan'])
            ->withCount([
                'products as products_count' => fn (Builder $query) => $query->where('item_type', 1),
                'products as services_count' => fn (Builder $query) => $query->where('item_type', 2),
                'users as users_count',
                'buyerRequests as buyer_requests_count',
            ])
            ->where('slug', $slug)
            ->first();

        if ($tenant === null) {
            return $this->fail('Tenant not found.', 404);
        }

        $recentRequests = BuyerRequest::query()
            ->with('item:id,item_name,slug,selling_price,item_type')
            ->where('tenant_id', $tenant->id)
            ->latest('id')
            ->limit(8)
            ->get();

        return $this->ok([
            'tenant' => new TenantResource($tenant),
            'subscriptions' => $tenant->getTenantSubscriptions,
            'recent_buyer_requests' => BuyerRequestResource::collection($recentRequests),
        ]);
    }

    public function updateStatus(Request $request, string $slug): JsonResponse
    {
        $data = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $tenant = (new Tenant())->getTenantBySlug($slug);
        if (empty($tenant)) {
            return $this->fail('Tenant not found.', 404);
        }

        $tenant->setAccountStatus((int) $data['status']);
        AdminMetrics::forgetDashboard();
        AdminActivityLog::record(
            'Tenant status',
            ($data['status'] == 1 ? 'Enabled' : 'Suspended').' tenant '.$tenant->company_name
        );

        return $this->ok(new TenantResource($tenant->fresh('getTenantPlan')), 'Tenant status updated');
    }

    public function extend(Request $request, string $slug): JsonResponse
    {
        $data = $request->validate([
            'days' => 'required|integer|in:7,14,30,90,365',
        ]);

        $tenant = (new Tenant())->getTenantBySlug($slug);
        if (empty($tenant)) {
            return $this->fail('Tenant not found.', 404);
        }

        $tenant->extendSubscription((int) $data['days']);
        AdminMetrics::forgetDashboard();
        AdminActivityLog::record(
            'Subscription',
            'Extended '.$tenant->company_name.' by '.$data['days'].' days to '.$tenant->end_date
        );

        return $this->ok(new TenantResource($tenant->fresh('getTenantPlan')), 'Subscription extended');
    }

    private function scoped(Request $request): Builder
    {
        $today = Carbon::today();
        $expiringUntil = $today->copy()->addDays(14)->endOfDay();

        return Tenant::query()
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('company_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone_no', 'like', $term);
                });
            })
            ->when($request->filled('plan'), fn (Builder $builder) => $builder->where('plan_id', $request->input('plan')))
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()))
            ->when($request->input('status') === 'enabled', fn (Builder $builder) => $builder->where('account_status', 1))
            ->when($request->input('status') === 'suspended', fn (Builder $builder) => $builder->where('account_status', 0))
            ->when($request->input('status') === 'subscribed', fn (Builder $builder) => $builder->where('end_date', '>=', $today))
            ->when($request->input('status') === 'expired', fn (Builder $builder) => $builder->where('end_date', '<', $today))
            ->when($request->input('status') === 'expiring', function (Builder $builder) use ($today, $expiringUntil) {
                $builder->where('end_date', '>=', $today)->where('end_date', '<=', $expiringUntil);
            });
    }
}
