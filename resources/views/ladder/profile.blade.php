@extends('app')

@section('body.tag')
<body data-controller="ladder" data-action="profile">
@endsection

@section('content')
	<h1 class="text-center">LADDER PROFILE</h1>
	<div class="biege">
		<div class="col-md-4 col-md-offset-4">
			<h4 class="text-center"></h4>
			<div class="text-center">
				<img src="{{asset('/images/squashplayer.png')}}">
			</div>
		</div>
	</div>
	<div class="biege">
		<div class="col-md-4 col-md-offset-4 pie-chart-container">
			<svg class="pie-chart"></svg>
			<div class="col-md-3 col-xs-3 loss-percentage text-right stat-num"><span class="stat-num">0</span>%</div>
			<div class="col-md-3 col-xs-3 win-percentage col-md-offset-6 col-xs-offset-6 text-left stat-num"><span class="stat-num">0</span>%</div>
			<div class="col-md-3 col-xs-3 loss-percentage stat-text">LOSS</div>
			<div class="col-md-3 col-xs-3 col-md-offset-6 col-xs-offset-6 win-percentage text-left stat-text">WIN</div>
		</div>
	</div>
	<div class="biege">
		<div class="col-md-4 col-md-offset-4 stats-container">
			<div class="col-md-4 col-xs-4 text-right stat-text">
				<span class="points stat-num text-right">0</span>POINTS
			</div>
			<div class="col-md-4 col-xs-4 text-center stat-text">
				<span class="matches stat-num">0</span>GAMES PLAYED
			</div>	
			<div class="col-md-4 col-xs-4 text-left stat-text">
				<span class="stat-num">#</span><span class="ranking stat-num">0</span>RANKING
			</div>
		</div>
	</div>
</div>
@endsection
