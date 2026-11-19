@extends('layouts.admin-layout')
@section('title', 'Contact Inquiries')
@section('active-page', 'Contact Inquiries')

@section('main-content')
    <div class="row">
        <div class="col-sm-6 col-xl-4">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Messages</h6><p class="value">{{ number_format($stats['total']) }}</p></div>
                <span class="cnx-stat-icon"><i class="ti-email"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>This month</h6><p class="value">{{ number_format($stats['this_month']) }}</p></div>
                <span class="cnx-stat-icon is-success"><i class="ti-calendar"></i></span>
            </div></div>
        </div>
        <div class="col-sm-6 col-xl-4">
            <div class="card"><div class="card-body cnx-stat">
                <div><h6>Today</h6><p class="value">{{ number_format($stats['today']) }}</p></div>
                <span class="cnx-stat-icon is-warning"><i class="ti-pulse"></i></span>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="get" class="cnx-admin-filters">
                <div>
                    <label class="cnx-muted">Search</label>
                    <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Name, email, subject, message">
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
                    <a class="btn btn-light" href="{{ route('admin.contact-inquiries') }}">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3 class="card-title mb-0">Public contact form</h3></div>
        <div class="table-responsive">
            <table class="table mb-0 cnx-admin-table">
                <thead>
                <tr>
                    <th>Sender</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @forelse($messages as $message)
                    <tr>
                        <td>
                            <strong>{{ $message->first_name }}</strong>
                            <div class="cnx-muted">{{ $message->email }} · {{ $message->mobile_no }}</div>
                        </td>
                        <td>{{ $message->subject }}</td>
                        <td style="max-width:320px;">{{ \Illuminate\Support\Str::limit($message->message, 120) }}</td>
                        <td>{{ $message->created_at?->format('d M, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center cnx-muted py-5">No contact messages found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
            <div class="card-footer">{{ $messages->links() }}</div>
        @endif
    </div>
@endsection
