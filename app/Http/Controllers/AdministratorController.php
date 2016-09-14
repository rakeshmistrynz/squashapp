<?php namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Booking;
use App\Time_slot;
use App\Result;
use App\Bookings_category;
use App\Notice;
use App\Http\Requests\StoreBlockBookingRequest;
use App\Http\Requests\StoreNewUserRequest;
use App\Http\Requests\StoreNoticeRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserAdminRequest;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Hash;
use Illuminate\Support\Facades\File;


class AdministratorController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Administrator Controller
    |--------------------------------------------------------------------------
    |
    | This controller contains the logic and page views to be renedered for a club administrator. Only a user with
    | registered as 'admin' user can access the administrator functions.
    |
    */


    public function __construct()
    {
        $this->middleware('admin');

    }


    /**
     * Display a list of block bookings for the logged in administrator
     */
    public function block_list()
    {
        $current_date = date('Y-m-d');

        $bookings = Booking::where('booking_cat_id', '=', 4)->get();

        $booking_data = $this->sortBlockBookingsForView($bookings);

        return view('admin.index', compact('booking_data','current_date'));
    }

    /**
     * View to create a block booking
     */
    public function create_booking()
    {

        $start = Time_slot::lists('time_slot', 'id');

        foreach ($start as $key => $value) {

            $start[$key] = date('g:i A', strtotime($value));
        }

        $start[0] = 'ALL DAY';

        $finish = $start;

        $finish = array_splice($finish, -22, -1);

        array_push($finish, '10.45 PM');

        return view('admin.block', compact('start', 'finish'));
    }

    /**
     * Store the block booking. Booking is per court and date.
     * First step is to find out if block booking is repeat - see if a Finish date has been entered, Create a booking for each date, ie every Thursday etc.
     * Else just create a block booking for the selected date and court.
     * - if cancelled booking data send out email to user, with a booking which has been removed by an administrator.
     * @param StoreBlockBookingRequest $request
     * @return Redirect
     */
    public function store_block_bookings(StoreBlockBookingRequest $request)
    {

        if (!null == $request->input('finish_date')) {

            $diff = date_diff(date_create($request->input('date')), date_create($request->input('finish_date')));

            $dates = [];

            $cancelled_booking_data = [];

            $dates[] = $request->input('date');

            for ($i = 1; $i <= ($diff->days / 7); $i++) {

                $week = '+%s week';
                $date = strtotime(sprintf($week, $i), strtotime($request->input('date')));
                $dates[] = date('Y-m-d', $date);
            }

            foreach ($dates as $date) {

                $cancelled_booking_data[] = $this->create_block_bookings($request, $date);
            }

        } else {

            $cancelled_booking_data[] = $this->create_block_bookings($request, $request->input('date'));
        }

        if (!empty($cancelled_booking_data)) {

            session()->flash('booking_data', $cancelled_booking_data);

            //with array of emails dates and booking info - go to a get request - create session with cancellation data.
            return redirect('email/admin-booking-cancellation');

        }

        session()->flash('flash_message', 'Your booking was successful');

        return redirect('administrator/block-bookings');
    }

    /**
     * Stores the booking for store_block_bookings, returns any cancelled booking data to be handled by mailer.
     * @param $request
     * @param $date
     * @return array
     */
    public function create_block_bookings($request, $date)
    {
        $start_time = $request->input('start_time');

        $finish_time = $request->input('finish_time');

        if ($start_time == 0) {

            $start_time = 1; //config.variable get id of first_time_slot
            $finish_time = 21;
        };

        $court = $request->input('court');

        $description = $request->input('description');

        $difference = $finish_time - $start_time;

        $increment = $start_time + $difference;

        $booking_data = [];

        for ($i = $start_time; $i <= $increment; $i++) {

            if ($this->checkIfExistingBooking($court, $date, $i)) {

                $booking_data[] = $this->cancelledBookingData($court, $date, $i);

                $this->overRideExistingBooking($court, $date, $i, $description);

            } else {

                $new_booking = new Booking;

                $new_booking->court_id = $court;

                $new_booking->booking_date = $date;

                $new_booking->time_slot_id = $i;

                $new_booking->booking_description = $description;

                $new_booking->player1_id = \Auth::user()->id;

                $new_booking->booking_cat_id = 4;

                $new_booking->save();

            }

        };

        return $this->sortBookingDataForEmail($booking_data);

    }

    /**
     * Display view to add a new user to the system by the administrator
     */
    public function add_user()
    {

        $types = array_map(function ($value) {
            return ucfirst($value);
        }, config('squash.user_types'));

        return view('admin.register', compact('types'));
    }

    /**
     * Save new user to the database.
     * @param  StoreNewUserRequest $request validated user input details from admin.
     */
    public function save_user(StoreNewUserRequest $request)
    {

        $new_user = new User;

        $new_user->first_name = $request->input('first_name');

        $new_user->last_name = $request->input('last_name');

        $new_user->email = $request->input('email');

        $new_user->user_type = $request->input('user_type');

        $new_user->password = Hash::make($request->input('password'));

        $new_user->save();

        session()->flash('flash_message', 'User has been added');

        return redirect('administrator/block-bookings');

    }

    /**
     * Return view to edit or delete user.
     */
    public function edit_user()
    {
        $types = array_map(function ($value) {
            return ucfirst($value);
        }, array_combine(config('squash.user_types'), config('squash.user_types')));

        return view('admin.user', compact('types'));
    }


    /**
     * Handles ajax request to get the search result to find another club memeber.
     * @param  Request $request incoming request object
     * @return JSON             A list of results
     */
    public function get_user_data(Request $request)
    {

        $term = $request->input('name');

        $results = [];

        $queries = User::where('first_name', 'LIKE', '%' . $term . '%')
            ->orWhere('last_name', 'LIKE', '%' . $term . '%')->get();

        foreach ($queries as $query) {


            ($query->id != \Auth::user()->id) ? $results[] = [
                'id' => $query->id,
                'name' => $query->first_name . ' ' . $query->last_name,
                'first_name' => $query->first_name,
                'last_name' => $query->last_name,
                'email' => $query->email,
                'user_type' => $query->user_type
            ] : false;
        }

        return response()->json($results);

    }

    /**
     * Delete User View
     * @return \Illuminate\View\View
     */
    public function delete_user_view()
    {

        return view('admin.deleteuser');
    }

    /**
     * Delete user from database
     * @param  Request $request validated input(user_id) provided by administrator.
     */
    public function delete_user(Request $request)
    {

        $user = User::find($request->input('user_id'));

        $user->delete();

        session()->flash('flash_message', 'User has been deleted');

        return redirect('administrator/block-bookings');
    }

    /**
     * Upgrade user to administrator
     * @param  Request $request validated input(user_id) provided by administrator.
     */
    public function update_user(UpdateUserAdminRequest $request)
    {

        $user = User::find($request->input('user_id'));

        $user->first_name = $request->input('first_name');

        $user->last_name = $request->input('last_name');

        $user->email = $request->input('email');

        $user->user_type = $request->input('user_type');

        if ($request->input('password')) {

            $user->password = Hash::make($request->input('password'));
        };

        $user->update();

        session()->flash('flash_message', 'User has been updated');

        return redirect('administrator/block-bookings');
    }

    /**
     * Return view to create a new club notice
     */
    public function create_notice()
    {

        return view('admin.createnotice');
    }

    /**
     * Return a view of all the club notices
     * @return [type] [description]
     */
    public function view_notices()
    {

        $notices = Notice::orderBy('created_at')->get();

        return view('admin.viewnotices', compact('notices'));

    }

    /**
     * Return view to edit club notice
     * @param $id
     * @return \Illuminate\View\View
     */
    public function view_notice($id)
    {
        $notice = Notice::find($id);

        return view('admin.viewnotice', compact('notice'));

    }


    /**
     * Save club notice to database
     * @param  StoreNoticeRequest $request validated input details from create notice form.
     */
    public function save_notice(StoreNoticeRequest $request)
    {

        $notice = new Notice;

        $notice->headline = $request->input('headline');

        $notice->body = $request->input('body');

        $notice->author_id = \Auth::user()->id;

        if ($request->file('pdf')) {

            $notice->file_name = $this->store_pdf($request);
        }

        if ($request->file('image')) {

            $notice->image_name = $this->store_image($request);
        }

        $notice->save();

        session()->flash('flash_message', 'Your notice has been posted');

        return redirect('administrator/notices');
    }

    /**
     * Handle Post Request - Edit Club Notice
     * @param StoreNoticeRequest $request
     * @param $id
     * @return Redirect
     */
    public function edit_notice(StoreNoticeRequest $request, $id)
    {

        $notice = Notice::find($id);

        $notice->headline = $request->input('headline');

        $notice->body = $request->input('body');

        $notice->author_id = \Auth::user()->id;

        if ($request->file('pdf')) {

            $notice->file_name = $this->store_pdf($request);
        }

        if ($request->file('image')) {

            $notice->image_name = $this->store_image($request);
        }

        $notice->update();

        session()->flash('flash_message', 'Your notice has been edited');

        return redirect('administrator/notices');
    }

    /**
     * Delete a club notice
     * @param  Request $request Notice id provided by administrator
     */
    public function delete_notice(Request $request)
    {

        $notice = Notice::find($request->input('notice_id'));

        $notice->delete();

        session()->flash('flash_message', 'Your notice has been deleted');

        return redirect('administrator/notices');

    }

    /**
     * Store PDF associated with Club Notice
     * @param $request
     * @return string
     */
    public function store_pdf($request)
    {
        $pdfName = time() . '.' . $request->file('pdf')->getClientOriginalExtension();

        $destination_path = public_path() . '/notices/' . date('Y') . '/' . date('F');

        if (!file_exists($destination_path)) {

            File::makeDirectory($destination_path, 0775, true);
        }

        $request->file('pdf')->move(
            $destination_path, $pdfName
        );

        return $pdfName;

    }

    /**
     * Store Image file associated with Club Notice
     * @param $request
     * @return string
     */
    public function store_image($request)
    {
        $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();

        $destination_path = public_path() . '/images/notice-image/' . date('Y') . '/' . date('F');

        if (!file_exists($destination_path)) {

            File::makeDirectory($destination_path, 0775, true);
        }

        $request->file('image')->move(
            $destination_path, $imageName
        );

        return $imageName;

    }


    /**
     * Delete block booking
     * @param  Request $request booking id
     */
    public function destroy_block_booking(Request $request)
    {
        $the_booking = Booking::find($request->input('booking_id'));

        $get_booking = Booking::where('created_at', '=', $the_booking->created_at)->groupBy('created_at');

        $get_booking->delete();

        session()->flash('flash_message', 'Your booking has been deleted');

        return redirect('administrator/block-bookings');
    }

    /**
     * Check if there is an existing booking where administrator want to do a block booking.
     * @param  int $court court number
     * @param  date $date date
     * @param  int $timeslot time slot id
     * @return boolean
     */
    private function checkIfExistingBooking($court, $date, $timeslot)
    {

        return Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('time_slot_id', '=', $timeslot)->exists();

    }

    /**
     * Delete existing booking, and replace with the block booking.
     * @param  int $court
     * @param  date $date
     * @param  int $timeslot
     * @param  string $description booking description
     * @return boolean
     */
    public function overRideExistingBooking($court, $date, $timeslot, $description)
    {

        Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('time_slot_id', '=', $timeslot)->delete();

        $new_booking = new Booking;

        $new_booking->time_slot_id = $timeslot;

        $new_booking->booking_date = $date;

        $new_booking->court_id = $court;

        $new_booking->booking_description = $description;

        $new_booking->booking_cat_id = 4;

        $new_booking->player1_id = \Auth::user()->id;

        $new_booking->save();

    }

    /**
     * @param $court - string
     * @param $date - string
     * @param $timeslot - string
     * @return array booking data
     */
    public function cancelledBookingData($court, $date, $timeslot)
    {
        $booking_data = Booking::where('booking_date', '=', $date)->where('court_id', '=', $court)->where('time_slot_id', '=', $timeslot)->get();

        return $booking_data;

    }

    /**
     * Sort through booking data to Email through to user. Assign first row of array to user email address. Next row of array contains a list of their bookings which have been cancelled, to be sent inside
     * one email. Note: Remember to return outside loop!
     * @param $bookingData
     * @return array of booking data
     */
    public function sortBookingDataForEmail($bookingData)
    {
        $a = [];

        foreach ($bookingData as $key => $value) {

            foreach ($value as $data) {

                $a[$data->player1_id]['name'] = User::find($data->player1_id)->first_name . ' ' . User::find($data->player1_id)->last_name;;

                $a[$data->player1_id]['email'] = User::find($data->player1_id)->email;

                $a[$data->player1_id]['booking_list'][$data->id]['court'] = $data->court_id;

                $a[$data->player1_id]['booking_list'][$data->id]['time'] = Time_slot::find($data->time_slot_id)->time_slot;

                $a[$data->player1_id]['booking_list'][$data->id]['date'] = $data->booking_date;

                if ($data->player2_id > 0) {

                    $a[$data->player2_id]['name'] = User::find($data->player2_id)->first_name . ' ' . User::find($data->player2_id)->last_name;

                    $a[$data->player2_id]['email'] = User::find($data->player2_id)->email;

                    $a[$data->player2_id]['booking_list'][$data->id]['court'] = $data->court_id;

                    $a[$data->player2_id]['booking_list'][$data->id]['time'] = Time_slot::find($data->time_slot_id)->time_slot;

                    $a[$data->player2_id]['booking_list'][$data->id]['date'] = $data->booking_date;

                }


            }

        }

        return $a;
    }

    /**
     * Sort through existing block booking under users name, first combine repeat booking then create one row with the booking details to loop through for view.
     * @param $bookings
     * @return array
     */
    private function sortBlockBookingsForView($bookings)
    {
        $sorted_bookings = [];

        foreach ($bookings as $booking) {

            $time = $booking->time_slot_id;

            ($time > 0) ? $booking['time_slot_id'] = date('g:i A', strtotime(Time_slot::find($time)->time_slot)) : $booking['time_slot_id'] = 'ALL DAY';

            $sorted_bookings[$booking['created_at']->toDateTimeString()][$booking->booking_date][$booking->time_slot_id] = $booking;
        }

        $display_data = [];

        foreach ($sorted_bookings as $data => $value) {

            $a['id'] = current(current($value))->id;

            $a['start_date'] = current(current($value))->booking_date;

            $a['end_date'] = current(end($value))->booking_date;

            $a['created_at'] = current(current($value))->created_at;

            $a['court'] = (string)current(current($value))->court_id;

            $a['player1'] = User::find(current(current($value))->player1_id)->first_name . ' ' . User::find(current(current($value))->player1_id)->last_name;

            $a['booking_description'] = (string)current(current($value))->booking_description;

            $times = array_keys(current($value));

            $a['start_time'] = reset($times);

            $end_time = strtotime('+45 minutes', strtotime(end($times)));

            $a['end_time'] = date('g:i A', $end_time);

            $display_data[] = $a;
        }

        return $display_data;
    }

}
