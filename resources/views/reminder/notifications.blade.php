@extends('layouts.master-layout')
@section('active-page')
    Notifications
@endsection
@section('title')
    Notifications
@endsection
@section('extra-styles')
    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4>Notifications</h4>
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                            <tr>
                                <th class="">#</th>
                                <th class="wd-15p">Subject</th>
                                <th class="wd-15p">Message</th>
                                <th class="wd-15p">Status</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$notification->subject ??  '' }}</td>
                                    <td>{{ strlen($notification->body) > 54 ? substr($notification->body,0,51).'...' : $notification->body  }}</td>
                                    <td>
                                        {!! $notification->is_read == 0 ? "<span class='text-danger'>Unread</span>" : "<span class='text-success'>Read</span>" !!}
                                    </td>
                                    <td>
                                        <a href="{{$notification->route_type == 0 ? route($notification->route_name) : route($notification->route_name, $notification->route_param)}}"  class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @endforeach
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
