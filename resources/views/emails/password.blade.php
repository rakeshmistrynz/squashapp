@extends('emails.email')
@section('content')
<h1>Hello {{$user->first_name}},</h1>
Click <a href="{{url('password/reset/'.$token)}}">here</a> to reset your password
@endsection
