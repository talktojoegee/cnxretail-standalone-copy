<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\AdminActivityLog;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ActivityLogController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $query = AdminActivityLog::query()
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('subject', 'like', $term)->orWhere('log', 'like', $term);
                });
            });

        $summary = $this->aggregate($query, 'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today', [Carbon::today()]);

        $logs = (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString();

        return $this->ok([
            'logs' => $logs->items(),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'today' => (int) ($summary->today ?? 0),
                'catalog' => Cache::remember('admin.stats.catalog', 60, fn () => Item::query()->count()),
                'users' => Cache::remember('admin.stats.users', 60, fn () => User::query()->count()),
            ],
        ], 'OK', 200, [
            'current_page' => $logs->currentPage(),
            'per_page' => $logs->perPage(),
            'total' => $logs->total(),
            'last_page' => $logs->lastPage(),
            'from' => $logs->firstItem(),
            'to' => $logs->lastItem(),
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
