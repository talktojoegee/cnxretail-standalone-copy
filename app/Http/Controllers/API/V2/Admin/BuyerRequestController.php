<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Http\Resources\Api\V2\BuyerRequestResource;
use App\Models\BuyerRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuyerRequestController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'seller' => 'nullable|string|max:160',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $todayStart = Carbon::today();
        $query = BuyerRequest::query()
            ->with([
                'tenant:id,company_name,slug,email',
                'item:id,item_name,slug,selling_price,item_type',
            ])
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone_no', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->when($request->filled('seller'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('seller').'%';
                $builder->whereHas('tenant', function (Builder $tenant) use ($term) {
                    $tenant->where('company_name', 'like', $term)->orWhere('email', 'like', $term);
                });
            })
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->aggregate($query, 'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today,
             COUNT(DISTINCT tenant_id) as sellers,
             COUNT(DISTINCT email) as buyers', [
            Carbon::now()->startOfMonth(), $todayStart,
        ]);

        $requests = (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString();

        return $this->ok([
            'requests' => BuyerRequestResource::collection($requests->getCollection()),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'today' => (int) ($summary->today ?? 0),
                'sellers' => (int) ($summary->sellers ?? 0),
                'buyers' => (int) ($summary->buyers ?? 0),
            ],
        ], 'OK', 200, [
            'current_page' => $requests->currentPage(),
            'per_page' => $requests->perPage(),
            'total' => $requests->total(),
            'last_page' => $requests->lastPage(),
            'from' => $requests->firstItem(),
            'to' => $requests->lastItem(),
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
