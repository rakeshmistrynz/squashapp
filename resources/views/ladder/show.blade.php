@extends('app')

@section('body.tag')
    <body data-controller="ladder" data-action="show">
    @endsection

    @section('content')

        <h1 class="text-center">MY MATCHES</h1>
        @include('partials.errors')
            @if(count($list_no_results)!=0)
                <div class="col-md-4 col-md-offset-4">
                    <h4 class='text-center'>AWAITING RESULT</h4>
                    <ul class=" results-list list-group">
                        @foreach ($list_no_results as $match)
                            <li class="list-group-item">
                                <div class="clearfix">
                                    <p class="date">{{ strtoupper(date('l j F Y', strtotime($match->match->booking_date)))}}</p>
                                    <a href="#" class="upload" data-matchid="{{$match->match_id}}"><i class="glyphicon glyphicon-open" aria-hidden="true"></i></a>
                                </div>
                                <p class="time">{{(date('g:i a', strtotime($match->match->time->time_slot)))}}</p>
                                <p class="court">COURT:&nbsp;{{$match->match->court_id}}</p>
                                <p class="opponent">OPPONENT:&nbsp;{{strtoupper($match->opponent)}}</p>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-4 col-md-offset-4">
                @if(count($list_results)==0)
                    <a href="{{ url('/bookings/book-a-court') }}"><h4 class="text-center">CLICK HERE TO MAKE A BOOKING
                            FOR A LADDER MATCH</h4></a>
                @else
                    <h4 class='text-center'>RESULTS</h4>
                    <ul class=" results-list list-group">
                        @foreach ($list_results as $match)
                            <li class="list-group-item">
                                <div class="clearfix">
                                    <p class="date">{{strtoupper(date('l j F Y', strtotime($match->match->booking_date)))}}</p>
                                    @if($match->winner)
                                        <i class="fa fa-trophy"></i>
                                    @endif
                                </div>
                                <p class="time">{{(date('g:i a', strtotime($match->match->time->time_slot)))}}</p>
                                <p class="court">COURT:&nbsp;{{$match->match->court_id}}</span></p>
                                <p class="result"><span>RESULT:</span><span>{{$match->user_games}}&nbsp;:&nbsp;{{$match->opponent_games}}</span>
                                    <span>OPPONENT:&nbsp;{{strtoupper($match->opponent)}}</span></p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

        <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal"
             aria-hidden="true">
            <div class="modal-dialog modal-sm result-modal">
                <div class="smallModal modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" id="myModalLabel">SCORE CARD</h5>
                    </div>

                    <div class="modal-body">

                        <hr>

                        {!! Form::open(['url' => 'ladder/store-result', 'method'=>'POST', 'id'=>'scoreform', 'class'=>'form'])!!}

                        <div class="container-fluid">
                            <div class="row">
                                <span class="win-loss-msg">DID YOU: </span>
                                <div class="text-center">
                                    {!! Form::button('WIN ?',['class'=>'win btn btn-default btn-block','value'=>'1'])!!}
                                </div>
                                <div class="text-center">
                                    {!! Form::button('LOSE ?',['class'=>'loss btn btn-default btn-block', 'value'=>'0'])!!}
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <span class=" win-loss-msg">PLEASE SELECT ONE:</span>
                                <div class="col-md-6">
                                    <div class="radioLabel text-center">ENTER MATCH SCORE</div>
                                    {!! Form::radio('result_by_default', '0', null,['id'=>'radio1']) !!}<label
                                            for="radio1" class="text-center"><span></span></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="radioLabel text-center">RESULT BY DEFAULT</div>
                                    {!! Form::radio('result_by_default', '1', null,['id'=>'radio2']) !!}<label
                                            for="radio2" class="text-center"><span></span></label>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <span class=" win-loss-msg">PLEASE ENTER MATCH SCORE:</span>
                                <div class="col-md-6">
                                    <div class="input-group input-group-sm user_score">
                                        <div class="input-group score-button up"></div>
                                        {!! Form::text('user_score', 0, ['class'=>'form-control'])	 !!}
                                        <div class="input-group score-button down"></div>
                                        <div class="input-group text-center user_name">YOUR MATCH SCORE</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="input-group input-group-sm opponent_score">
                                        <div class="input-group score-button up"></div>
                                        {!! Form::text('opponent_score', 0,['class'=>'form-control'])	 !!}
                                        <div class="input-group score-button down"></div>
                                        <div class="input-group text-center opponent_name">OPPONENT'S MATCH SCORE</div>
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        {!! Form::hidden('match_id')!!}
                        {!! Form::hidden('win')!!}
                        {!! Form::close() !!}
                        <button type="submit" form="scoreform" class="btn btn-primary book-btn" disabled="true">ENTER</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
