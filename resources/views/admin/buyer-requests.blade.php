@extends('layouts.admin-layout')
@section('title', 'Buyer Requests')
@section('active-page', 'Buyer Requests')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Requests</h6><p class="value">{{ number_format($stats['total']) }}</p><p class="hint">Matching filters</p></div>
                <span class="cnx-stat-icon"><i class="ti-shopping-cart"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>This month</h6><p class="value">{{ number_format($stats['this_month']) }}</p><p class="hint">{{ number_format($stats['today']) }} today</p></div>
                <span class="cnx-stat-icon is-success"><i class="ti-calendar"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Unique buyers</h6><p class="value">{{ number_format($stats['buyers']) }}</p><p class="hint">By email</p></div>
                <span class="cnx-stat-icon"><i class="ti-user"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Sellers reached</h6><p class="value">{{ number_format($stats['sellers']) }}</p><p class="hint">Tenants with demand</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-briefcase"></i></span>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="cnx-admin-filters">
                <div>
                    <label class="cnx-muted">Buyer</label>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Name, email, phone, message">
                </div>
                <div>
                    <label class="cnx-muted">Seller</label>
                    <input type="search" name="seller" value="{{ $filters['seller'] ?? '' }}" class="form-control" placeholder="Company or email">
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
                    <a class="btn btn-light" href="{{ route('admin.buyer-requests') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">Marketplace buyer requests</h3>
            <span class="cnx-muted">{{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }} of {{ number_format($requests->total()) }}</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 cnx-admin-table">
                <thead>
                <tr>
                    <th style="width:56px;">#</th>
                    <th>Buyer</th>
                    <th>Seller</th>
                    <th>Item</th>
                    <th>Request</th>
                    <th>Received</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse($requests as $request)
                    @php
                        $serial = ($requests->firstItem() ?? 1) + $loop->index;
                        $kind = (int) optional($request->item)->item_type === 2 ? 'Service' : 'Product';
                    @endphp
                    <tr>
                        <td class="cnx-muted">{{ $serial }}</td>
                        <td>
                            <strong>{{ $request->first_name }}</strong>
                            <div class="cnx-muted">{{ $request->email }}</div>
                            <div class="cnx-muted">{{ $request->phone_no ?: 'No phone' }}</div>
                        </td>
                        <td>
                            @if($request->tenant)
                                <a href="{{ route('view-tenant', $request->tenant->slug) }}"><strong>{{ $request->tenant->company_name }}</strong></a>
                                <div class="cnx-muted">{{ $request->tenant->email }}</div>
                            @else
                                —
                            @endif
                        </td>
                        <td>
                            @if($request->item)
                                <a href="{{ route('view-item', $request->item->slug) }}" target="_blank" rel="noopener">{{ $request->item->item_name }}</a>
                                <div class="cnx-muted">{{ $kind }} · ₦{{ number_format($request->item->selling_price ?? 0, 2) }}</div>
                            @else
                                Item removed
                            @endif
                        </td>
                        <td style="max-width:320px;">
                            <details>
                                <summary>{{ \Illuminate\Support\Str::limit($request->message, 70) ?: 'No message' }}</summary>
                                <p class="mb-0 mt-2">{{ $request->message }}</p>
                            </details>
                        </td>
                        <td>
                            {{ $request->created_at?->format('d M, Y H:i') }}
                            <div class="cnx-muted">{{ $request->created_at?->diffForHumans() }}</div>
                        </td>
                        <td>
                            <div class="cnx-admin-actions">
                                @if($request->email)
                                    <a class="btn btn-sm btn-light" href="mailto:{{ $request->email }}">Email</a>
                                @endif
                                @if($request->phone_no)
                                    <a class="btn btn-sm btn-light" href="tel:{{ $request->phone_no }}">Call</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center cnx-muted py-5">No buyer requests found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
            <div class="card-footer">{{ $requests->links() }}</div>
        @endif
    </div>
@endsection
