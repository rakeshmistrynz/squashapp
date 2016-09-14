@extends('app')

@section('body.tag')
    <body data-controller="profile" data-action="photo">
    @endsection

    @section('content')

        <h1 class="text-center">MY DETAILS</h1>
        @include('partials.errors')
        <div class="container-fluid">
            <div class="row">
                <h4 class="text-center">UPLOAD PROFILE PHOTO</h4>
                <div class="text-center col-md-4 col-md-offset-4">
                    <img src="{{asset('/images/profile-image/'.Auth::user()->user_photo_file) }}" class="img-rounded"
                         alt="user-image" width="200" height="200">
                    {!! Form::open(['url'=>'profile/store-photo', 'method'=>'POST', 'class'=>'form', 'files'=>true]) !!}
                    <div class="form-group alert upload-photo">
                        {!! Form::label('image','SELECT IMAGE:',['class'=>""])!!}
                        {!! Form::file('image')!!}
                        <label>Best Image Size - 200px x 200px</label>
                    </div>
                    <div class="form-group col-md-4 col-md-offset-4">
                        {!! Form::submit('UPLOAD', ['class'=>'btn btn-primary book-btn'])!!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
@endsection