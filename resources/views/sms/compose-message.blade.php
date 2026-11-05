@extends('layouts.master-layout')
@section('active-page')
    Compose Message
@endsection
@section('title')
    Compose Message
@endsection
@section('extra-styles')
    <link href="/assets/plugins/select2/select2.min.css" rel="stylesheet"/>
@endsection
@section('breadcrumb-action-btn')
    @include('contacts.partials._menu')
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
                    @if(session()->has('error'))
                        <div class="alert alert-warning mb-4">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <strong>Whoops!</strong>
                            <hr class="message-inner-separator">
                            <p>{!! session()->get('error') !!}</p>
                        </div>
                    @endif
                        @if(Auth::user()->getTenant->sender_id_verified == 0)
                            <div class="alert alert-warning mb-4">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <strong>Whoops!</strong>
                                <hr class="message-inner-separator">
                                <p>We're still processing your sender ID verification. You'll not be able to send messages till this process is done. We apologise for the inconvenience.</p>
                            </div>
                        @endif
                    <div class="card-body">
                        <form action="{{route('preview-message')}}" method="get">
                            @csrf
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="mb-0 card-title">Compose SMS</h3>
                                </div>
                                <div class="card-alert alert alert-success mb-0">
                                    New Message
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Sender ID{!! Auth::user()->getTenant->sender_id_verified == 1 ? "<sup><i class='ti-check text-success'></i></sup>" : "<sup><i class='ti-help text-danger'></i></sup>" !!}</label>
                                                <input type="text" class="form-control" value="{{old('sender_id', Auth::user()->getTenant->sender_id) }}" readonly name="sender_id" placeholder="Sender ID">
                                                @error('sender_id') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="from-contact">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Contact</label>
                                                <select name="contact[]" id="contact" class="form-control select2" data-placeholder="Select contact" multiple>
                                                    @foreach($contacts as $contact)
                                                        <option value="{{$contact->id}}">{{$contact->company_name ?? '' }}</option>
                                                    @endforeach
                                                </select>
                                                @error('contacts') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="from-phone-group">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Phone Group</label>
                                                <select name="phonegroup[]" id="phonegroup" class="form-control select2" data-placeholder="Select contact" multiple>
                                                    @foreach($phonegroups as $group)
                                                        <option value="{{$group->id}}">{{$group->group_name ?? '' }} ({{$group->getNumberOfContacts($group->id)}})</option>
                                                    @endforeach
                                                </select>
                                                @error('phonegroup') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="from-phone-numbers">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Numbers</label>
                                                <textarea name="phone_numbers" id="phone_numbers" style="resize: none" placeholder="Enter phone numbers separated by comma"
                                                          class="form-control">{{old('phone_numbers')}}</textarea>
                                                @error('phone_numbers') <i class="text-danger">{{$message}}</i>@enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" id="from-message">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Compose message</label>
                                                <textarea name="message" rows="5" id="message" style="resize: none" placeholder="Compose message here..."
                                                          class="form-control">{{old('message')}}</textarea>
                                                @error('message') <i class="text-danger">{{$message}}</i>@enderror
                                                <p class="text-right text-danger" id="character-counter">0</p>
                                                <br>
                                                <span><strong class="text-danger">Note: </strong> One page of message consists of <code>160</code> characters.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="{{url()->previous()}}" class="btn btn-danger">Back</a>
                                    @if(Auth::user()->getTenant->sender_id_verified != 0)
                                    <button type="submit" class="btn btn-primary">Preview</button>
                                    @endif
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
            $(document).on('keydown','#message', function() {
                var leng = $(this).val();
                $('#character-counter').text(leng.length+1);
            });
            $(document).on('blur','#message', function() {
                var leng = $(this).val();
                $('#character-counter').text(leng.length+1);
            });
        });
    </script>
@endsection
