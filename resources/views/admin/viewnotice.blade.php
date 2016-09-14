@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="notice">
    @endsection

    @section('content')

        <h1 class="text-center">CLUB NOTICE</h1>
        @include('partials.errors')
        <h4 class="text-center">EDIT NOTICE</h4>
        <div class="col-md-4 col-md-offset-4">
            {!! Form::open(['url'=>'administrator/edit-notice/'.$notice->id, 'method'=>'POST', 'class'=>'form-horizontal','files'=>true]) !!}
            <div class="form-group">
                <label>HEADING</label>
                <input type="text" class="form-control" name="headline" value="{{$notice->headline}}">
            </div>

            <div class="form-group">
                <label>MESSAGE (MAX: 300 CHARACTERS)</label>
                <textarea class="form-control" name="body" rows="20" maxlength="350">{{$notice->body}}</textarea>
            </div>

            <div class="form-group">
                {!! Form::label('NOTICE IMAGE') !!}
                {!! Form::file('image') !!}
            </div>

            <div class="form-group">
                {!! Form::label('PDF') !!}
                {!! Form::file('pdf') !!}
            </div>

            <div class="form-group">
                <div class="col-md-12 text-center">
                    <button type="submit" class="btn btn-primary book-btn">
                        EDIT NOTICE
                    </button>
                </div>
            </div>
            </form>
        </div>
@endsection