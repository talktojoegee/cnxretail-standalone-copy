@extends('layouts.master-layout')
@section('active-page')
    All My Imprest
@endsection
@section('title')
    All My Imprest
@endsection
@section('extra-styles')
    <link href="/assets/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet"/>
    <link href="/assets/plugins/datatable/responsivebootstrap4.min.css" rel="stylesheet" />
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('add-new-imprest')}}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" name="date" placeholder="Date" class="form-control">
                            @error('date')
                            <i class="text-danger">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Amount</label>
                            <input type="text" id="amount" step="0.01" name="amount" placeholder="Amount" class="form-control">
                            @error('amount')
                            <i class="text-danger">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Source</label>
                            <select name="bank" id="bank" class="form-control">
                                <option disabled selected>Select source</option>
                                @foreach ($banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->bank ?? ''}} - {{$bank->account_no ?? ''}}</option>
                                @endforeach
                            </select>
                            @error('bank')
                            <i class="text-danger">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Responsible Person</label>
                            <select name="responsible_person" class="form-control">
                                <option disabled selected>Select responsible person</option>
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}">{{$user->first_name ?? ''}} {{$user->surname ?? '' }}</option>
                                @endforeach
                            </select>
                            @error('responsible_person')
                            <i class="text-danger">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Description/Purpose</label>
                            <textarea name="description" id="description" style="resize: none;" class="form-control" placeholder="Description/Purpose"></textarea>
                            @error('description')
                            <i class="text-danger">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-group ">
                            <div class="btn-group d-flex justify-content-center">
                                <a href="{{url()->previous()}}" class="btn-danger btn btn-mini"><i class="ti-close mr-2"></i> Cancel</a>
                                <button class="btn-primary btn-mini btn" type="submit"><i class="ti-check mr-2"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4>All My Imprest</h4>
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
                                <th>Source</th>
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
                                    <td>{{date('d-M-Y', strtotime($imprest->transaction_date))}}</td>
                                    <td>{{number_format($imprest->amount,2)}}</td>
                                    <td>
                                        @if ($imprest->status == 0)
                                            <label for="" class="label label-warning text-uppercase">Pending</label>
                                        @elseif($imprest->status == 1)
                                            <label for="" class="label label-success text-uppercase">Approved</label>
                                        @elseif($imprest->status == 2)
                                            <label for="" class="label label-danger text-uppercase">Declined</label>
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
                                                        <form id="imprest_{{$imprest->id}}">
                                                            @csrf
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
                                                                <input type="text" value="{{$imprest->getResponsibleOfficer->first_name ?? ''}} {{$imprest->getResponsibleOfficer->surname ?? '' }}" readonly class="form-control">
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
    <script>
        $(document).ready(function(){
            $('#amount').on('blur', function(e){
                var value = $(this).val();
                $(this).val(formatter.format(value));

            });
            $('#amount').on('focus', function(e){
                 $(this).val("");
            });
        });
        var formatter = new Intl.NumberFormat('en-US');
    </script>
@endsection
