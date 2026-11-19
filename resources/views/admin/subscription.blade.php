@extends('layouts.admin-layout')
@section('title', 'Subscriptions')
@section('active-page', 'Subscriptions')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Payments</h6><p class="value">{{ number_format($stats['total']) }}</p><p class="hint">Matching filters</p></div>
                <span class="cnx-stat-icon"><i class="ti-receipt"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Net revenue</h6><p class="value">₦{{ number_format($stats['revenue'], 2) }}</p><p class="hint">After gateway charges</p></div>
                <span class="cnx-stat-icon is-success"><i class="ti-money"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Active terms</h6><p class="value">{{ number_format($stats['active']) }}</p><p class="hint">{{ number_format($stats['expired']) }} expired</p></div>
                <span class="cnx-stat-icon"><i class="ti-check"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>This month</h6><p class="value">{{ number_format($stats['this_month']) }}</p><p class="hint">New payments</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-calendar"></i></span>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="cnx-admin-filters">
                <div>
                    <label class="cnx-muted">Search</label>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Company or email">
                </div>
                <div>
                    <label class="cnx-muted">Term status</label>
                    <select name="status" class="form-control custom-select">
                        <option value="">All</option>
                        <option value="active" @selected(($filters['status'] ?? '') === 'active')>Active term</option>
                        <option value="expired" @selected(($filters['status'] ?? '') === 'expired')>Expired term</option>
                    </select>
                </div>
                <div>
                    <label class="cnx-muted">From</label>
                    <input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control">
                </div>
                <div>
                    <label class="cnx-muted">To</label>
                    <input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control">
                </div>
                <div>
                    <label class="cnx-muted">Per page</label>
                    <select name="per_page" class="form-control custom-select">
                        @foreach([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected((int) ($filters['per_page'] ?? 25) === $size)>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cnx-admin-actions">
                    <button class="btn btn-primary" type="submit">Filter</button>
                    <a class="btn btn-light" href="{{ route('subscriptions') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Subscription payments</h3>
            <span class="cnx-muted">{{ $subscriptions->firstItem() ?? 0 }}–{{ $subscriptions->lastItem() ?? 0 }} of {{ number_format($subscriptions->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 cnx-admin-table">
                <thead>
                <tr>
                    <th style="width:56px;">#</th>
                    <th>Company</th>
                    <th>Plan</th>
                    <th>Billing period</th>
                    <th>Status</th>
                    <th>Paid on</th>
                    <th class="text-right">Gross</th>
                    <th class="text-right">Charge</th>
                    <th class="text-right">Net</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($subscriptions as $sub)
                    @php
                        $displayNet = max(0, ceil(($sub->amount ?? 0) / 100) - ($sub->charge ?? 0));
                        $active = $sub->end_date && \Carbon\Carbon::parse($sub->end_date)->startOfDay()->gte(now()->startOfDay());
                        $serial = ($subscriptions->firstItem() ?? 1) + $loop->index;
                    @endphp
                    <tr>
                        <td class="cnx-muted">{{ $serial }}</td>
                        <td>
                            @if($sub->getTenant)
                                <a href="{{ route('view-tenant', $sub->getTenant->slug) }}"><strong>{{ $sub->getTenant->company_name }}</strong></a>
                                <div class="cnx-muted">{{ $sub->getTenant->email }}</div>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            <div>{{ $sub->getSubscriptionPlan->price_name ?? '—' }}</div>
                            @if($sub->sub_key)
                                <div class="cnx-muted">{{ \Illuminate\Support\Str::limit($sub->sub_key, 18) }}</div>
                            @endif
                        </td>
                        <td>
                            {{ date('d M, Y', strtotime($sub->start_date)) }}
                            <div class="cnx-muted">to {{ date('d M, Y', strtotime($sub->end_date)) }}</div>
                        </td>
                        <td>
                            <span class="cnx-badge {{ $active ? 'is-active' : 'is-expired' }}">
                                {{ $active ? 'In term' : 'Ended' }}
                            </span>
                        </td>
                        <td>{{ $sub->created_at?->format('d M, Y H:i') }}</td>
                        <td class="text-right">₦{{ number_format($displayNet + ($sub->charge ?? 0), 2) }}</td>
                        <td class="text-right cnx-muted">₦{{ number_format($sub->charge ?? 0, 2) }}</td>
                        <td class="text-right"><strong>₦{{ number_format($displayNet, 2) }}</strong></td>
                        <td>
                            @if($sub->getTenant)
                                <a href="{{ route('view-tenant', $sub->getTenant->slug) }}" class="btn btn-sm btn-light">Tenant</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10" class="text-center cnx-muted py-5">No subscription payments found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($subscriptions->hasPages())
            <div class="card-footer">{{ $subscriptions->links() }}</div>
        @endif
    </div>
@endsection
