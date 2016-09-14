@extends('app')
@section('body.tag')
    <body data-controller="admin" data-action="notices">
    @endsection
    @section('content')
        <h1 class="text-center">CLUB NOTICES</h1>
        @include('partials.errors')
        @if(count($notices)==0)
            <h4 class="text-center">
                THERE ARE NO CLUB NOTICES.
            </h4>
        @else
            <h4 class="text-center">
                EDIT OR REMOVE A NOTICE
            </h4>
            <div class="notice-wrapper">
                <div class="notice-columns">
                    @foreach ($notices as $notice)
                        @if($notice->file_name)
                            <div class="pin">
                                <div>
                                    <div>
                                        <a href="{{(asset('notices/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->file_name))}}">
                                            @if($notice->image_name != 'default.jpg')
                                                <img src="{{asset('images/notice-image/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->image_name)}}"
                                                     alt="" width="100%">
                                            @else
                                                <img src="{{asset('images/notice-image/'.$notice->image_name)}}" alt=""
                                                     width="100%">
                                            @endif
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{(asset('notices/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->file_name))}}">
                                            <h5>{{$notice->headline}}</h5></a>

                                        <p class="excerpt">{!!$notice->body!!}</p>
                                    </div>
                                    <div>
                                        <ul class="list-unstyled post-details">
                                            <li>Posted: {{(date('l j F Y', strtotime($notice->created_at)))}}</li>
                                            <li>Author: {{$notice->author->first_name or 'Silverdale Squash Club'}}</li>
                                        </ul>
                                        <a href="#" class="btn btn-default btn-primary remove-notice"
                                           data-noticeid="{{$notice->id}}"><i class="fa fa-trash"></i></a>
                                        <a href="view-notice/{{$notice->id}}"
                                           class="btn btn-default btn-primary edit-notice"><i
                                                    class="fa fa-pencil-square-o"></i></a>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="pin">
                                <div>
                                    <div>
                                        @if($notice->image_name != 'default.jpg')
                                            <img src="{{asset('images/notice-image/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->image_name)}}"
                                                 alt="" width="100%">
                                        @else
                                            <img src="{{asset('images/notice-image/'.$notice->image_name)}}" alt=""
                                                 width="100%">
                                        @endif
                                    </div>
                                    <div>
                                        <h5>{{$notice->headline}}</h5>

                                        <p class="excerpt">{!!$notice->body!!}</p>
                                    </div>
                                    <div>
                                        <ul class="list-unstyled post-details">
                                            <li>Posted: {{(date('l j F Y', strtotime($notice->created_at)))}}</li>
                                            <li>Author: {{$notice->author->first_name or 'Silverdale Squash Club'}}</li>
                                        </ul>
                                        <a href="#" class="btn btn-default btn-primary remove-notice"
                                           data-noticeid="{{$notice->id}}"><i class="fa fa-trash"></i></a>
                                        <a href="view-notice/{{$notice->id}}"
                                           class="btn btn-default btn-primary edit-notice"><i
                                                    class="fa fa-pencil-square-o"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @endif
                </div>
            </div>

            <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal"
                 aria-hidden="true">
                <div class="modal-dialog modal-sm">
                    <div class="smallModal modal-content delete-modal">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h5 class="modal-title" id="myModalLabel">Delete Notice</h5>
                        </div>
                        <div class="modal-body">
                            <h5 class="text-center">Delete this Club Notice?</h5>
                        </div>
                        <div class="modal-footer">
                            {!! Form::open(['url' => 'administrator/delete-notice','id'=>'deleteform', 'method'=>'POST'])!!}
                            {!!FORM::hidden('notice_id')!!}
                            {!! Form::close() !!}
                            <button type="submit" form="deleteform" class="btn btn-danger book-btn">DELETE</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>
@endsection
