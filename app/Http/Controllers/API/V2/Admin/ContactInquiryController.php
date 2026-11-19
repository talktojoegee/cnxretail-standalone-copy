<?php

namespace App\Http\Controllers\API\V2\Admin;

use App\Http\Controllers\API\V2\BaseController;
use App\Models\ContactUs;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactInquiryController extends BaseController
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'nullable|string|max:120',
            'from' => 'nullable|date',
            'to' => 'nullable|date|after_or_equal:from',
            'per_page' => 'nullable|integer|in:10,25,50,100',
        ]);

        $perPage = (int) $request->input('per_page', 25);
        $todayStart = Carbon::today();
        $query = ContactUs::query()
            ->when($request->filled('q'), function (Builder $builder) use ($request) {
                $term = '%'.$request->input('q').'%';
                $builder->where(function (Builder $inner) use ($term) {
                    $inner->where('first_name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('subject', 'like', $term)
                        ->orWhere('message', 'like', $term);
                });
            })
            ->when($request->filled('from'), fn (Builder $builder) => $builder->where('created_at', '>=', Carbon::parse($request->input('from'))->startOfDay()))
            ->when($request->filled('to'), fn (Builder $builder) => $builder->where('created_at', '<=', Carbon::parse($request->input('to'))->endOfDay()));

        $summary = $this->aggregate($query, 'COUNT(*) as total,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_month,
             SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today', [
            Carbon::now()->startOfMonth(), $todayStart,
        ]);

        $messages = (clone $query)->orderByDesc('id')->paginate($perPage)->withQueryString();

        return $this->ok([
            'messages' => $messages->items(),
            'stats' => [
                'total' => (int) ($summary->total ?? 0),
                'this_month' => (int) ($summary->this_month ?? 0),
                'today' => (int) ($summary->today ?? 0),
            ],
        ], 'OK', 200, [
            'current_page' => $messages->currentPage(),
            'per_page' => $messages->perPage(),
            'total' => $messages->total(),
            'last_page' => $messages->lastPage(),
            'from' => $messages->firstItem(),
            'to' => $messages->lastItem(),
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
