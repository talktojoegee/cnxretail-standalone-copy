@extends('layouts.master-layout')
@section('active-page')
    Team Members
@endsection
@section('title')
    Team Members
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                <thead>
                                <tr>
                                    <th class="">#</th>
                                    <th class="wd-15p">First name</th>
                                    <th class="wd-15p">Last name</th>
                                    <th class="wd-20p">Status</th>
                                    <th class="wd-15p">Phone No.</th>
                                    <th class="wd-25p">E-mail</th>
                                    <th class="wd-25p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $serial = 1; @endphp
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{$user->first_name ?? '' }}</td>
                                        <td>{{$user->surname ?? '' }}</td>
                                        <td>{!! $user->account_status == 1 ? "<label class='text-success'>Active</label>" : "<label class='text-danger'>Deactivated</label>" !!}</td>
                                        <td>{{$user->mobile_no ?? '' }}</td>
                                        <td>{{$user->email ?? '' }}</td>
                                        <td><a href="{{route('view-profile', $user->slug)}}" class="btn btn-info btn-sm"><i class="ti-eye mr-2"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
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
