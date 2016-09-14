@if ($errors->any())
	<div class="container-fluid">
		<div class="row alert alert-danger">
			<div class="col-md-4 col-md-offset-4">
				<button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
				<ul class="text-center list-unstyled">
					@foreach ($errors->all() as $error)
						<li>{{$error}}</li>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
@endif
@if (Session::has('flash_message'))
	<div class="alert alert-success text-center">
		<button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
		{{Session::get('flash_message')}}
	</div>
@endif 