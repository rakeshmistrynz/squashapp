@extends('app')

@section('body.tag')
<body data-controller="notices" data-action="show">
@endsection

@section('content')
	<h1 class="text-center">CLUB NOTICES</h1>
	@if(count($notices)==0)
		<h4 class="text-center">
			THERE ARE NO CLUB NOTICES.
		</h4>
	@else
		<h4 class="text-center">
			NOTICE BOARD
		</h4>
		<div class="notice-wrapper">
			<div class="notice-columns">

				@foreach ($notices as $notice)
					@if($notice->file_name)
						<div class="pin">
							<div>
								<div>
									<a href="{{(asset('notices/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->file_name))}}">
										@if($notice->image_name != 'default.jpg')
											<img src="{{asset('images/notice-image/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->image_name)}}" alt="" width="100%">
										@else
											<img src="{{asset('images/notice-image/'.$notice->image_name)}}" alt="" width="100%">
										@endif
									</a>
								</div>
								<div>
									<a href="{{(asset('notices/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->file_name))}}"><h5>{{$notice->headline}}</h5></a>
									<p class="excerpt">{!! $notice->body !!}</p>
								</div>
								<div>
									<ul class="list-unstyled post-details">
										<li>Posted: {{(date('l j F Y', strtotime($notice->created_at)))}}</li>
										<li>Author: {{$notice->author->first_name or 'Silverdale Squash Club'}}</li>
									</ul>
								</div>
							</div>
						</div>
					@else
						<div class="pin">
							<div>
								<div>
									@if($notice->image_name != 'default.jpg')
										<img src="{{asset('images/notice-image/'.date('Y',strtotime($notice->created_at)).'/'.date('F',strtotime($notice->created_at)).'/'.$notice->image_name)}}" alt="" width="100%">
									@else
										<img src="{{asset('images/notice-image/'.$notice->image_name)}}" alt="" width="100%">
									@endif
								</div>
								<div>
									<h5>{{$notice->headline}}</h5>
									<p class="excerpt">{!! $notice->body !!}</p>
								</div>
								<div>
									<ul class="list-unstyled post-details">
										<li>Posted: {{(date('l j F Y', strtotime($notice->created_at)))}}</li>
										<li>Author: {{$notice->author->first_name or 'Silverdale Squash Club'}}</li>
									</ul>
								</div>
							</div>
						</div>
					@endif
				@endforeach
				@endif
			</div>
		</div>
@endsection
