@extends('layouts.master-layout')
@section('active-page')
    Contact Profile
@endsection
@section('title')
    Contact Profile
@endsection
@section('extra-styles')
    <style>
        .modal p {
            word-wrap: break-word;
        }
    </style>
@endsection

@section('breadcrumb-action-btn')
    <a href="{{route('show-edit-contact', $contact->slug)}}" class="btn btn-primary btn-icon text-white mr-2">
            <span>
                <i class="ti-user"></i>
            </span> Edit Contact
    </a>
    <a href="{{route('add-new-contact')}}" class="btn btn-secondary btn-icon text-white">
            <span>
                <i class="fe fe-plus"></i>
            </span> Add New Contact
    </a>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user">
                        <div class="card">
                            <div class="card-header bg-primary br-tr-3 br-tl-3">
                                <h3 class="card-title text-white">Company Info <sup><label for="" class="label label-primary">
                                            @switch($contact->contact_type)
                                                @case(0)
                                                Contact
                                                @break
                                                @case(1)
                                                Lead
                                                @break
                                                @case(2)
                                                Deal
                                                @break
                                            @endswitch
                                        </label></sup></h3>
                                <div class="card-options ">
                                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up text-white"></i></a>
                                </div>
                            </div>
                            <div class="card-body">
                               <div class="form-group">
                                   <label for="">Company Name:</label>
                                   <input type="text" readonly value="{{$contact->company_name ?? '' }}" class="form-control">
                               </div>
                                <div class="form-group">
                                    <label for="">Email:</label>
                                    <input type="text" readonly value="{{$contact->company_email ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Phone No.:</label>
                                    <input type="text" readonly value="{{$contact->company_phone ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Company Address:</label>
                                    <input type="text" readonly value="{{$contact->company_address ?? '' }}" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="">Description:</label>
                                    <p>{{$contact->description ?? '' }}</p>
                                </div>
                            </div>
                            <div class="card-footer">
                                @if(!empty($contact->company_website))
                                    <a href="http://{{$contact->company_website}}" target="_blank">Visit website</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">Profile</a></li>
                                <li><a href="#tab-61" data-toggle="tab" class="">Follow-up</a></li>
                                <li><a href="#tab-71" data-toggle="tab" class="">Invoice</a></li>
                                <li><a href="#tab-81" data-toggle="tab" class="">Receipt</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>Contact Person</strong></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><strong>Full Name :</strong> {{$contact->contact_first_name ?? '' }} {{$contact->contact_last_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Position :</strong> {{$contact->contact_position ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>WhatsApp :</strong> {{$contact->whatsapp_contact ?? '' }}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-6 p-0">
                                        <tr>
                                            <td><strong>Call Time :</strong> {{ date('h:i a', strtotime($contact->preferred_time))  }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email :</strong> {{$contact->contact_email ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone :</strong> {{$contact->contact_mobile ?? '' }} </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="tab-pane" id="tab-61">
                    <div class="card">
                        <div class="card-status bg-blue br-tr-7 br-tl-7"></div>
                        <div class="card-header">
                            <h3 class="card-title">Add New Conversation</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session()->has('success'))
                                <div class="alert alert-success mb-4">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>Great!</strong>
                                    <hr class="message-inner-separator">
                                    <p>{!! session()->get('success') !!}</p>
                                </div>
                            @endif
                            <form action="{{route('new-conversation')}}" method="post">
                                @csrf
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Subject</label>
                                                    <input type="text" class="form-control" value="{{old('subject') }}" name="subject" placeholder="Subject">
                                                    @error('subject') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Date & Time <i class="ti-help" title="Set a future date & time for this event"></i> </label>
                                                    <input type="datetime-local" class="form-control" value="{{old('remind_at') }}" name="remind_at" placeholder="Set Date & Time">
                                                    @error('remind_at') <i class="text-danger">{{$message}}</i>@enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <div class="form-group mb-0">
                                                    <label class="form-label">Message</label>
                                                    <textarea class="form-control" name="conversation" rows="4" placeholder="What was your last conversation with {{$contact->contact_first_name ?? '' }}?">{{old('conversation')}}</textarea>
                                                    @error('conversation') <i class="text-danger">{{$message}}</i>@enderror
                                                    <input type="hidden" name="contact_id" value="{{$contact->id}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-right">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-status bg-danger br-tr-7 br-tl-7"></div>
                        <div class="card-header">
                            <h3 class="card-title">Previous Conversations</h3>
                            <div class="card-options">
                                <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Log</h3>
                                </div>
                                <div class="table-responsive">
                                    <table class="table card-table table-vcenter text-nowrap mb-0">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject</th>
                                            <th>Message</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php $serial = 1; @endphp
                                        @foreach($contact->getAllConversations as $conversation)
                                        <tr>
                                            <th scope="row">{{$serial++}}</th>
                                            <td>{{strlen($conversation->subject) > 20 ? substr($conversation->subject,0,20).'...' : $conversation->subject }}</td>
                                            <td>{{strlen($conversation->conversation) > 25 ? substr($conversation->conversation,0,25).'...' : $conversation->conversation }}</td>
                                            <td>
                                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalLongConvo_{{$conversation->id}}"><i class="ti-eye text-white"></i></button>
                                                <div class="modal" id="exampleModalLongConvo_{{$conversation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLongTitle">Conversation Details</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="card">
                                                                    <div class="card-header">
                                                                        <div class="card-options">
                                                                            <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                                                            <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                                                                        </div>
                                                                    </div>
                                                                    <div class="card-alert alert alert-success mb-0">
                                                                        Subject
                                                                    </div>
                                                                    <div class="card-body">
                                                                        {{$conversation->subject ?? '' }}
                                                                    </div>
                                                                    <div class="card-alert alert alert-success mb-0">
                                                                        Message
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <textarea name="" readonly id="" style="resize: none"
                                                                                  class="form-control">{{$conversation->conversation ?? '' }}</textarea>
                                                                    </div>
                                                                    <div class="card-footer text-muted z-index2">
                                                                        <strong>Author:</strong> {{$conversation->getAddedBy->first_name ?? '' }} {{$conversation->getAddedBy->surname ?? '' }}
                                                                        <strong>Date:</strong> {{date('d M, Y', strtotime($conversation->created_at)) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- SCROLLING CLOSED -->

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
                <div class="tab-pane" id="tab-71">
                    <div class="card">
                        <div class="card-header">{{$contact->company_name ?? '' }}'s Invoices</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="table-responsive">
                                        <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                            <thead>
                                            <tr role="row">
                                                <th class="sorting_asc">S/No.</th>
                                                <th class="sorting_asc">Date</th>
                                                <th class="sorting">Invoice No.</th>
                                                <th class="sorting">Total</th>
                                                <th class="sorting">Amount Paid</th>
                                                <th class="sorting">Balance</th>
                                                <th class="sorting">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $serial = 1;
                                            @endphp
                                            @foreach($contact->getAllContactInvoices as $invoice)
                                                <tr>
                                                    <td>{{$serial++}}</td>
                                                    <td>{{!is_null($invoice->created_at) ? date('d M,Y', strtotime($invoice->created_at)) : '-'}}</td>
                                                    <td>{{$invoice->invoice_no ?? ''}} - {{$invoice->ref_no ?? ''}}</td>
                                                    <td class="text-right">{{number_format($invoice->total,2)}}</td>
                                                    <td class="text-right text-success">{{number_format($invoice->paid_amount,2)}}</td>
                                                    <td class="text-right text-warning">{{number_format($invoice->total - $invoice->paid_amount,2)}}</td>
                                                    <td>
                                                        <div class="dropdown-secondary dropdown">
                                                            <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                            <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                <a class="dropdown-item waves-light waves-effect" href="{{route('view-invoice', $invoice->slug)}}"><i class="ti-printer"></i> View Invoice</a>
                                                                @if($invoice->posted == 1 && $invoice->paid_amount < $invoice->total)
                                                                    <a class="dropdown-item waves-light waves-effect" href="{{route('receive-payment', $invoice->slug)}}"><i class="ti-receipt"></i> Receive Payment</a>
                                                                @endif
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
                <div class="tab-pane" id="tab-81">
                    <div class="card">
                        <div class="card-header">{{$contact->company_name ?? '' }}'s Receipts</div>
                    <div class="card-body">
                        <div class="row">
                            <div class=" col-lg-12 col-md-12">
                                <div class="table-responsive">
                                    <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                        <thead>
                                        <tr role="row">
                                            <th class="sorting_asc">S/No.</th>
                                            <th class="sorting_asc">Date</th>
                                            <th class="sorting_asc">Receipt No.</th>
                                            <th class="sorting">Amount</th>
                                            <th class="sorting">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @php
                                            $serial = 1;
                                        @endphp
                                        @foreach($contact->getAllContactReceipts as $receipt)
                                            <tr role="row" class="odd">
                                                <td>{{$serial++}}</td>
                                                <td>{{ date('d M, Y', strtotime($receipt->created_at)) }}</td>
                                                <td>{{$receipt->receipt_no ?? ''}} - {{strtoupper($receipt->ref_no)}}</td>
                                                <td class="text-right">{{number_format($receipt->amount,2)}}</td>
                                                <td>
                                                    <div class="dropdown-secondary dropdown">
                                                        <button class="btn btn-info btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown14" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                        <div class="dropdown-menu" aria-labelledby="dropdown14" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                            <a class="dropdown-item waves-light waves-effect" href="{{route('view-receipt', $receipt->slug)}}"><i class="ti-printer"></i> View Receipt</a>
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
            </div>
        </div><!-- COL-END -->
    </div>
@endsection
