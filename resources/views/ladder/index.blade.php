@extends('app')

@section('body.tag')
<body data-controller="ladder" data-action="index">
@endsection

@section('content')
	<h1 class="text-center">CLUB LADDER</h1>
			<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4">
				<h4 class="text-center">LEADER BOARD</h4>
				<ul id="the-ladder" class="list-group"></ul>
			</div>
@endsection
