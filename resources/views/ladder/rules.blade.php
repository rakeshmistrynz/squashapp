@extends('app')

@section('body.tag')
<body data-controller="ladder" data-action="rules">
@endsection

@section('content')
	<h1 class="text-center">CLUB LADDER</h1>
		<div>
			<h4 class="text-center">POINTS SYSTEM</h4>
			<div class="col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4">
				<ul class="rules list-group text-center">
					<li class="list-group-item">BEST OUT OF 5 GAMES OR 3 GAMES</li>
					<li class="list-group-item">10 POINTS PER PLAYER PER MATCH</li>
					<li class="list-group-item">10 POINTS FOR A PLAYER WINNING A GAME</li>
					<li class="list-group-item">10 POINTS FOR A PLAYER WINNING A MATCH</li>
					<li class="list-group-item">20 BONUS POINTS FOR WINNING BEST OUT OF 5 MATCH IN 3 GAMES</li>
					<li class="list-group-item">10 BONUS POINTS FOR WINNING BEST OUT OF 5 MATCH IN 4 GAMES</li>
					<li class="list-group-item">50 POINTS TO A PLAYER WINNING BY DEFAULT</li>
					<li class="list-group-item">0 POINTS FOR A PLAYER DEFAULTING</li>
					<li class="list-group-item">CLUB ADMINISTRATOR TO SETTLE DISPUTES</li>
				</ul>
			</div>
		</div>
@endsection
