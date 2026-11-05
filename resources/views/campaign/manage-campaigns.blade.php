@extends('layouts.master-layout')
@section('active-page')
    Manage Campaigns
@endsection
@section('title')
    Manage Campaigns
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._invoice-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if(session()->has('error'))
                        <div class="alert alert-warning mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Whoops!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('error') !!}</p>
                        </div>
                    @endif
                    <div class="table-responsive">
                            <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                <thead>
                                <tr role="row">
                                    <th class="sorting_asc">S/No.</th>
                                    <th class="sorting">Title</th>
                                    <th class="sorting">Subject</th>
                                    <th class="sorting">Subscribers</th>
                                    <th class="sorting_asc">List Name</th>
                                    <th class="sorting">From</th>
                                    <th class="sorting">Reply To</th>
                                    <th class="sorting">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $serial = 1;
                                @endphp
                                @forelse($request->campaigns ?? [] as $campaign)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{$campaign->settings->title ?? '' }}</td>
                                        <td>{{$campaign->settings->subject_line ?? '' }}</td>
                                        <td>{{$campaign->recipients->recipient_count ?? '' }}</td>
                                        <td>{{$campaign->recipients->list_name ?? '' }}</td>
                                        <td>{{$campaign->settings->from_name ?? '' }}</td>
                                        <td>{{$campaign->settings->reply_to ?? '' }}</td>
                                        <td>
                                            <a href="{{route('view-campaign', $campaign->id)}}" class="btn btn-sm btn-info"> <i class="ti-eye mr-2"></i> </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No campaigns to show.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatable/datatable.js"></script>
    <script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
@endsection
