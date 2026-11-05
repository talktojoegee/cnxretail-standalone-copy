@extends('layouts.master-layout')
@section('active-page')
    All Vendors
@endsection
@section('title')
    All Vendors
@endsection
@section('extra-styles')

    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')
    @include('vendors.partials._menu')
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
                                    <th class="wd-15p">Date</th>
                                    <th class="wd-15p">First name</th>
                                    <th class="wd-15p">Last name</th>
                                    <th class="wd-20p">Company</th>
                                    <th class="wd-15p">Phone No.</th>
                                    <th class="wd-25p">E-mail</th>
                                    <th class="wd-25p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $serial = 1; @endphp
                                @foreach($vendors as $contact)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{date('d M, Y', strtotime($contact->created_at))}}</td>
                                        <td>{{$contact->contact_first_name ?? '' }}</td>
                                        <td>{{$contact->contact_last_name ?? '' }}</td>
                                        <td>{{$contact->company_name ?? '' }}</td>
                                        <td>{{$contact->company_phone ?? '' }}</td>
                                        <td>{{$contact->company_email ?? '' }}</td>
                                        <td>
                                            <a href="{{route('view-contact', $contact->slug)}}" class="btn btn-sm btn-info"> <i class="ti-eye"></i> </a>
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
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/datatable/jquery.dataTables.min.js"></script>
    <script src="/assets/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script src="/assets/plugins/datatable/datatable.js"></script>
    <script src="/assets/plugins/datatable/dataTables.responsive.min.js"></script>
@endsection
