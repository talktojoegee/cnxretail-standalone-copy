@extends('layouts.master-layout')
@section('active-page')
    General Settings
@endsection
@section('title')
    General Settings
@endsection
@section('breadcrumb-action-btn')

@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   @if(session()->has('success'))
                    <div class="alert alert-success mb-4">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <strong>Great!</strong>
                        <hr class="message-inner-separator">
                        <p>{!! session()->get('success') !!}</p>
                    </div>
                    @endif
                   @if(session()->has('error'))
                       <div class="alert alert-warning mb-4">
                           <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                           <strong>Whoops!</strong>
                           <hr class="message-inner-separator">
                           <p>{!! session()->get('error') !!}</p>
                       </div>
                   @endif
                    <div class="panel panel-primary">
                        <div class=" tab-menu-heading">
                            <div class="tabs-menu1 ">
                                <!-- Tabs -->
                                <ul class="nav panel-tabs">
                                    <li><a href="#tab5" class="active" data-toggle="tab">General Settings</a></li>
                                    <li><a href="#tab6" data-toggle="tab" class="">Payment Integration</a></li>
                                    <li><a href="#tab7" data-toggle="tab" class="">Email Campaign</a></li>
                                    <li><a href="#tab8" data-toggle="tab" class="">Banks</a></li>
                                    <li><a href="#tab9" data-toggle="tab" class="">Bulk SMS</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="panel-body tabs-menu-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab5">
                                    <form action="{{route('app-settings')}}" method="post">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0 card-title">General Settings</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Company Name</label>
                                                            <input type="text" class="form-control" value="{{old('company_name',Auth::user()->getTenant->company_name) }}" name="company_name" placeholder="Company Name">
                                                            @error('company_name') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Phone No.</label>
                                                            <input type="text" class="form-control" name="phone_no" placeholder="Phone No." value="{{old('phone_no',Auth::user()->getTenant->phone_no) }}" >
                                                            @error('phone_no') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Address</label>
                                                            <input type="text" class="form-control" name="address" placeholder="Address" value="{{old('address',Auth::user()->getTenant->address) }}" >
                                                            @error('address') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Email Address</label>
                                                            <input type="text" class="form-control" name="email" value="{{Auth::user()->getTenant->email ?? ''}}" readonly placeholder="Valid Email..">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Tagline</label>
                                                            <input type="text" class="form-control" name="tagline" placeholder="Tagline" value="{{old('tagline',Auth::user()->getTenant->tagline) }}" >
                                                            @error('tagline') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="form-label">Website</label>
                                                            <input type="text" class="form-control" name="website" placeholder="Website" value="{{old('website',Auth::user()->getTenant->website) }}" >
                                                            @error('website') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 ">
                                                        <div class="form-group mb-0">
                                                            <label class="form-label">Description</label>
                                                            <textarea class="form-control" name="description" rows="4" placeholder="What will you say about your business or company?">{{old('description',Auth::user()->getTenant->description)}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <a href="{{url()->previous()}}" class="btn btn-danger">Cancle</a>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                                <div class="tab-pane" id="tab6">
                                    <form action="{{route('app-payment-integration')}}" method="post">
                                        @csrf
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="mb-0 card-title">Payment Integration</h3>
                                            </div>
                                            <div class="card-body">
                                                <p>{{config('app.name')}} uses <a href="https://www.paystack.com" target="_blank">Paystack</a> to enable businesses receive payment directly to their Paystack account. This is possible through the use of both the <code>secret</code> and <code>public</code> keys provided by Paystack.</p>
                                                <p>Copy both your <code>Live Public Key</code> and <code>Live Secret Key</code> and paste them in the fields provided below.</p>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Live Public Key</label>
                                                            <input type="text" class="form-control" value="{{old('public_key',Auth::user()->getTenant->public_key) }}" name="public_key" placeholder="Live Public Key">
                                                            @error('public_key') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label class="form-label">Live Secret Key</label>
                                                            <input type="text" class="form-control" name="secret_key" value="{{old('secret_key',Auth::user()->getTenant->secret_key)}}" placeholder="Live Secret Key">
                                                            @error('secret_key') <i class="text-danger">{{$message}}</i>@enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <p><strong class="text-danger">Note:</strong> Use <a
                                                                href="javascript:void(0);">http://app.cnxretail.com/process/payment</a> as your callback URL</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer text-right">
                                                <a href="{{url()->previous()}}" class="btn btn-danger">Cancle</a>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane" id="tab7">
                                   <div class="row">
                                       <div class="col-md-4">
                                           <form action="{{route('new-mailchimp-settings')}}" method="post">
                                               @csrf
                                               <div class="card">
                                                   <div class="card-header">
                                                       <h3 class="mb-0 card-title">Add New Mailchimp Settings</h3>
                                                   </div>
                                                   <div class="card-body">
                                                       <p>Add your various mailchimp API keys and List IDs using the form below. We'll give you the option to choose from this list when sending out an email campaign.</p>
                                                       <div class="row">
                                                           <div class="col-md-12">
                                                               <div class="form-group">
                                                                   <label class="form-label">API Key</label>
                                                                   <input type="text" class="form-control" value="{{old('api_key') }}" name="api_key" placeholder="API Key">
                                                                   @error('api_key') <i class="text-danger">{{$message}}</i>@enderror
                                                               </div>
                                                           </div>
                                                           <div class="col-md-12">
                                                               <div class="form-group">
                                                                   <label class="form-label">List ID</label>
                                                                   <input type="text" class="form-control" name="list_id" value="{{old('list_id')}}" placeholder="List ID">
                                                                   @error('list_id') <i class="text-danger">{{$message}}</i>@enderror
                                                               </div>
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
                                       <div class="col-md-8">
                                           <div class="card">
                                               <div class="card-header">
                                                   <h3 class="mb-0 card-title">Previously Saved List</h3>
                                               </div>
                                              <div class="card-body">
                                                  <div class="table-responsive">
                                                      <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                                          <thead>
                                                          <tr>
                                                              <th class="">#</th>
                                                              <th class="wd-15p">API Key</th>
                                                              <th class="wd-15p">List ID</th>
                                                              <th class="wd-25p">Action</th>
                                                          </tr>
                                                          </thead>
                                                          <tbody>
                                                          @php $serial = 1; @endphp
                                                          @foreach(Auth::user()->getTenantMailchimpSettings as $settings)
                                                          <tr>
                                                              <td>{{$serial++}}</td>
                                                              <td>{{$settings->mailchimp_api_key ?? '' }}</td>
                                                              <td>{{$settings->mailchimp_list_id ?? '' }}</td>
                                                              <td>
                                                                  <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModalLong_{{$settings->id}}">Edit</button></td>
                                                              <div class="modal" id="exampleModalLong_{{$settings->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                                  <div class="modal-dialog" role="document">
                                                                      <div class="modal-content">
                                                                          <div class="modal-header">
                                                                              <h5 class="modal-title" id="exampleModalLongTitle">Edit Settings</h5>
                                                                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                  <span aria-hidden="true">&times;</span>
                                                                              </button>
                                                                          </div>
                                                                          <div class="modal-body">
                                                                              <form action="{{route('update-mailchimp-settings')}}" method="post">
                                                                                  @csrf
                                                                                  <div class="card">
                                                                                      <div class="card-header">
                                                                                          <h3 class="mb-0 card-title">Edit Mailchimp Settings</h3>
                                                                                      </div>
                                                                                      <div class="card-body">
                                                                                          <div class="row">
                                                                                              <div class="col-md-12">
                                                                                                  <div class="form-group">
                                                                                                      <label class="form-label">API Key</label>
                                                                                                      <input type="text" class="form-control" value="{{old('api_key', $settings->mailchimp_api_key) }}" name="api_key" placeholder="API Key">
                                                                                                      @error('api_key') <i class="text-danger">{{$message}}</i>@enderror
                                                                                                  </div>
                                                                                              </div>
                                                                                              <div class="col-md-12">
                                                                                                  <div class="form-group">
                                                                                                      <label class="form-label">List ID</label>
                                                                                                      <input type="text" class="form-control" name="list_id" value="{{old('list_id', $settings->mailchimp_list_id)}}" placeholder="List ID">
                                                                                                      @error('list_id') <i class="text-danger">{{$message}}</i>@enderror
                                                                                                  </div>
                                                                                              </div>
                                                                                          </div>
                                                                                      </div>
                                                                                      <div class="card-footer text-right">
                                                                                          <input type="hidden" name="mailchimp" value="{{$settings->id}}">
                                                                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                          <button type="submit" class="btn btn-primary">Save changes</button>
                                                                                      </div>
                                                                                  </div>
                                                                              </form>
                                                                          </div>
                                                                      </div>
                                                                  </div>
                                                              </div>
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
                                <div class="tab-pane " id="tab8">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <form action="{{route('save-bank')}}" method="post">
                                                @csrf
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="mb-0 card-title">Add New Bank</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <p>Add the various accounts your business operates with to track remittance</p>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Bank Name</label>
                                                                    <input type="text" class="form-control" value="{{old('bank_name') }}" name="bank_name" placeholder="Bank Name">
                                                                    @error('bank_name') <i class="text-danger">{{$message}}</i>@enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Account Name</label>
                                                                    <input type="text" class="form-control" name="account_name" value="{{old('account_name')}}" placeholder="Account Name">
                                                                    @error('account_name') <i class="text-danger">{{$message}}</i>@enderror
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Account No.</label>
                                                                    <input type="text" class="form-control" name="account_no" value="{{old('account_no')}}" placeholder="Account No.">
                                                                    @error('account_no') <i class="text-danger">{{$message}}</i>@enderror
                                                                </div>
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
                                        <div class="col-md-8">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="mb-0 card-title">All Banks</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="data-table1" class="table table-striped table-bordered text-nowrap w-100">
                                                            <thead>
                                                            <tr>
                                                                <th class="">#</th>
                                                                <th class="wd-15p">Bank Name</th>
                                                                <th class="wd-15p">Account Name</th>
                                                                <th class="wd-15p">Account No.</th>
                                                                <th class="wd-25p">Action</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            @php $serial = 1; @endphp
                                                            @foreach(Auth::user()->getTenantBanks as $bank)
                                                                <tr>
                                                                    <td>{{$serial++}}</td>
                                                                    <td>{{$bank->bank ?? '' }}</td>
                                                                    <td>{{$bank->account_name ?? '' }}</td>
                                                                    <td>{{$bank->account_no ?? '' }}</td>
                                                                    <td>
                                                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#bankModal_{{$bank->id}}">Edit</button></td>
                                                                    <div class="modal" id="bankModal_{{$bank->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                                                        <div class="modal-dialog" role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Bank</h5>
                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                        <span aria-hidden="true">&times;</span>
                                                                                    </button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <form action="{{route('update-bank')}}" method="post">
                                                                                        @csrf
                                                                                        <div class="card">
                                                                                            <div class="card-header">
                                                                                                <h3 class="mb-0 card-title">Edit
                                                                                                    {{$bank->bank ?? '' }}</h3>
                                                                                            </div>
                                                                                            <div class="card-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                            <label class="form-label">Bank Name</label>
                                                                                                            <input type="text" class="form-control" value="{{old('bank_name', $bank->bank) }}" name="bank_name" placeholder="Bank Name">
                                                                                                            @error('bank_name') <i class="text-danger">{{$message}}</i>@enderror
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                            <label class="form-label">Account Name</label>
                                                                                                            <input type="text" class="form-control" name="account_name" value="{{old('account_name', $bank->account_name)}}" placeholder="Account Name">
                                                                                                            @error('account_name') <i class="text-danger">{{$message}}</i>@enderror
                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="form-group">
                                                                                                            <label class="form-label">Account No.</label>
                                                                                                            <input type="text" class="form-control" name="account_no" value="{{old('account_no', $bank->account_no)}}" placeholder="Account No.">
                                                                                                            @error('account_no') <i class="text-danger">{{$message}}</i>@enderror
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="card-footer text-right">
                                                                                                <input type="hidden" name="bank_id" value="{{$bank->id}}">
                                                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
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
                                <div class="tab-pane " id="tab9">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <form action="{{route('update-bulk-settings')}}" method="post" autocomplete="off">
                                                @csrf
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h3 class="mb-0 card-title">Bulk SMS</h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <p><strong class="text-danger">Note:</strong> Your Sender ID for bulk SMS service needs to be verified before you'll be able to send SMS. This process usually takes 24-48 hours. Kindly get that done in time.</p>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label class="form-label">Sender ID</label>
                                                                    <input type="text" class="form-control" value="{{old('sender_id',Auth::user()->getTenant->sender_id) }}" maxlength="10" name="sender_id" placeholder="Sender ID">
                                                                    @error('sender_id') <i class="text-danger">{{$message}}</i>@enderror
                                                                </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
