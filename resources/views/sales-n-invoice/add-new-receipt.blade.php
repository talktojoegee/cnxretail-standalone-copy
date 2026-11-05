@extends('layouts.master-layout')
@section('active-page')
    New Receipt
@endsection
@section('title')
    Add New Receipt
@endsection
@section('extra-styles')
    <link href="/assets/plugins/select2/select2.min.css" rel="stylesheet"/>
@endsection
@section('breadcrumb-action-btn')
    @include('sales-n-invoice.partials._receipt-menu')
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="">
                    @if(session()->has('success'))
                        <div class="alert alert-success mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Great!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('success') !!}</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{route('add-new-receipt')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Issue Receipt</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Company Info
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Select Contact<sup class="text-danger">*</sup> </label>
                                                <select name="contact" id="contact" class="form-control select2-show-search">
                                                    <option selected disabled>--Select contact--</option>
                                                    @foreach($contacts as $contact)
                                                        <option value="{{$contact->id}}">{{$contact->company_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('contact') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Payment Date <sup class="text-danger">*</sup> </label>
                                                <input type="date" name="payment_date" placeholder="Payment Date" class="form-control">
                                                @error('payment_date') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Reference No. <sup class="text-danger">*</sup> </label>
                                                <input type="text" name="reference_no" value="{{old('reference_no')}}" placeholder="Reference No." class="form-control">
                                                @error('reference_no') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Payment Method<sup class="text-danger">*</sup> </label>
                                                <select name="payment_method" id="payment_method" class="form-control">
                                                    <option selected disabled>--Select payment method--</option>
                                                    <option value="1">Cash</option>
                                                    <option value="2">Bank Transfer</option>
                                                    <option value="3">Cheque</option>
                                                </select>
                                                @error('payment_method') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="">Bank<sup class="text-danger">*</sup> </label>
                                                <select name="bank" id="bank" class="form-control select2-show-search">
                                                    <option selected disabled>--Select bank--</option>
                                                    @foreach($banks as $bank)
                                                        <option value="{{$bank->id}}">{{$bank->account_no ?? ''}} - {{$bank->account_name ?? '' }} ({{$bank->bank}})</option>
                                                    @endforeach
                                                </select>
                                                <input type="hidden" name="receipt_type" value="2">
                                                @error('bank') <i class="text-danger mt-2">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Product/Service
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="table-responsive">
                                            <table class="table card-table table-vcenter text-nowrap mb-0 invoice-detail-table">
                                                <thead>
                                                <tr>
                                                    <th>Item</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                    <th>Total</th>
                                                    <th>Action</th>
                                                </tr>
                                                </thead>
                                                <tbody id="products">
                                                <tr class="item">
                                                    <td >
                                                        <select name="item_name[]"  class="form-control select-product select2-show-search" >
                                                            <option selected>--Select item--</option>
                                                            @foreach($items as $item)
                                                                <option value="{{$item->id}}">{{$item->item_name ?? '' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]" placeholder="Quantity" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="amount[]" step="0.01" placeholder="Amount" class="form-control">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="total[]" class="form-control total_amount" readonly>
                                                    </td>
                                                    <td>
                                                        <i class="ti-trash text-danger remove-line" style="cursor: pointer;"></i>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 col-sm-12 col-lg-12">
                                            <button class="btn btn-sm btn-primary add-line" type="button"> <i class="ti-plus mr-2"></i> Add Line</button>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12 col-sm-12 col-lg-12 d-flex justify-content-end">
                                            <h4 class="text-danger">Total: <span id="grand_total" style="color: #242E52;"></span></h4>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancle</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script src="/assets/plugins/select2/select2.full.min.js"></script>
    <script src="/assets/js/select2.js"></script>
    <script>
        $(document).ready(function(){
            var grand_total = 0;
            $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());

            $(document).on('click', '.add-line', function(e){
                e.preventDefault();
                var new_selection = $('.item').first().clone();
                $('#products').append(new_selection);

                $(".select-product").select2({
                    placeholder: "Select product or service"
                });
                $(".select-product").select2({ width: '100%' });
                $(".select-product").last().next().next().remove();
                //calculateTotals();
            });

            $(document).on('click', '.remove-line', function(e){
                e.preventDefault();
                $(this).closest('tr').remove();
                calculateTotals();
            });

        });

        function calculateTotals(){
            const subTotals = $('.item').map((idx, val)=> calculateSubTotal(val)).get();
            const total = subTotals.reduce((a, v)=> a + Number(v), 0);
            grand_total = total;
            // console.log("Total: "+total);
            $('#grand_total').text(grand_total.toLocaleString());
            //$('.total_amount').val(total.toLocaleString());
        }
        function calculateSubTotal(row){
            const $row = $(row);
            const inputs = $row.find('input');
            const subtotal = inputs[0].value * inputs[1].value;
            $row.find('td:nth-last-child(2) input[type=text]').val(formatter.format(subtotal));
            return subtotal;
        }
        function setTotal(){
            var sum = 0;
            $(".payment").each(function(){
                sum += +$(this).val().replace(/,/g, '');
                $(".total").text(sum.toLocaleString());
            });
        }
        var formatter = new Intl.NumberFormat('en-US')
    </script>
@endsection
