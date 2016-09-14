<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Booking;
use App\Time_slot;
use App\User;
use App\Result;
use App\Bookings_category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Response;

class BookingsController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Bookings Controller
    |--------------------------------------------------------------------------
    |
    | This controller contains the page view and logic for the booking section of the application.
    | The methods included in this controller are for making a booking and viewing a users existing bookings.
    |
    */

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a list of the users bookings and order by date, ascending.
     *
     * @return Response
     */
    public function list_bookings()
    {

        $current_date = date('Y-m-d');

        $bookings = Booking::where('booking_date', '>=', $current_date)->where('booking_cat_id', '<', '4')
            ->where(function ($query) {
                $query->where('player1_id', '=', \Auth::user()->id)
                    ->orWhere('player2_id', '=', \Auth::user()->id);

            })->orderBy('booking_date')->orderBy('time_slot_id')->get();

        foreach ($bookings as $booking) {

            if ($booking->player2_id) {

                $opponent = $booking->player2_id;

                $user = \Auth::user()->id;

                $opponent = ($opponent == $user) ? User::withTrashed()->find($booking->player1_id) : User::withTrashed()->find($booking->player2_id);

                $booking['opponent'] = $opponent->first_name;

            }
        }

        return view('bookings.index', compact('bookings'));
    }

    /**
     * Show the booking calender. If the user is not admin remove the blocking booking category.
     *
     * @return Response
     */
    public function create_booking()
    {
        $categories = Bookings_category::lists('category_description', 'id');

        array_pop($categories);

        $categories = array_map(function($value){return strtoupper($value);}, $categories);

        return view('bookings.create', compact('categories'));
    }

    /**
     * Store a newly created booking. If the booking is for a ladder match
     * store a record for each player in the results table, to enter their results once
     * they have completed their match. Refer to Ladder controller.
     *
     * @return Response
     */
    public function store_booking(StoreBookingRequest $request)
    {

        $booking = Booking::create($request->all());

        if ($booking->booking_cat_id == 3) {

            $player1 = new Result;

            $player1->match_id = $booking->id;

            $player1->player_id = $booking->player1_id;

            $player1->save();

            $player2 = new Result;

            $player2->match_id = $booking->id;

            $player2->player_id = $booking->player2_id;

            $player2->save();

        };

        $booking_data = Booking::find($booking->id);

        session()->flash('booking_data', $booking_data);

        //with array of emails dates and booking info - go to a get request - create session with cancellation data.
        return redirect('email/booking-confirmation');

        //session()->flash('flash_message', 'Your booking was successful');

        //return redirect('bookings/my-bookings');
    }

    /**
     * Delete a booking form the bookings table.
     * @param  Request $request Input - booking id
     * @return Redirect           Redirect to my-bookings page
     */
    public function destroy_booking(Request $request)
    {
        $booking_id = $request->input('booking_id');

        $booking_info = Booking::find($booking_id);

        Booking::destroy($booking_id);

        session()->flash('booking_data', $booking_info);

        return redirect('email/booking-cancellation');
    }

    /**
     * Handles ajax request to get the search result to find another club memeber.
     * @param  Request $request incoming request object
     * @return JSON             A list of results
     */
    public function get_users(Request $request)
    {

        $term = $request->input('name');

        $results = [];

        $queries = User::where('first_name', 'LIKE', '%' . $term . '%')
            ->orWhere('last_name', 'LIKE', '%' . $term . '%')->get();

        foreach ($queries as $query) {


            ($query->id != \Auth::user()->id) ? $results[] = ['id' => $query->id, 'name' => $query->first_name . ' ' . $query->last_name] : false;
        }

        return response()->json($results);

    }

    /**
     * Get all the bookings and available timeslots responding to an ajax request when user enters date to see available booking times
     * @param  Request $request user input('date')
     * @return JSON Object           return a JSON object of available booking times for courts for a user specified date.
     */
    public function get_timeslots(Request $request)
    {


        $date = $request->input('date');

        $response = [];

        $response['court_1'] = $this->getExistingBookings('1', $date);

        $response['court_2'] = $this->getExistingBookings('2', $date);

        $response['court_3'] = $this->getExistingBookings('3', $date);

        return response()->json($response);
    }

    /**
     * Check for any booking conflict and whether booking complies with user booking rules
     * @param  Request $request input
     * @return JSON object
     */
    public function booking_check(Request $request)
    {
        $response = [];

        if(!in_array(\Auth::user()->user_type, config('squash.club+member'))){

            $response['junior_booking'] = $this->checkifJunior($request->input('timeslot_id'));
            $response['user_booking'] = $this->checkifUserBooked($request->input('selected_date'));
        };

        $response['existing_booking'] = $this->checkIfExistingBooking($request->input('court_id'), $request->input('selected_date'), $request->input('timeslot_id'));

        //$response['con_booking'] = $this->checkConsecutiveBooking($request->input('court_id'), $request->input('selected_date'), $request->input('timeslot_id'));
        //$response['double_booking'] = $this->checkDoubleBooking($request->input('selected_date'), $request->input('timeslot_id'));

        return response()->json($response);
    }

    /**
     * Get all of the time-slots as an array
     * @param  Eloquent -query $db_time_slots gets all time-slot descriptions
     * @return array    $array          array of time-slots with the id's as keys
     */
    private function makeTimeSlots($db_time_slots, $court_id)
    {

        $array = [];

        $time_slots = $db_time_slots;

        foreach ($time_slots as $timeslot) {

            $array[$timeslot->id]['time'] = date('g:i A', strtotime($timeslot->time_slot));

            $array[$timeslot->id]['court_id'] = $court_id;

            $array[$timeslot->id]['timeslot_id'] = $timeslot->id;
        };

        return $array;

    }

    /**
     * Find any existing bookings for a specified date and court. Insert them into the array of time-slots
     * @param  int $court court id
     * @param  string $date date
     * @return array        an array of times-slots and any bookings for a time-slot with the name of user making the booking
     */
    private function getExistingBookings($court, $date)
    {

        $array = $this->makeTimeSlots(Time_slot::all(), $court);

        $bookings = Booking::where('court_id', '=', $court)->where('booking_date', '=', $date)->get();

        foreach ($bookings as $booking) {

            $player1 = ($booking->player1_id) ? User::withTrashed()->find($booking->player1_id)->first_name . ' ' . User::withTrashed()->find($booking->player1_id)->last_name : false;

            $player2 = ($booking->player2_id) ? User::withTrashed()->find($booking->player2_id)->first_name . ' ' . User::withTrashed()->find($booking->player2_id)->last_name : false;

            $array[$booking->time_slot_id]['booking_id'] = $booking->id;

            $array[$booking->time_slot_id]['booking_date'] = $booking->booking_date;

            $array[$booking->time_slot_id]['player1'] = $player1;

            $array[$booking->time_slot_id]['player2'] = $player2;

            if(\Auth::user()->user_type == 'administrator'|| \Auth::user()->user_type == 'coach'){

                $array[$booking->time_slot_id]['admin'] = true;
            }

            $array[$booking->time_slot_id]['cat_id'] = $booking->booking_cat_id;

            $array[$booking->time_slot_id]['booking_description'] = ($booking->booking_description) ? strtoupper($booking->booking_description) : strtoupper(Bookings_category::find($booking->booking_cat_id)->category_description);
        }

        return $array;
    }

    /**
     * checkIfExistingBooking - db query
     * @param  int $court court id
     * @param  date $date date
     * @param  int $timeslot time slot id
     * @return boolean
     */
    private function checkIfExistingBooking($court, $date, $timeslot)
    {

        return $booking = Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('time_slot_id', '=', $timeslot)->exists();

    }

    /**
     * Check for a Consecutive Booking
     * @param  int $court court id
     * @param  date $date date
     * @param  int $timeslot time slot id
     * @return boolean
     */
    private function checkConsecutiveBooking($court, $date, $timeslot)
    {

        $booking_before = Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('player1_id', '=', \Auth::user()->id)->where('time_slot_id', '=', ($timeslot - 1))->exists();

        $booking_after = Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('player1_id', '=', \Auth::user()->id)->where('time_slot_id', '=', ($timeslot + 1))->exists();

        $booking = ($booking_before || $booking_after) ? true : false;

        return $booking;
    }

    /**
     * Check for a Double Booking
     * @param  date $date
     * @param  int $timeslot time slot id
     * @return boolean
     */
    private function checkDoubleBooking($date, $timeslot)
    {

        return $booking = Booking::where('booking_date', '=', $date)->where('player1_id', '=', \Auth::user()->id)->where('time_slot_id', '=', $timeslot)->exists();

    }

    /**
     * Check to see if user has existing booking on the selected date.
     * @param $date
     * @return mixed
     */
    private function checkifUserBooked($date)
    {
        return $booking = Booking::where('booking_date', '=', $date)->where('player1_id', '=', \Auth::user()->id)->exists();
    }

    /**
     * Check timeslot between 5-7, not open to Juniors for bookings
     * @param $timeslot
     * @return bool
     */
    private function checkifJunior($timeslot)
    {
        return $booking = (!in_array(\Auth::user()->user_type, config('squash.book_between_5_7pm')) && $timeslot > 13 && $timeslot < 18)? true :false;
    }

}
