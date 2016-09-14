@extends('app')

@section('body.tag')
    <body data-controller="admin" data-action="bookings">
    @endsection

    @section('content')
        <h1 class="text-center">CLUB+ MEMBER BOOKINGS</h1>
        @include('partials.errors')
        <div>
            @if(count($booking_data)==0)
                <h4 class="text-center">
                    YOU HAVE NO BLOCK BOOKINGS.
                </h4>
                <a href="{{ url('administrator/create-block-bookings') }}"><h4 class="text-center">CLICK HERE TO
                        MAKE A BLOCK BOOKING</h4></a>
            @else
                <h4 class="text-center">CURRENT BOOKINGS</h4>
                <div class="col-md-4 blk-list">
                    <h4 class="btn-primary btn-lg text-center blk-heading">COURT 1</h4>
                    <ul class="list-group list-unstyled">
                        @foreach ($booking_data as $key => $value)
                            @if($booking_data[$key]['court']=='1')
                                @if($booking_data[$key]['start_date'] >= $current_date || $booking_data[$key]['end_date'] >= $current_date)
                                    <li class="list-group-item">
                                        <a href="#" class="remove" data-bookingid="{{$booking_data[$key]['id']}}">
                                            &#x2715;</a>
                                        <ul class="list-unstyled">
                                            <li class="date">{{ strtoupper(date('l j F Y', strtotime($booking_data[$key]['start_date'])))}}</li>
                                            @if($booking_data[$key]['start_date']!=$booking_data[$key]['end_date'])
                                                <li class="date">{{strtoupper(date('l j F Y', strtotime($booking_data[$key]['end_date'])))}}</li>
                                            @endif
                                            <li class="time">{{$booking_data[$key]['start_time']}} - {{$booking_data[$key]['end_time']}}</li>
                                            <li class="description">{{$booking_data[$key]['booking_description']}}</li>
                                            <li class="created_at">{{$booking_data[$key]['player1']}} | {{date('j F Y', strtotime($booking_data[$key]['created_at']))}}</li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-4 blk-list">
                    <h4 class="btn-primary btn-lg text-center blk-heading">COURT 2</h4>
                    <ul class="list-group list-unstyled">
                        @foreach ($booking_data as $key => $value)
                            @if($booking_data[$key]['court']=='2')
                                @if($booking_data[$key]['start_date'] >= $current_date || $booking_data[$key]['end_date'] >= $current_date)
                                    <li class="list-group-item list-unstyled">
                                        <a href="#" class="remove" data-bookingid="{{$booking_data[$key]['id']}}">
                                            &#x2715;</a>
                                        <ul class="list-unstyled">
                                            <li class="date">{{ strtoupper(date('l j F Y', strtotime($booking_data[$key]['start_date'])))}}</li>
                                            @if($booking_data[$key]['start_date']!=$booking_data[$key]['end_date'])
                                                <li class="date">{{strtoupper(date('l j F Y', strtotime($booking_data[$key]['end_date'])))}}</li>
                                            @endif
                                            <li class="time">{{$booking_data[$key]['start_time']}} - {{$booking_data[$key]['end_time']}}</li>
                                            <li class="description">{{$booking_data[$key]['booking_description']}}</li>
                                            <li class="created_at">{{$booking_data[$key]['player1']}} | {{date('j F Y', strtotime($booking_data[$key]['created_at']))}}</li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>

                <div class="col-md-4 blk-list">
                    <h4 class="btn-primary btn-lg text-center blk-heading">COURT 3</h4>
                    <ul class="list-group list-unstyled">
                        @foreach ($booking_data as $key => $value)
                            @if($booking_data[$key]['court']=='3')
                                @if($booking_data[$key]['start_date'] >= $current_date || $booking_data[$key]['end_date'] >= $current_date)
                                    <li class="list-group-item">
                                        <a href="#" class="remove" data-bookingid="{{$booking_data[$key]['id']}}">
                                            &#x2715;</a>
                                        <ul class="list-unstyled">
                                            <li class="date">{{ strtoupper(date('l j F Y', strtotime($booking_data[$key]['start_date'])))}}</li>
                                            @if($booking_data[$key]['start_date']!=$booking_data[$key]['end_date'])
                                                <li class="date">{{strtoupper(date('l j F Y', strtotime($booking_data[$key]['end_date'])))}}</li>
                                            @endif
                                            <li class="time">{{$booking_data[$key]['start_time']}} - {{$booking_data[$key]['end_time']}}</li>
                                            <li class="description">{{$booking_data[$key]['booking_description']}}</li>
                                            <li class="created_at">{{$booking_data[$key]['player1']}} | {{date('j F Y', strtotime($booking_data[$key]['created_at']))}}</li>
                                        </ul>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal"
             aria-hidden="true">
            <div class="modal-dialog modal-sm delete-modal">
                <div class="smallModal modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&#x2715;</button>
                        <h5 class="modal-title" id="myModalLabel">DELETE BOOKING</h5>
                    </div>
                    <div class="modal-body text-center">
                        <h5 class="text-center">Delete Booking?</h5>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open(['url' => 'administrator/remove-block-booking', 'method'=>'POST', 'id'=>"deleteform"])!!}
                        {!!FORM::hidden('booking_id')!!}
                        {!! Form::close() !!}
                        <button type="submit" form="deleteform" class="btn btn-danger book-btn">DELETE</button>
                    </div>
                </div>
            </div>
        </div>
@endsection
