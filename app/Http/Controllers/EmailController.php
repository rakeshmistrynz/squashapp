<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Booking;
use App\Time_slot;
use App\User;
use App\Bookings_category;

class EmailController extends Controller
{
    /**
     * Send out Email to users who's bookings have been cancelled by an Administrator making a booking
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function admin_booking_cancellation(Request $request)
    {

        $cancelled_booking_data = session('booking_data');

        foreach($cancelled_booking_data as $data){

            foreach ($data as $key => $value) {

                $name = $data[$key]['name'];

                $email = $data[$key]['email'];

                $booking_list = $data[$key]['booking_list'];

                Mail::send('emails.admin_cancellation', ['data' => $booking_list, 'name'=>$name], function ($message) use ($email, $name) {
                    $message->to($email, $name)->subject('Test - Booking cancelled');
                });
            }
        }

        session()->flash('flash_message', 'Your booking was successful');

        return redirect('administrator/block-bookings');


    }

    /**
     * Email Booking Confirmation
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function booking_confirmation(Request $request)
    {
        $booking_data = session('booking_data');

        $data = $this->prepare_email_data($booking_data);

        $emails = $data['emails'];

        Mail::send('emails.confirmation', ['data'=>$data], function($message) use ($emails)
        {
            $message->to($emails)->subject('Test - Booking Confirmed');
        });

        session()->flash('flash_message', 'Your booking was successful');

        session()->flash('flash_time', Booking::find($booking_data->id)->created_at);

        return redirect('bookings/my-bookings');
    }

    public function booking_cancellation(Request $request)
    {
        $booking_data = session('booking_data');

        $data = $this->prepare_email_data($booking_data);

        $emails = $data['emails'];

        Mail::send('emails.cancellation', ['data'=>$data], function($message) use ($emails)
        {
            $message->to($emails)->subject('Test - Booking Cancelled');
        });

        session()->flash('flash_message', 'Your booking was cancelled');

        return redirect('bookings/my-bookings');
    }

    private function prepare_email_data($booking_data){

        $data = [];

        $emails = [];

        $data['names']['player1'] = User::find($booking_data->player1_id)->first_name;

        $data['names']['player2'] = ($booking_data->player2_id)? User::find($booking_data->player2_id)->first_name : false;

        $data['time_slot_id'] = Time_slot::find($booking_data->time_slot_id)->time_slot;

        $data['booking_date'] = $booking_data->booking_date;

        $data['court_id'] = $booking_data->court_id;

        $emails[] = User::find($booking_data->player1_id)->email;

        ($booking_data->player2_id)? $emails[] = User::find($booking_data->player2_id)->email : false;

        $data['emails'] = $emails;

        return $data;


    }

}
