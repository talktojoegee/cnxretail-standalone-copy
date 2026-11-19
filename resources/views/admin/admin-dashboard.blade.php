@extends('layouts.admin-layout')
@section('title', 'Admin Dashboard')
@section('active-page', 'Admin Dashboard')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Tenants</h6>
                    <p class="value">{{ number_format($stats['tenants']) }}</p>
                    <p class="hint">{{ number_format($stats['this_month']) }} joined this month</p>
                </div>
                <span class="cnx-stat-icon"><i class="ti-briefcase"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Active Plans</h6>
                    <p class="value">{{ number_format($stats['subscribed']) }}</p>
                    <p class="hint">{{ number_format($stats['expired']) }} expired</p>
                </div>
                <span class="cnx-stat-icon is-success"><i class="ti-check"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Est. MRR</h6>
                    <p class="value">₦{{ number_format($stats['mrr'], 0) }}</p>
                    <p class="hint">Paid plans still in term</p>
                </div>
                <span class="cnx-stat-icon"><i class="ti-money"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Renewals Due</h6>
                    <p class="value">{{ number_format($stats['expiring']) }}</p>
                    <p class="hint">Next 14 days</p>
                </div>
                <span class="cnx-stat-icon is-warning"><i class="ti-alarm-clock"></i></span>
            </div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Users</h6>
                    <p class="value">{{ number_format($stats['users']) }}</p>
                    <p class="hint">{{ number_format($stats['suspended']) }} tenants suspended</p>
                </div>
                <span class="cnx-stat-icon"><i class="ti-user"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Catalog</h6>
                    <p class="value">{{ number_format($stats['products'] + $stats['services']) }}</p>
                    <p class="hint">{{ number_format($stats['products']) }} products · {{ number_format($stats['services']) }} services</p>
                </div>
                <span class="cnx-stat-icon"><i class="ti-package"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Buyer Requests</h6>
                    <p class="value">{{ number_format($stats['buyer_requests']) }}</p>
                    <p class="hint">Marketplace demand</p>
                </div>
                <span class="cnx-stat-icon"><i class="ti-shopping-cart"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div>
                    <h6>Contact Messages</h6>
                    <p class="value">{{ number_format($stats['contact_messages']) }}</p>
                    <p class="hint">Public contact form</p>
                </div>
                <span class="cnx-stat-icon is-danger"><i class="ti-email"></i></span>
            </div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Tenant registrations</h3>
                    <span class="cnx-muted">Last 6 months</span>
                </div>
                <div class="card-body">
                    <canvas id="cnx-admin-registrations" height="120"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Recent registrations</h3>
                    <a href="{{ route('manage-tenants') }}" class="btn btn-sm btn-primary">View all</a>
                </div>
                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap mb-0 cnx-admin-table">
                        <thead>
                        <tr>
                            <th>Company</th>
                            <th>Plan</th>
                            <th>Status</th>
                            <th>Ends</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentTenants as $tenant)
                            @php
                                $subscribed = $tenant->isSubscriptionActive();
                                $badge = $tenant->account_status != 1 ? 'is-suspended' : ($subscribed ? 'is-active' : 'is-expired');
                                $label = $tenant->account_status != 1 ? 'Suspended' : ($subscribed ? 'Active plan' : 'Expired');
                            @endphp
                            <tr>
                                <td>
                                    <strong>{{ $tenant->company_name }}</strong>
                                    <div class="cnx-muted">{{ $tenant->email }}</div>
                                </td>
                                <td>{{ $tenant->getTenantPlan->price_name ?? '—' }}</td>
                                <td><span class="cnx-badge {{ $badge }}">{{ $label }}</span></td>
                                <td>{{ $tenant->end_date ? date('d M, Y', strtotime($tenant->end_date)) : '—' }}</td>
                                <td><a href="{{ route('view-tenant', $tenant->slug) }}" class="btn btn-sm btn-outline-primary">Open</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center cnx-muted">No tenants yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">Plan mix</h3>
                </div>
                <div class="card-body">
                    @php $planTotal = max(1, $planMix->sum('total')); @endphp
                    @forelse($planMix as $plan)
                        <div class="cnx-plan-bar">
                            <div style="min-width:90px;">{{ $plan->label }}</div>
                            <div class="cnx-plan-track"><div class="cnx-plan-fill" style="width: {{ round(($plan->total / $planTotal) * 100) }}%"></div></div>
                            <strong>{{ number_format($plan->total) }}</strong>
                        </div>
                    @empty
                        <p class="cnx-muted mb-0">No plan data yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Upcoming renewals</h3>
                    <a href="{{ route('manage-tenants', ['status' => 'expiring']) }}" class="btn btn-sm btn-light">See all</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <tbody>
                            @forelse($expiringTenants as $tenant)
                                <tr>
                                    <td>
                                        <a href="{{ route('view-tenant', $tenant->slug) }}"><strong>{{ $tenant->company_name }}</strong></a>
                                        <div class="cnx-muted">{{ $tenant->getTenantPlan->price_name ?? 'No plan' }}</div>
                                    </td>
                                    <td class="text-right">
                                        <span class="cnx-badge is-expiring">
                                            {{ \Carbon\Carbon::parse($tenant->end_date)->diffForHumans() }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="text-center cnx-muted py-4">No renewals in the next 14 days.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Latest buyer requests</h3>
                    <a href="{{ route('admin.buyer-requests') }}" class="btn btn-sm btn-light">Open</a>
                </div>
                <div class="card-body">
                    @forelse($recentBuyerRequests as $request)
                        <div class="mb-3 pb-3 border-bottom">
                            <strong>{{ $request->first_name }}</strong>
                            <div class="cnx-muted">{{ $request->email }} · {{ $request->created_at?->format('d M, Y') }}</div>
                        </div>
                    @empty
                        <p class="cnx-muted mb-0">No buyer requests yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/chart/Chart.bundle.js"></script>
    <script>
        (function () {
            var canvas = document.getElementById('cnx-admin-registrations');
            if (!canvas || typeof Chart === 'undefined') return;
            new Chart(canvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'New tenants',
                        data: @json($chartValues),
                        borderColor: '#2446d4',
                        backgroundColor: 'rgba(36,70,212,0.12)',
                        pointBackgroundColor: '#2446d4',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    legend: { display: false },
                    scales: {
                        yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }],
                        xAxes: [{ gridLines: { display: false } }]
                    }
                }
            });
        })();
    </script>
@endsection
