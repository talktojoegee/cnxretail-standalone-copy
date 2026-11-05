@extends('layouts.master-layout')
@section('active-page')
    CNXDrive
@endsection
@section('title')
    Manage Storage
@endsection
@section('extra-styles')

@endsection
@section('breadcrumb-action-btn')
    @include('cnxdrive.partials._file-menu')
@endsection

@section('main-content')
    <div class="card">
        <div class="card-body">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="sub-title">Upload/Create </div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs md-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#home3" role="tab">File</a>
                                <div class="slide"></div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#profile3" role="tab">Folder</a>
                                <div class="slide"></div>
                            </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content card-block">
                            <div class="tab-pane active" id="home3" role="tabpanel">
                                <form action="{{route('upload-files')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">File Name</label>
                                        <input type="text" name="file_name" placeholder="File Name" class="form-control">
                                        @error('file_name')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Attachment</label>
                                        <input type="file" name="attachments[]" class="form-control-file" multiple>
                                        @error('attachment')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                        <input type="hidden" name="folder" value="0">
                                    </div>
                                    <hr>
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i> Back</a>
                                            <button type="submit" class="btn btn-mini btn-primary"><i class="ti-check mr-2"></i> Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="profile3" role="tabpanel">
                                <form action="{{route('create-folder')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">Folder Name</label>
                                        <input type="text" name="folder_name" placeholder="Folder Name" class="form-control">
                                        @error('folder_name')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Parent Folder</label>
                                        <select name="parent_folder" id="parent_folder" class="form-control">
                                            <option value="0" selected>None</option>
                                            @foreach($folders as $folder)
                                                <option value="{{$folder->id}}">{{$folder->folder ?? ''}}</option>
                                            @endforeach
                                        </select>
                                        @error('parent_folder')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Visibility</label>
                                        <select name="visibility" id="visibility" class="form-control">
                                            <option disabled selected>--Select visibility--</option>
                                            <option value="1">Private</option>
                                            <option value="2">Public</option>
                                        </select>
                                        @error('visibility')
                                        <i class="text-danger mt-2">{{$message}}</i>
                                        @enderror
                                    </div>
                                    <hr>
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="btn-group">
                                            <a href="{{url()->previous()}}" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i> Back</a>
                                            <button type="submit" class="btn btn-mini btn-primary"><i class="ti-check mr-2"></i> Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <h5 class="sub-title"> Document Storage</h5>
                        @if (session()->has('success'))
                            <div class="alert alert-success background-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="icofont icofont-close-line-circled text-white"></i>
                                </button>
                                {!! session()->get('success') !!}
                            </div>
                        @endif
                        <div class="row">
                            @foreach($folders as $folder)
                                @if($folder->parent_id == 0)
                                    <div class="col-md-2">
                                        <a href="{{route('open-folder', $folder->slug)}}" title="{{$folder->folder ?? 'No name'}}" data-original-title="{{$folder->folder ?? 'No name'}}" style="cursor: pointer;">
                                            <img src="/assets/formats/folder.png" height="64" width="64" alt="{{$folder->folder ?? 'No name'}}"><br>
                                            {{strlen($folder->folder ?? 'No name') > 20 ? substr($folder->folder ?? 'No name',0,17).'...' : $folder->folder ?? 'No name'}}
                                        </a>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="dropdown-secondary dropdown float-right">
                                                    <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                        <div class="dropdown-divider"></div>
                                                        <a class="dropdown-item waves-light waves-effect deleteFolder" data-toggle="modal" data-target="#deleteFolderModal"  data-folder="{{$folder->folder ?? 'File name'}}" data-fid="{{$folder->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @foreach ($files as $file)
                                @switch(pathinfo($file->filename, PATHINFO_EXTENSION))
                                    @case('pptx')
                                    <div class="col-md-2">
                                        <a href="button" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}" style="cursor: pointer;">
                                            <img src="/assets/formats/ppt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>

                                    @break
                                    @case('pdf')
                                    <div class="col-md-2 mb-4">
                                        <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}" style="cursor: pointer;">
                                            <img src="/assets/formats/pdf.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"> <br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break

                                    @case('csv')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/csv.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('xls')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/xls.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('xlsx')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/xls.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('doc')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/doc.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('doc')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/doc.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('docx')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/doc.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('jpeg')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/jpg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('jpg')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/jpg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('png')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;"  data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/png.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('gif')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/gif.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('ppt')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/ppt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('txt')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/txt.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('css')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/css.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"> <br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('mp3')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/mp3.png" height="64" width="64" alt=""><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('mp4')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/mp4.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('svg')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/svg.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('xml')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/xml.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                    @case('zip')
                                    <div class="col-md-2">
                                        <a href="button" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="{{$file->name ?? 'No name'}}" data-original-title="{{$file->name ?? 'No name'}}">
                                            <img src="/assets/formats/zip.png" height="64" width="64" alt="{{$file->name ?? 'No name'}}"><br>
                                            {{strlen($file->name ?? 'No name') > 10 ? substr($file->name ?? 'No name',0,7).'...' : $file->name ?? 'No name'}}
                                        </a>
                                        <div class="dropdown-secondary dropdown float-right">
                                            <button class="btn btn-default btn-mini dropdown-toggle waves-light b-none txt-muted" type="button" id="dropdown6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-navigation-menu"></i></button>
                                            <div class="dropdown-menu" aria-labelledby="dropdown6" data-dropdown-in="fadeIn" data-dropdown-out="fadeOut" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 24px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <a class="dropdown-item waves-light waves-effect" href="{{route('download-attachment',$file->filename)}}"><i class="ti-download text-success mr-2"></i> Download</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item waves-light waves-effect deleteFile"  data-toggle="modal" data-target="#deleteModal" data-directory="{{$file->filename}}" data-file="{{$file->name ?? 'File name'}}" data-unique="{{$file->id}}" href="javascript:void(0);"><i class="ti-trash mr-2 text-danger"></i> Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @endswitch
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('delete-file')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">This action cannot be undone. Are you sure you want to delete <strong id="file"></strong>?</label>
                                </div>
                            </div>
                            <input type="hidden" name="directory" id="directory">
                            <input type="hidden" name="key" id="key">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button data-dismiss="modal" type="button" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i>Cancel</button>
                            <button type="submit" class="btn btn-primary btn-mini"><i class="ti-check mr-2"></i>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="deleteFolderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-warning" id="exampleModalLabel">Warning</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('delete-folder')}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">This action cannot be undone. Are you sure you want to delete <strong id="folderpop"></strong>?</label>
                                </div>
                            </div>
                            <input type="hidden" name="folder_key" id="folder_key">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group">
                            <button data-dismiss="modal" type="button" class="btn btn-danger btn-mini"><i class="ti-close mr-2"></i>Cancel</button>
                            <button type="submit" class="btn btn-primary btn-mini"><i class="ti-check mr-2"></i>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('extra-scripts')
    <script>
        $(document).ready(function(){
            $(document).on('click', '.deleteFile', function(e){
                e.preventDefault();
                var directory = $(this).data('directory');
                var file = $(this).data('file');
                var id = $(this).data('unique');
                $('#file').text(file);
                $('#directory').val(directory);
                $('#key').val(id);
            });

            $(document).on('click', '.deleteFolder', function(e){
                e.preventDefault();
                var folder = $(this).data('folder');
                var id = $(this).data('fid');
                $('#folderpop').text(folder);
                $('#folder_key').val(id);
            });
        });
    </script>
@endsection
