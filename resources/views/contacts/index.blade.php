@php
    $labels = [0 => 'Contacts', 1 => 'Leads', 2 => 'Deals'];
    $label = $labels[$type] ?? 'Contacts';
    $listRoute = [0 => 'all-contacts', 1 => 'all-leads', 2 => 'all-deals'][$type] ?? 'all-contacts';
@endphp
@extends('layouts.master-layout')
@section('active-page')
    All {{ $label }}
@endsection
@section('title')
    All {{ $label }}
@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">Total {{ $label }}</p>
                            <h3 class="mb-0 number-font">{{ number_format($stats['total']) }}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary1">
                                <i class="bx bxs-user"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">{{ request()->filled('from') || request()->filled('to') ? 'In selected dates' : 'All time' }}</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">New this month</p>
                            <h3 class="mb-0 number-font">{{ number_format($stats['this_month']) }}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-orange">
                                <i class="bx bxs-calendar"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">{{ now()->format('F Y') }}</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">With phone</p>
                            <h3 class="mb-0 number-font">{{ number_format($stats['with_phone']) }}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-secondary">
                                <i class="bx bxs-phone"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">Company or mobile number</span>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-1">
                        <div class="col">
                            <p class="mb-1">With email</p>
                            <h3 class="mb-0 number-font">{{ number_format($stats['with_email']) }}</h3>
                        </div>
                        <div class="col-auto mb-0">
                            <div class="dash-icon text-warning">
                                <i class="bx bxs-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <span class="fs-12 text-muted">Company or contact email</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if (! empty($errors) && $errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="get" action="{{ route($listRoute) }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label>Search</label>
                                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Name, company, phone or email">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>From</label>
                                <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>To</label>
                                <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                            </div>
                            <div class="col-md-2 mb-2">
                                <label>Show</label>
                                <select name="per_page" class="form-control">
                                    @foreach([10, 25, 50, 100] as $size)
                                        <option value="{{ $size }}" {{ (int) request('per_page', 10) === $size ? 'selected' : '' }}>{{ $size }} entries</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary mr-2">Apply</button>
                                <a href="{{ route($listRoute) }}" class="btn btn-light">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Company</th>
                                <th>Phone No.</th>
                                <th>E-mail</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contacts as $contact)
                                <tr>
                                    <td>{{ $contacts->firstItem() + $loop->index }}</td>
                                    <td>{{ date('d M, Y', strtotime($contact->created_at)) }}</td>
                                    <td>{{ $contact->contact_first_name ?? '' }}</td>
                                    <td>{{ $contact->contact_last_name ?? '' }}</td>
                                    <td>{{ $contact->company_name ?? '' }}</td>
                                    <td>{{ $contact->company_phone ?: ($contact->contact_mobile ?? '') }}</td>
                                    <td>{{ $contact->company_email ?: ($contact->contact_email ?? '') }}</td>
                                    <td>
                                        <a href="{{ route('view-contact', $contact->slug) }}" class="btn btn-sm btn-info"><i class="ti-eye"></i></a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">No {{ strtolower($label) }} match these filters.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
                        <span class="text-muted mb-2">
                            Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} entries
                        </span>
                        @if ($contacts->hasPages())
                            {{ $contacts->links() }}
                        @else
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">Previous</span></li>
                                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">Next</span></li>
                                </ul>
                            </nav>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
