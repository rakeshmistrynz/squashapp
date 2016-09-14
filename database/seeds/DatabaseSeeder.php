<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Booking;
use App\Time_slot;
use App\Bookings_category;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		//Model::unguard();
		// $this->call('BookingTableSeeder');
		$this->call('TimeSlotsTableSeeder');
		$this->call('BookingCategoriesTableSeeder');
	}

}

/**
* 
*/
// class BookingTableSeeder extends Seeder
// {
	
// 	function run()
// 	{
		
// 		$booking = [];

// 		$booking = [

// 		'booking_date'=>'2015-09-09',
// 		'time_slot_id'=>'1',
// 		'player1_id'=>'1',
// 		'player2_id'=>'2',
// 		'court_id'=>'1',
// 		'booking_cat_id'=>'3'
// 		];

// 		Booking::create($booking);
// 	}
// }

/**
* 
*/
class TimeSlotsTableSeeder extends Seeder
{
	
	function run()
	{
		$time_slots = $this->createTimeSlots('7.00am','10.00pm');

		foreach ($time_slots as $key => $value) {
			
			Time_slot::create(array('time_slot'=>$value));
		}
	}

	function createTimeSlots($start, $finish){

	$array = [];

	$startTime = "";

	$startTime  = strtotime($start);

	array_push($array, date("H:i:s", $startTime));

	$endTime = strtotime($finish);

		while($startTime < $endTime){

			$startTime = strtotime("+45 minutes", $startTime);

			array_push($array, date("H:i:s", $startTime));
		}

	return $array;

	}
}

/**
* 
*/
class BookingCategoriesTableSeeder extends Seeder
{
	
	function run()
	{
		$booking_cat = [];

		$booking_cat = ['practice','social','ladder','block'];

		foreach ($booking_cat as $key => $value) {
			
			Bookings_category::create(array('category_description'=>$value));
		}
	}
}
