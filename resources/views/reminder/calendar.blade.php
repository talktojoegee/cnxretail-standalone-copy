@extends('layouts.master-layout')
@section('active-page')
    Reminders
@endsection
@section('title')
    Reminders
@endsection
@section('extra-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('/assets/fullcalendar/fullcalendar.min.css')}}">
    <style>
        .fc .fc-view-container .fc-view table .fc-body .fc-widget-content .fc-day-grid-container .fc-day-grid .fc-row .fc-content-skeleton table .fc-event-container .fc-day-grid-event.fc-event{
            padding: 9px 16px;
            border-radius: 20px 20px 20px 0px;
        }
        .fc-title{
            color:white !important;
        }
        .fc-time{
            color: white !important;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link{
            background: #9DCB5C !important;
        }
        .nav-pills .nav-link{
            border-radius: 0px !important;
        }
        .dropdown-menu{
            border:none !important;
        }
        .fc-button{
            color: #fff !important;
        }
    </style>
@endsection
@section('breadcrumb-action-btn')
    <a href="javascript:void(0);" data-toggle="modal" data-target="#reminderModal" class="btn btn-secondary btn-icon text-white">
        <span>
            <i class="fe fe-plus"></i>
        </span> Add New Reminder
    </a>
@endsection

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-body">
                        <div id='fullcalendar'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="reminderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Reminder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route('add-new-reminder')}}" method="post">
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
                                            <label class="form-label">Note</label>
                                            <textarea class="form-control" style="resize: none;" name="conversation" rows="4" placeholder="A brief description"></textarea>
                                            @error('conversation') <i class="text-danger">{{$message}}</i>@enderror
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
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script type="text/javascript" src="{{asset('/assets/moment/moment.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/assets/fullcalendar/fullcalendar.min.js')}}"></script>
    <script src="{{asset('/assets/js/taskCalendar.js')}}"></script>
@endsection
