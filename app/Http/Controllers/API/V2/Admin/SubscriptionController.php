<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\SubscriptionResource;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'status' => 'nullable|in:active,expired',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $today = Carbon::today();
        $query = Subscription::query()
            ->with([
                'getTenant:id,company_name,slug,email,phone_no',
                'getSubscriptionPlan:id,price_name,price,duration',
            ])
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->whereHas('getTenant', function (Builder $tenant) use ($term) {
                    $tenant->where('company_name', 'like', $term)->orWhere('email', 'like', $term);
                });
            })
            ->when($request->input('status') === 'active', fn (Builder $builder) => $builder->where('end_date', '>=', $today))
            ->when($request->input('status') === 'expired', fn (Builder $builder) => $builder->where('end_date', '<', $today))
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->aggregate($query, 'COUNT(*) as total,
             COALESCE(SUM(GREATEST(amount - charge, 0) / 100), 0) as revenue,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN end_date >= ? THEN 1 ELSE 0 END) as active,
             SUM(CASE WHEN end_date < ? THEN 1 ELSE 0 END) as expired', [
            Carbon::now()->startOfMonth(), $today, $today,
        ]);

        $subscriptions = (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString();

        return $this->ok([
            'subscriptions' => SubscriptionResource::collection($subscriptions->getCollection()),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'revenue' => (float) ($summary->revenue ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'active' => (int) ($summary->active ?? 0),
                'expired' => (int) ($summary->expired ?? 0),
            ],
        ], 'OK', 200, [
            'current_page' => $subscriptions->currentPage(),
            'per_page' => $subscriptions->perPage(),
            'total' => $subscriptions->total(),
            'last_page' => $subscriptions->lastPage(),
            'from' => $subscriptions->firstItem(),
            'to' => $subscriptions->lastItem(),
        ]);
    }

    private function aggregate(Builder $query, string $expression, array $bindings = []): object
    {
        $base = (clone $query)->toBase();
        $base->columns = null;
        $base->bindings['select'] = [];

        return $base->selectRaw($expression, $bindings)->first() ?? (object) [];
    }
}
