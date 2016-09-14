<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;

class StoreBookingRequest extends Request
{


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {


        return [

            'booking_cat_id' => 'required|numeric',
            'time_slot_id' => 'required|numeric',
            'player1_id' => 'required|numeric',
            'court_id' => 'required|numeric'
        ];
    }

    public function messages()
    {

        return [

            'player2_id.required' => 'Player Required'

        ];
    }

    /**
     * If booking is not for practice, check that the other player has been entered.
     */
    public function validator()
    {

        $validator = Validator::make($this->input(), $this->rules(), $this->messages());

        $validator->sometimes('player2_id', 'required', function ($input) {

            return $input->booking_cat_id > 1;
        });

        return $validator;
    }

}
