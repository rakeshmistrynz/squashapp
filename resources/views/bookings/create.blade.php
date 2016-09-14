@extends('app')

@section('body.tag')
<body data-controller="bookings" data-action="create">
@endsection

@section('content')

	<h1 class="text-center">BOOKING CALENDAR</h1>
	@include('partials.errors') 
	<div class="col-md-12 bg-white">
		<div class="col-md-4 col-md-offset-4 date-container" id="datepicker">
		</div>
	</div>

	<div class="col-md-12">
		<div class="form-group col-md-12 text-center" id="datevalue">
		</div>
	</div>


		<div class="col-md-12">
			<div class="accordion booking-accordion" id="accordion2">
				<div class="accordion-group col-md-4 col-sm-4">
					<div class="accordion-heading text-center">
						<a class="accordion-toggle btn btn-primary btn-lg btn-block" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne" data-courtid="1">COURT 1</a>
					</div>
					<div id="collapseOne" class="accordion-body collapse in">
						<div class="accordion-inner fixed-height">
						</div>
					</div>
				</div>
				<div class="accordion-group col-md-4 col-sm-4">
					<div class="accordion-heading text-center">
						<a class="accordion-toggle btn btn-primary btn-lg btn-block" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo" data-courtid="2">COURT 2</a>
					</div>
					<div id="collapseTwo" class="accordion-body collapse in">
						<div class="accordion-inner fixed-height">
						</div>
					</div>
				</div>
				<div class="accordion-group col-md-4 col-sm-4">
					<div class="accordion-heading text-center">
						<a class="accordion-toggle btn btn-primary btn-lg btn-block" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree" data-courtid="3">COURT 3</a>
					</div>
					<div id="collapseThree" class="accordion-body collapse in">
						<div class="accordion-inner fixed-height">
						</div>
					</div>
				</div>
			</div>
		</div>

	<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
		<div class="modal-dialog modal-sm booking-modal">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 class="modal-title" id="myModalLabel">BOOKING DETAILS</h5>
				</div>
				<div class="modal-body">
					<div id="date"><h5>DATE:</h5></div>
					<div id="time"><h5>TIME:</h5></div>
					<div id="court"><h5>COURT:</h5></div>

					{!!Form::open(['url' => 'bookings/book-a-court/store-booking', 'method'=>'POST', 'id'=>"myform"])!!}

					<div class="form-group alert alert-info booking-type">

						{!!FORM::label('BOOKING TYPE')!!}

						{!!Form::select('booking_cat_id', $categories, null, ['class'=>'form-control'])!!}

					</div>

					<div id="playing_partner" class="form-group alert alert-info playing-partner">
						{!!FORM::label('SELECT PARTNER')!!}
						<input type="text" class="typeahead form-control" placeholder="SEARCH FOR PLAYERS" autocomplete="off">
					</div>


					{!!FORM::hidden('booking_date')!!}
					{!!FORM::hidden('time_slot_id')!!}
					{!!FORM::hidden('player1_id', \Auth::user()->id)!!}
					{!!FORM::hidden('player2_id')!!}
					{!!FORM::hidden('court_id')!!}


					{!!Form::close()!!}

				</div>
				<div class="modal-footer">
					{{--<button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>--}}
					<button type="submit" form="myform" class="btn btn-primary book-btn create-btn">BOOK</button>
				</div>
			</div>
		</div>
	</div>

	{{--Admin Modal--}}
	<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="smallModal"
		 aria-hidden="true">
		<div class="modal-dialog modal-sm delete-modal">
			<div class="smallModal modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h5 class="modal-title" id="myModalLabel">DELETE BOOKING</h5>
				</div>
				<div class="modal-body">
					<h5 class="text-center alert alert-danger">DELETE BOOKING?</h5>
				</div>
				<div class="modal-footer">
					{!! Form::open(['url' => 'bookings/book-a-court/delete-booking','id'=>'deleteform', 'method'=>'POST'])!!}
					{!! Form::hidden('booking_id')!!}
					{!! Form::close() !!}
					<button type="submit" form="deleteform" class="btn btn-danger book-btn">DELETE</button>
				</div>
			</div>
		</div>
	</div>

@endsection