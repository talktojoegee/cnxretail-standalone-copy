@extends('layouts.master-layout')
@section('active-page')
    New Bill
@endsection
@section('title')
    New Bill
@endsection
@section('extra-styles')
    <link href="/assets/plugins/select2/select2.min.css" rel="stylesheet"/>
@endsection
@section('breadcrumb-action-btn')
    @include('bills-n-payment.partials._bill-menu')
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
                        <form action="{{route('add-new-bill')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Issue Bill</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Company Info
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="">Select vendor type</label>
                                                <select name="vendor_type" id="vendor_type" class="form-control">
                                                    <option selected disabled>--Select vendor type--</option>
                                                    <option value="1">New Vendor</option>
                                                    <option value="2">Existing Vendor</option>
                                                </select>
                                                @error('vendor_type') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 contact-wrapper">
                                            <div class="form-group">
                                                <label for="">Select Vendor</label>
                                                <select name="vendor" id="vendor" class="form-control select2-show-search">
                                                    <option selected disabled>--Select vendor--</option>
                                                    @foreach($vendors as $vendor)
                                                        <option value="{{$vendor->id}}">{{$vendor->company_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Bill Period</label>
                                                <div class="input-group">
                                                    <button class="btn btn-primary" type="button">Bill Date</button>
                                                    <input type="date" name="issue_date" class="form-control" placeholder="Bill Date">
                                                    <span class="input-group-prepend"></span>
                                                </div>
                                                @error('issue_date') <i class="text-danger">{{$message}}</i>@enderror

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
                                                        <textarea name="item_name[]" id="item_name" style="resize: none;"
                                                                  class="form-control" placeholder="Type description here..."></textarea>
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
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="addNewContactModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Vendor</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form action="{{route('add-new-contact')}}" method="post">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Add New Vendor</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Company Info
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" value="{{old('company_name') }}" name="company_name" placeholder="Company Name">
                                                @error('company_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Phone No.</label>
                                                <input type="text" class="form-control" name="company_phone_no" placeholder="Company Phone No." value="{{old('company_phone_no') }}" >
                                                @error('company_phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Company Email Address</label>
                                                <input type="text" class="form-control" name="company_email" value="{{old('company_email')}}" placeholder="Company Email Address">
                                                @error('company_email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Website</label>
                                                <input type="text" class="form-control" name="website" placeholder="Website" value="{{old('website') }}" >
                                                @error('website') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Address</label>
                                                <input type="text" class="form-control" name="address" placeholder="Address" value="{{old('address') }}" >
                                                @error('address') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    Contact Person
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">First Name</label>
                                                <input type="text" class="form-control" value="{{old('first_name') }}" name="first_name" placeholder="First Name">
                                                @error('first_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Phone No.</label>
                                                <input type="text" class="form-control" name="phone_no" placeholder="Phone No." value="{{old('phone_no') }}" >
                                                @error('phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" class="form-control" name="last_name" value="{{old('last_name')}}" placeholder="Last Name">
                                                @error('last_name') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Email Address</label>
                                                <input type="text" class="form-control" name="email" placeholder="Email Address" value="{{old('email') }}" >
                                                @error('email') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Position</label>
                                                <input type="text" class="form-control" name="position" placeholder="Position" value="{{old('position') }}" >
                                                @error('position') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" id="description" style="resize: none;"
                                                          class="form-control" placeholder="Briefly describe your contact here...">{{old('description')}}</textarea>
                                                @error('description') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Cancel</a>
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
            $('.contact-wrapper').hide();
            var grand_total = 0;
            $('.invoice-detail-table').on('mouseup keyup', 'input[type=number]', ()=> calculateTotals());

            $('#vendor_type').on('change', function(e){
                var selection = $(this).val();
                if(selection == 1){
                    $("#addNewContactModal").modal('show');
                    $('.contact-wrapper').hide();
                }else{
                    $('#addNewContactModal').modal('hide');
                    $('.contact-wrapper').show();
                }
            });

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
            $row.find('td:nth-last-child(2) input[type=text]').val(subtotal);
            return subtotal;
        }
        function setTotal(){
            var sum = 0;
            $(".payment").each(function(){
                sum += +$(this).val().replace(/,/g, '');
                $(".total").text(sum.toLocaleString());
            });
        }
    </script>
@endsection
