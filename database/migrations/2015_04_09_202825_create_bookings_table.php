<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->timestamps();
			$table->date('booking_date');
			$table->integer('time_slot_id');
			$table->integer('player1_id');
			$table->integer('player2_id');
			$table->integer('court_id');
			$table->integer('booking_cat_id');
			$table->string('booking_description');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bookings');
	}

}
