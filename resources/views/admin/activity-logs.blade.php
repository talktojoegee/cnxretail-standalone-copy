@extends('layouts.admin-layout')
@section('title', 'Activity Log')
@section('active-page', 'Activity Log')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Admin actions</h6><p class="value">{{ number_format($stats['total']) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-time"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Today</h6><p class="value">{{ number_format($stats['today']) }}</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-pulse"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Catalog items</h6><p class="value">{{ number_format($stats['catalog']) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-package"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Platform users</h6><p class="value">{{ number_format($stats['users']) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-user"></i></span>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="cnx-admin-filters">
                <div>
                    <label class="cnx-muted">Search</label>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Subject or details">
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
                    <a class="btn btn-light" href="{{ route('admin.activity-logs') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Administrator activity</h3></div>
        <div class="table-responsive">
            <table class="table mb-0 cnx-admin-table">
                <thead>
                <tr>
                    <th>When</th>
                    <th>Subject</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td>{{ $log->created_at?->format('d M, Y H:i') }}</td>
                        <td><strong>{{ $log->subject }}</strong></td>
                        <td>{{ $log->log }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center cnx-muted py-5">No admin activity recorded yet. Actions such as suspending a tenant will appear here.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="card-footer">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
