@extends('layouts.admin-layout')
@section('title', $tenant->company_name)
@section('active-page', 'Tenant Details')

@section('breadcrumb-action-btn')
    <a href="{{ route('manage-tenants') }}" class="btn btn-primary btn-icon text-white mr-2">
        <span><i class="ti-arrow-left"></i></span> Back to tenants
    </a>
@endsection

@section('main-content')
    @php
        $subscribed = $tenant->isSubscriptionActive();
        $badge = $tenant->account_status != 1 ? 'is-suspended' : ($subscribed ? 'is-active' : 'is-expired');
        $label = $tenant->account_status != 1 ? 'Suspended' : ($subscribed ? 'Active plan' : 'Expired');
    @endphp

    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Products</h6><p class="value">{{ number_format($tenant->products_count) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-package"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Services</h6><p class="value">{{ number_format($tenant->services_count) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-settings"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Users</h6><p class="value">{{ number_format($tenant->users_count) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-user"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Buyer requests</h6><p class="value">{{ number_format($tenant->buyer_requests_count) }}</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-shopping-cart"></i></span>
            </div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Tenant profile</h3>
                    <span class="cnx-badge {{ $badge }}">{{ $label }}</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Company</label>
                        <input type="text" class="form-control" readonly value="{{ $tenant->company_name }}">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control" readonly value="{{ $tenant->email }}">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" class="form-control" readonly value="{{ $tenant->phone_no ?: '—' }}">
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control" readonly value="{{ $tenant->address ?: '—' }}">
                    </div>
                    <div class="form-group mb-0">
                        <label>Description</label>
                        <p class="mb-0">{{ $tenant->description ?: 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h3 class="card-title mb-0">Subscription controls</h3></div>
                <div class="card-body">
                    <p class="mb-1"><strong>Plan:</strong> {{ $tenant->getTenantPlan->price_name ?? '—' }}</p>
                    <p class="mb-1"><strong>Starts:</strong> {{ $tenant->start_date ? date('d M, Y', strtotime($tenant->start_date)) : '—' }}</p>
                    <p class="mb-3"><strong>Ends:</strong> {{ $tenant->end_date ? date('d M, Y', strtotime($tenant->end_date)) : '—' }}</p>

                    <form method="post" action="{{ route('admin.tenant.status', $tenant->slug) }}" class="mb-3">
                        @csrf
                        @if($tenant->account_status == 1)
                            <input type="hidden" name="status" value="0">
                            <button class="btn btn-outline-danger btn-block" type="submit" onclick="return confirm('Suspend this tenant account?')">Suspend account</button>
                        @else
                            <input type="hidden" name="status" value="1">
                            <button class="btn btn-outline-success btn-block" type="submit">Enable account</button>
                        @endif
                    </form>

                    <form method="post" action="{{ route('admin.tenant.extend', $tenant->slug) }}" class="cnx-admin-actions">
                        @csrf
                        <select name="days" class="form-control custom-select" style="min-width:140px;">
                            <option value="7">+7 days</option>
                            <option value="14">+14 days</option>
                            <option value="30" selected>+30 days</option>
                            <option value="90">+90 days</option>
                            <option value="365">+365 days</option>
                        </select>
                        <button class="btn btn-primary" type="submit">Extend plan</button>
                    </form>
                    @if($tenant->website)
                        <a class="d-inline-block mt-3" href="http://{{ $tenant->website }}" target="_blank" rel="noopener">Visit website</a>
                    @endif
                    <a class="d-inline-block mt-3 ml-3" href="{{ route('vendor-store', $tenant->slug) }}" target="_blank" rel="noopener">View storefront</a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><h3 class="card-title mb-0">Subscription history</h3></div>
                <div class="table-responsive">
                    <table class="table mb-0 cnx-admin-table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Plan</th>
                            <th>Start</th>
                            <th>End</th>
                            <th class="text-right">Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($tenant->getTenantSubscriptions as $sub)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $sub->getSubscriptionPlan->price_name ?? '—' }}</td>
                                <td>{{ date('d M, Y', strtotime($sub->start_date)) }}</td>
                                <td>{{ date('d M, Y', strtotime($sub->end_date)) }}</td>
                                <td class="text-right">₦{{ number_format(max(0, ceil(($sub->amount ?? 0) / 100) - ($sub->charge ?? 0)), 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center cnx-muted py-4">No paid subscription history yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">Recent buyer requests</h3>
                    <a href="{{ route('admin.buyer-requests', ['q' => $tenant->company_name]) }}" class="btn btn-sm btn-light">All requests</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 cnx-admin-table">
                        <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Item</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentRequests as $request)
                            <tr>
                                <td>
                                    <strong>{{ $request->first_name }}</strong>
                                    <div class="cnx-muted">{{ $request->email }} · {{ $request->phone_no }}</div>
                                </td>
                                <td>{{ $request->item->item_name ?? 'Item removed' }}</td>
                                <td>{{ $request->created_at?->format('d M, Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center cnx-muted py-4">No marketplace requests for this tenant.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
