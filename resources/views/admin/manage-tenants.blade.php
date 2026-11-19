@extends('layouts.admin-layout')
@section('title', 'Manage Tenants')
@section('active-page', 'Manage Tenants')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Matching</h6><p class="value">{{ number_format($stats['total']) }}</p><p class="hint">Current filters</p></div>
                <span class="cnx-stat-icon"><i class="ti-briefcase"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Active plans</h6><p class="value">{{ number_format($stats['subscribed']) }}</p><p class="hint">{{ number_format($stats['expired']) }} expired</p></div>
                <span class="cnx-stat-icon is-success"><i class="ti-check"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Expiring soon</h6><p class="value">{{ number_format($stats['expiring']) }}</p><p class="hint">Next 14 days</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-alarm-clock"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>This month</h6><p class="value">{{ number_format($stats['this_month']) }}</p><p class="hint">{{ number_format($stats['suspended']) }} suspended</p></div>
                <span class="cnx-stat-icon"><i class="ti-plus"></i></span>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="cnx-admin-filters">
                <div>
                    <label class="cnx-muted">Search</label>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Company, email, phone">
                </div>
                <div>
                    <label class="cnx-muted">Status</label>
                    <select name="status" class="form-control custom-select">
                        <option value="">All</option>
                        @foreach(['enabled' => 'Enabled', 'suspended' => 'Suspended', 'subscribed' => 'Active plan', 'expired' => 'Expired', 'expiring' => 'Expiring soon'] as $value => $label)
                            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="cnx-muted">Plan</label>
                    <select name="plan" class="form-control custom-select">
                        <option value="">All plans</option>
                        @foreach($plans as $plan)
                            <option value="{{ $plan->id }}" @selected((string) ($filters['plan'] ?? '') === (string) $plan->id)>{{ $plan->price_name }}</option>
                        @endforeach
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
                    <a class="btn btn-light" href="{{ route('manage-tenants') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Tenants</h3>
            <span class="cnx-muted">{{ $tenants->firstItem() ?? 0 }}–{{ $tenants->lastItem() ?? 0 }} of {{ number_format($tenants->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 cnx-admin-table">
                <thead>
                <tr>
                    <th style="width:56px;">#</th>
                    <th>Company</th>
                    <th>Plan / MRR</th>
                    <th>Usage</th>
                    <th>Health</th>
                    <th>Joined</th>
                    <th>Ends</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($tenants as $tenant)
                    @php
                        $subscribed = $tenant->isSubscriptionActive();
                        $daysLeft = $tenant->end_date ? (int) now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($tenant->end_date)->startOfDay(), false) : null;
                        $badge = $tenant->account_status != 1 ? 'is-suspended' : ($subscribed ? ($daysLeft !== null && $daysLeft <= 14 ? 'is-expiring' : 'is-active') : 'is-expired');
                        $label = $tenant->account_status != 1 ? 'Suspended' : ($subscribed ? ($daysLeft !== null && $daysLeft <= 14 ? 'Expiring' : 'Active plan') : 'Expired');
                        $mrr = \App\Support\AdminMetrics::monthlyValue(optional($tenant->getTenantPlan)->price, optional($tenant->getTenantPlan)->duration);
                        $serial = ($tenants->firstItem() ?? 1) + $loop->index;
                    @endphp
                    <tr>
                        <td class="cnx-muted">{{ $serial }}</td>
                        <td>
                            <strong>{{ $tenant->company_name }}</strong>
                            <div class="cnx-muted">{{ $tenant->email }}</div>
                            <div class="cnx-muted">{{ $tenant->phone_no ?: 'No phone' }}</div>
                        </td>
                        <td>
                            <div>{{ $tenant->getTenantPlan->price_name ?? 'No plan' }}</div>
                            <div class="cnx-muted">
                                @if($mrr > 0)
                                    ₦{{ number_format($mrr, 0) }}/mo
                                @else
                                    Trial / unpaid
                                @endif
                            </div>
                        </td>
                        <td class="cnx-muted">
                            {{ number_format($tenant->products_count) }} products<br>
                            {{ number_format($tenant->services_count) }} services<br>
                            {{ number_format($tenant->users_count) }} users · {{ number_format($tenant->buyer_requests_count) }} requests
                        </td>
                        <td><span class="cnx-badge {{ $badge }}">{{ $label }}</span></td>
                        <td>{{ $tenant->created_at?->format('d M, Y') }}</td>
                        <td>
                            {{ $tenant->end_date ? date('d M, Y', strtotime($tenant->end_date)) : '—' }}
                            @if($daysLeft !== null)
                                <div class="cnx-muted">
                                    @if($daysLeft >= 0)
                                        {{ $daysLeft }} day{{ $daysLeft === 1 ? '' : 's' }} left
                                    @else
                                        {{ abs($daysLeft) }} day{{ abs($daysLeft) === 1 ? '' : 's' }} overdue
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="cnx-admin-actions">
                                <a href="{{ route('view-tenant', $tenant->slug) }}" class="btn btn-sm btn-primary">Manage</a>
                                <a href="{{ route('vendor-store', $tenant->slug) }}" class="btn btn-sm btn-light" target="_blank" rel="noopener">Store</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center cnx-muted py-5">No tenants match these filters.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($tenants->hasPages())
            <div class="card-footer">{{ $tenants->links() }}</div>
        @endif
    </div>
@endsection
