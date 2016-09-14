@if (Session::has('flash_message'))
	<div class="alert alert-success text-center">
		<button aria-hidden="true" class="close" data-dismiss="alert" type="button">&times;</button>
		{{Session::get('flash_message')}}
	</div>
@endif  