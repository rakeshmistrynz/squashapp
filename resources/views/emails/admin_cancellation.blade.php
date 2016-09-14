@extends('emails.email')
@section('content')
<h1>Hi {{$name}}</h1>
<p class="lead">The following bookings under your name have been cancelled by a club administrator to make a block
    booking:</p>
<ul>
    <?php

    foreach ($data as $key => $value) {
        echo '<li>' . date("l d F Y", strtotime($data[$key]['date'])) . ' @ ' . date("g:i a", strtotime($data[$key]['time'])) . ' on Court ' . $data[$key]['court'] . '</li>';
    }
    ?>
</ul>
@endsection

