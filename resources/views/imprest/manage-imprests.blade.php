@extends('layouts.master-layout')
@section('active-page')
    Manage Imprests
@endsection
@section('title')
    Manage Imprests
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
                    <h4>Manage Imprest</h4>
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class=" table-responsive">
                        <table  class="table table-striped table-bordered nowrap simpletable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Bank</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $serial = 1;
                            @endphp
                            @foreach ($imprests as $imprest)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{date('d M, Y', strtotime($imprest->transaction_date))}}</td>
                                    <td>{{number_format($imprest->amount,2)}}</td>
                                    <td>
                                        @if ($imprest->status == 0)
                                            <label for="" class="label label-warning text-uppercase">Pending</label>
                                        @elseif($imprest->status == 1)
                                            <label for="" class="label label-success text-uppercase">Approved</label>
                                        @endif
                                    </td>
                                    <td>{{$imprest->getBank->bank ?? ''}} - {{$imprest->getBank->account_no ?? ''}}</td>
                                    <td>
                                        <label for="" class="" style="display: block; cursor:pointer;" data-target="#imprestModal_{{$imprest->id}}" data-toggle="modal"><i class="ti-eye text-primary"></i></label>
                                        <div class="modal fade" id="imprestModal_{{$imprest->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Imprest Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                            <div class="form-group">
                                                                <label for="">Date</label>
                                                                <input type="text" readonly placeholder="Date" class="form-control" value="{{date('d-m-Y', strtotime($imprest->transaction_date))}}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Amount</label>
                                                                <input type="text" readonly placeholder="Amount" class="form-control" value="{{number_format($imprest->amount,2)}}">

                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Bank</label>
                                                                <input type="text" readonly value="{{$imprest->getBank->bank ?? ''}} - {{$imprest->getBank->account_no ?? ''}}" class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Responsible Officer</label>
                                                                <input type="text" value="{{$imprest->getUser->first_name ?? ''}} {{$imprest->getUser->surname ?? ''}}" readonly class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Status</label>
                                                                <br>
                                                                @if ($imprest->status == 0)
                                                                    <label for="" class="label label-warning text-uppercase">Pending</label>
                                                                @elseif($imprest->status == 1)
                                                                    <label for="" class="label label-success text-uppercase">Approved</label>
                                                                @endif
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Description/Purpose</label>
                                                                <textarea readonly class="form-control" placeholder="Description/Purpose">{{$imprest->description ?? ''}}</textarea>

                                                            </div>
                                                            <div class="form-group ">
                                                                <div class="btn-group d-flex justify-content-center">
                                                                    <button data-dismiss="modal" class="btn-danger btn btn-mini"><i class="ti-close mr-2"></i> Close </button>
                                                                    <a href="{{route('process-imprest',['action'=>'decline','slug'=>$imprest->slug])}}" class="btn-secondary btn btn-mini"><i class="ti-trash mr-2"></i> Decline Imprest</a>
                                                                    <a class="btn-primary btn-mini btn "  href="{{route('process-imprest',['action'=>'approve','slug'=>$imprest->slug])}}"><i class="ti-check mr-2"></i> Approve Imprest</a>
                                                                </div>
                                                            </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tfoot>
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
