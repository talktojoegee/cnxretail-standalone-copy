@extends('layouts.master-layout')
@section('active-page')
    Bulk Messages
@endsection
@section('title')
    Bulk Messages
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
                                    <th class="wd-15p">Message</th>
                                    <th class="wd-15p">Date</th>
                                    <th class="wd-25p">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $serial = 1; @endphp
                                @foreach($messages as $message)
                                    <tr>
                                        <td>{{$serial++}}</td>
                                        <td>{{$message->message ?? '' }}</td>
                                        <td>{{date('d M, Y', strtotime($message->created_at))}}</td>
                                        <td>
                                            <a href="javascript:void(0);" data-toggle="modal" data-target="#messageModal_{{$message->id}}" class="btn btn-sm btn-info"> <i class="ti-eye"></i> </a>
                                            <div class="modal" id="messageModal_{{$message->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLongTitle">Message Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="javascript:void(0);" method="post">
                                                                @csrf
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <h3 class="mb-0 card-title">Date
                                                                            <label for="" class="label label-danger">{{date('d M, Y', strtotime($message->created_at)) ?? '' }}</label></h3>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <div class="row">
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Message</label>
                                                                                    <textarea readonly  cols="30" rows="5" class="form-control">{{$message->message ?? ''}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-md-12">
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Phone Numbers</label>
                                                                                    <textarea readonly  cols="30" rows="5" class="form-control">{{$message->sent_to ?? ''}}</textarea>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-footer text-right">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
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
