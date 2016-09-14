@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="book">
    @endsection

    @section('content')

        <h1 class="text-center">MAKE A BOOKING</h1>
        @include('partials.errors')
                <h4 class="text-center">BOOKING FORM</h4>
                <div class="col-md-4 col-md-offset-4">
                    {!! Form::open(['url'=>'administrator/store-block-booking', 'method'=>"POST", 'class'=>'form-horizontal']) !!}

                    <div class="form-group">
                        {!! Form::label('date','DATE')!!}
                        <div class="input-group date">
                            <input type="text" class="form-control" name="date" value=""><span
                                    class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        {!! Form::label('court','COURT')!!}
                        {!! Form::Select('court',['1'=>'COURT 1','2'=>'COURT 2', '3'=>'COURT 3'], null,['class'=>'form-control'])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('start_time','START TIME')!!}
                        {!! Form::Select('start_time',$start,null,['class'=>'form-control'])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('finish_time','FINISH TIME')!!}
                        {!! Form::Select('finish_time',$finish,null,['class'=>'form-control'])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('description','DESCRIPTION')!!}
                        {!! Form::text('description',null,['class'=>'form-control'])!!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('finish_date','LAST BOOKING DATE (REPEAT BOOKING)')!!}
                        <div class="input-group date">
                            <input type="text" class="form-control" name="finish_date" value=""><span
                                    class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <button type="submit" class="btn btn-primary book-btn">
                                BOOK
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
@endsection