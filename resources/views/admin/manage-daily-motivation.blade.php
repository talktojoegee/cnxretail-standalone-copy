@extends('layouts.admin-layout')
@section('active-page')
    Daily Motivation
@endsection
@section('title')
    Daily Motivation
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
                    <form action="{{route('add-daily-motivation')}}" method="post" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Time of Day</label>
                                    <select name="time" id="time" class="form-control">
                                        <option selected disabled>--Select time of day--</option>
                                        <option value="1">Morning</option>
                                        <option value="2">Afternoon</option>
                                        <option value="3">Evening</option>
                                        <option value="4">Night</option>
                                    </select>
                                    @error('time')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Author</label>
                                    <input type="text"  placeholder="Author" name="author" value="{{ old('author') }}" class="form-control">
                                    @error('author')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group">
                                    <label for="">Motivation</label>
                                    <textarea name="motivation" placeholder="Type motivation here..." id="motivation" style="resize: none;"
                                              class="form-control">{{ old('motivation') }}</textarea>
                                    @error('motivation')<i class="text-danger">{{$message}}</i>@enderror
                                </div>
                                <div class="form-group d-flex justify-content-center">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Submit</button>
                                    </div>
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
                    <h4>Pricing</h4>
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
                                <th class="wd-15p">Author</th>
                                <th class="wd-15p">Motivation</th>
                                <th class="wd-15p">Time</th>
                                <th class="wd-25p">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $serial = 1; @endphp
                            @foreach($motivations as $motivation)
                                <tr>
                                    <td>{{$serial++}}</td>
                                    <td>{{$motivation->author ??  '' }}</td>
                                    <td>{{ strlen($motivation->motivation) > 54 ? substr($motivation->motivation,0,51).'...' : $motivation->motivation  }}</td>
                                    <td>
                                        @switch($motivation->time)
                                            @case(1)
                                            Morning
                                            @break
                                            @case(2)
                                            Afternoon
                                            @break
                                            @case(3)
                                            Evening
                                            @break
                                            @case(4)
                                            Night
                                            @break
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#motivationModal_{{$motivation->id}}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                <div class="modal" id="motivationModal_{{$motivation->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLongTitle">Edit Motivation</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('update-daily-motivation')}}" method="post" autocomplete="off">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="">Time of Day</label>
                                                                <select name="time" id="time" class="form-control">
                                                                    <option value="1" {{$motivation->id == 1 ? 'selected' : '' }}>Morning</option>
                                                                    <option value="2" {{$motivation->id == 2 ? 'selected' : '' }}>Afternoon</option>
                                                                    <option value="3" {{$motivation->id == 3 ? 'selected' : '' }}>Evening</option>
                                                                    <option value="4" {{$motivation->id == 4 ? 'selected' : '' }}>Night</option>
                                                                </select>
                                                                @error('time')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Author</label>
                                                                <input type="text"  placeholder="Author" name="author" value="{{ $motivation->author ?? '' }}" class="form-control">
                                                                @error('author')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="">Motivation</label>
                                                                <textarea name="motivation" id="motivation" style="resize: none;"
                                                                          class="form-control">{{ $motivation->motivation ?? '' }}</textarea>
                                                                @error('motivation')<i class="text-danger">{{$message}}</i>@enderror
                                                            </div>
                                                            <div class="form-group d-flex justify-content-center">
                                                                <input type="hidden" name="daily" value="{{$motivation->id}}">
                                                                <div class="btn-group">
                                                                    <button type="submit" class="btn btn-sm btn-primary"><i class="ti-check mr-2"></i> Save changes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
@endsection
