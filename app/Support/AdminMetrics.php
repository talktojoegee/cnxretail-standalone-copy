<?php

namespace App\Support;

use App\Models\BuyerRequest;
use App\Models\ContactUs;
use App\Models\Item;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class AdminMetrics
{
    public function dashboard(): array
    {
        return Cache::remember('admin.dashboard.metrics', 60, function () {
            $today = Carbon::today();
            $monthStart = Carbon::now()->startOfMonth();
            $weekStart = Carbon::now()->startOfWeek();
            $yearStart = Carbon::now()->startOfYear();
            $expiringUntil = $today->copy()->addDays(14)->endOfDay();

            $tenantStats = Tenant::query()
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('SUM(CASE WHEN account_status = 1 THEN 1 ELSE 0 END) as enabled')
                ->selectRaw('SUM(CASE WHEN account_status = 0 THEN 1 ELSE 0 END) as suspended')
                ->selectRaw('SUM(CASE WHEN end_date IS NOT NULL AND end_date >= ? THEN 1 ELSE 0 END) as subscribed', [$today])
                ->selectRaw('SUM(CASE WHEN end_date IS NOT NULL AND end_date < ? THEN 1 ELSE 0 END) as expired', [$today])
                ->selectRaw('SUM(CASE WHEN end_date IS NOT NULL AND end_date >= ? AND end_date <= ? THEN 1 ELSE 0 END) as expiring', [$today, $expiringUntil])
                ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month', [$monthStart])
                ->selectRaw('SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_week', [$weekStart])
                ->first();

            $mrr = (float) Tenant::query()
                ->join('pricings', 'tenants.plan_id', '=', 'pricings.id')
                ->where('tenants.end_date', '>=', $today)
                ->where('pricings.price', '>', 0)
                ->selectRaw('COALESCE(SUM(CASE
                    WHEN pricings.duration >= 360 THEN pricings.price / 12
                    WHEN pricings.duration >= 80 THEN pricings.price / 3
                    ELSE pricings.price
                END), 0) as mrr')
                ->value('mrr');

            $catalog = Item::query()
                ->selectRaw('SUM(CASE WHEN item_type = 1 THEN 1 ELSE 0 END) as products')
                ->selectRaw('SUM(CASE WHEN item_type = 2 THEN 1 ELSE 0 END) as services')
                ->first();

            $registrations = Tenant::query()
                ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as period, COUNT(*) as total")
                ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
                ->groupBy('period')
                ->orderBy('period')
                ->pluck('total', 'period');

            $chartLabels = [];
            $chartValues = [];
            for ($i = 5; $i >= 0; $i--) {
                $period = Carbon::now()->subMonths($i)->format('Y-m');
                $chartLabels[] = Carbon::now()->subMonths($i)->format('M Y');
                $chartValues[] = (int) ($registrations[$period] ?? 0);
            }

            return [
                'stats' => [
                    'tenants' => (int) ($tenantStats->total ?? 0),
                    'enabled' => (int) ($tenantStats->enabled ?? 0),
                    'suspended' => (int) ($tenantStats->suspended ?? 0),
                    'subscribed' => (int) ($tenantStats->subscribed ?? 0),
                    'expired' => (int) ($tenantStats->expired ?? 0),
                    'expiring' => (int) ($tenantStats->expiring ?? 0),
                    'this_month' => (int) ($tenantStats->this_month ?? 0),
                    'this_week' => (int) ($tenantStats->this_week ?? 0),
                    'mrr' => $mrr,
                    'subscription_revenue' => (float) Subscription::query()
                        ->where('created_at', '>=', $yearStart)
                        ->selectRaw('COALESCE(SUM(GREATEST(amount - charge, 0) / 100), 0) as revenue')
                        ->value('revenue'),
                    'users' => User::query()->count(),
                    'products' => (int) ($catalog->products ?? 0),
                    'services' => (int) ($catalog->services ?? 0),
                    'buyer_requests' => BuyerRequest::query()->count(),
                    'contact_messages' => ContactUs::query()->count(),
                ],
                'recentTenants' => Tenant::query()
                    ->select(['id', 'company_name', 'email', 'slug', 'plan_id', 'account_status', 'start_date', 'end_date', 'created_at'])
                    ->with('getTenantPlan:id,price_name,price,duration')
                    ->latest('id')
                    ->limit(8)
                    ->get(),
                'expiringTenants' => Tenant::query()
                    ->select(['id', 'company_name', 'slug', 'plan_id', 'end_date', 'account_status'])
                    ->with('getTenantPlan:id,price_name')
                    ->where('end_date', '>=', $today)
                    ->where('end_date', '<=', $expiringUntil)
                    ->orderBy('end_date')
                    ->limit(10)
                    ->get(),
                'recentBuyerRequests' => BuyerRequest::query()
                    ->select(['id', 'first_name', 'email', 'created_at'])
                    ->latest('id')
                    ->limit(6)
                    ->get(),
                'chartLabels' => $chartLabels,
                'chartValues' => $chartValues,
                'planMix' => Tenant::query()
                    ->leftJoin('pricings', 'tenants.plan_id', '=', 'pricings.id')
                    ->selectRaw("COALESCE(pricings.price_name, 'No plan') as label, COUNT(*) as total")
                    ->groupBy('label')
                    ->orderByDesc('total')
                    ->limit(6)
                    ->get(),
            ];
        });
    }

    public function tenantSummary(Builder $query): array
    {
        $today = Carbon::today();
        $expiringUntil = $today->copy()->addDays(14)->endOfDay();
        $monthStart = Carbon::now()->startOfMonth();
        $weekStart = Carbon::now()->startOfWeek();

        $base = (clone $query)->toBase();
        $base->columns = null;
        $base->bindings['select'] = [];

        $row = $base->selectRaw(
            'COUNT(*) as total,
             SUM(CASE WHEN account_status = 1 THEN 1 ELSE 0 END) as enabled,
             SUM(CASE WHEN account_status = 0 THEN 1 ELSE 0 END) as suspended,
             SUM(CASE WHEN end_date IS NOT NULL AND end_date >= ? THEN 1 ELSE 0 END) as subscribed,
             SUM(CASE WHEN end_date IS NOT NULL AND end_date < ? THEN 1 ELSE 0 END) as expired,
             SUM(CASE WHEN end_date IS NOT NULL AND end_date >= ? AND end_date <= ? THEN 1 ELSE 0 END) as expiring,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_week',
            [$today, $today, $today, $expiringUntil, $monthStart, $weekStart]
        )->first();

        return [
            'total' => (int) ($row->total ?? 0),
            'enabled' => (int) ($row->enabled ?? 0),
            'suspended' => (int) ($row->suspended ?? 0),
            'subscribed' => (int) ($row->subscribed ?? 0),
            'expired' => (int) ($row->expired ?? 0),
            'expiring' => (int) ($row->expiring ?? 0),
            'this_month' => (int) ($row->this_month ?? 0),
            'this_week' => (int) ($row->this_week ?? 0),
        ];
    }

    public static function forgetDashboard(): void
    {
        Cache::forget('admin.dashboard.metrics');
    }

    public static function monthlyValue(?float $price, ?int $duration): float
    {
        $price = (float) $price;
        $duration = (int) $duration;
        if ($price <= 0) {
            return 0;
        }
        if ($duration >= 360) {
            return $price / 12;
        }
        if ($duration >= 80) {
            return $price / 3;
        }

        return $price;
    }
}
