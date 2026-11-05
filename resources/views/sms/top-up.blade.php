@extends('layouts.master-layout')
@section('active-page')
    Top up
@endsection
@section('title')
    Top up
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
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                        <div class="card-body text-center">
                            <h6 class="mb-0">Expenditure(₦)</h6>
                            <h4 class="mb-1 mt-2 number-font text-danger"><span class="counter">{{'₦'.number_format($transactions->sum('debit'),2)}}</span></h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0">
                        <div class="card-body text-center">
                            <h6 class="mb-0">Expenditure(Units)</h6>
                            <h4 class="mb-1 mt-2 number-font text-danger">{{number_format($transactions->sum('unit_debit') )}}</h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0 border-right">
                        <div class="card-body text-center">
                            <h6 class="mb-0">Balance(₦)</h6>
                            <h4 class="mb-1 mt-2 number-font text-success"><span class="counter">{{'₦'.number_format($transactions->sum('credit') - $transactions->sum('debit'),2)}}</span></h4>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-sm-6 pr-0 pl-0">
                        <div class="card-body text-center">
                            <h6 class="mb-0">Balance(Units)</h6>
                            <h4 class="mb-1 mt-2 number-font text-success">{{number_format($transactions->sum('unit_credit') - $transactions->sum('unit_debit'))}}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <p><strong class="text-danger">Note:</strong> You're being charge ₦3 per page. A page consist of <code>160</code> characters. Please put this into consideration as you make your purchase. Thank you.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('top-up')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Buy More Units</h4>
                                <div class="form-group">
                                    <label for="">Number of Units</label>
                                    <input type="text" placeholder="Number of units" name="units" id="units" value="{{old('units')}}" class="form-control">
                                    <span class=""><strong class="text-danger">Note:</strong> Minimum number of units  <code>500</code> units</span>
                                    <br> @error('units')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <h4>Total: <span id="total">₦0.00</span></h4>
                                </div>
                                <hr style="margin: 0; padding: 0">
                                <p>We charge a flat rate of ₦3 per unit</p>
                                <div class="form-group d-flex justify-content-center">
                                    <button type="submit" class="btn btn-sm btn-primary"> Proceed <i class="ti-arrow-right ml-2"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4>Transactions</h4>
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
                                <th class="wd-15p">Quantity</th>
                                <th class="wd-15p">Amount</th>
                                <th class="wd-15p">Transaction</th>
                                <th class="wd-15p">Narration</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$transaction->unit_credit == 0 ? number_format($transaction->unit_debit) : number_format($transaction->unit_credit) }}</td>
                                    <td>{{$transaction->credit == 0 ? '₦'.number_format($transaction->debit) : '₦'.number_format($transaction->credit) }}</td>
                                    <td>{!! $transaction->unit_credit == 0 ? "<label class='text-danger'>Debit</label>" : "<label class='text-success'>Credit</label>" !!}</td>
                                    <td>{{$transaction->narration ?? '' }}</td>
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
    <script>
        $(document).ready(function(){
            $(document).on('blur', '#units', function(e){
                e.preventDefault();
                if(parseInt($(this).val()) < 500){
                    alert("The minimum amount of units that can be bought is 500 units");
                    $('#units').val(500);
                }
                var amt = parseFloat($(this).val().replace(/,/g, '')) /*$(this).val()*/ * 3; //#3/unit
                $('#total').text('₦'+amt.toLocaleString());
                var value = $(this).val();
                $(this).val(formatter.format(value));
            });
            var formatter = new Intl.NumberFormat('en-US');

        });
    </script>
@endsection
