@extends('app')
@section('body.tag')
    <body data-controller="bookings" data-action="show">
    @endsection
    @section('content')
        <h1 class="text-center">MY BOOKINGS</h1>
        @include('partials.errors')
        <div class="col-md-4 col-md-offset-4 margin-top">
            @if(count($bookings)==0)
                <div class="text-center margin-top">
                    <a class="btn btn btn-info book-btn book-btn-head" role="button" href="{{ url('/bookings/book-a-court') }}">BOOK A COURT &nbsp; <i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
            @else
                <ul class="list-group my-bookings">
                    @foreach ($bookings as $booking)
                        @if($booking->booking_cat_id < 3)
                            @if (Session::has('flash_time') && (Session::get('flash_time')==$booking->created_at))
                                <li class="list-group-item flash">
                            @else
                                    <li class="list-group-item">
                                        @endif
                                    <div class="clearfix">
                                        <a href="#" class="btn btn-default btn-primary remove"
                                           data-bookingid="{{$booking->id}}">&#10005;</a>
                                        <p class="date">{{ strtoupper(date('l j F Y', strtotime($booking->booking_date)))}}</p>
                                    </div>
                                    <p class="time">{{ date('g:ia', strtotime($booking->time->time_slot))}}</p>

                                    <p class="court">COURT:&nbsp;{{$booking->court_id}}</p>

                                    <p class="opponent">{{($booking->player2_id)? 'OPPONENT: '.strtoupper($booking->opponent) : 'TRAINING'}}</p>
                                </li>
                            @elseif(App\Result::where('match_id','=', $booking->id)->whereRaw('updated_at = created_at')->exists())
                                <li class="list-group-item">
                                    <div class="clearfix">
                                        <a href="#" class="btn btn-default btn-primary remove"
                                           data-bookingid="{{$booking->id}}">&#10005;</a>
                                        <p>{{ strtoupper(date('l j F Y', strtotime($booking->booking_date)))}}</p>
                                    </div>
                                    <p>{{ date('g:ia', strtotime($booking->time->time_slot))}}</p>

                                    <p>COURT: {{$booking->court_id}}</p>

                                    <p>{{($booking->player2_id)? 'OPPONENT: '.strtoupper($booking->opponent) : ' '}}</p>
                                </li>
                            @endif
                            @endforeach
                </ul>
            @endif
        </div>
        </div>

        <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal"
             aria-hidden="true">
            <div class="modal-dialog modal-sm delete-modal">
                <div class="smallModal modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h5 class="modal-title" id="myModalLabel">DELETE BOOKING</h5>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-center">Delete Booking?</h5>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open(['url' => 'bookings/book-a-court/delete-booking','id'=>'deleteform', 'method'=>'POST'])!!}
                        {!! Form::hidden('booking_id')!!}
                        {!! Form::close() !!}
                        <button type="submit" form="deleteform" class="btn btn-danger book-btn">DELETE</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
