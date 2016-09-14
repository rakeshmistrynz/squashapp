@extends('emails.email')
@section('content')
    <h1>
        Hi {{($data['names']['player2'])? $data['names']['player1'].' & '.$data['names']['player2'] : $data['names']['player1']}}</h1>

    <p class="lead">The following bookings under your name have been cancelled:</p>

    <ul>
        <li>{{date("l d F Y",strtotime($data['booking_date'])).' @ '.date("g:i a",strtotime($data['time_slot_id'])).' on Court '.$data['court_id']}}</li>
    </ul>
@endsection

