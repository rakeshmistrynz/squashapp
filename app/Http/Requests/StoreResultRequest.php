<?php namespace App\Http\Requests;

use App\Booking;
use App\Http\Requests\Request;
use Validator;
use App\Result;

class StoreResultRequest extends Request
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

            'result_by_default' => 'required|numeric',
            'win' => 'required|numeric',
            'match_id' => 'required|numeric'

        ];
    }

    public function messages()
    {

        return [

            'user_score.required' => 'Score Required',
            'opponent_score.required' => 'Score Required'

        ];
    }

    /**
     * Check that if the result was not be default, a user and opponent score has been entered.
     */
    public function validator()
    {

        $validator = Validator::make($this->input(), $this->rules(), $this->messages());

        $validator->sometimes(['user_score', 'opponent_score'], 'required|numeric', function ($input) {

            return $input->result_by_default > 0;
        });

        if (method_exists($this, 'moreValidation')) {

            $this->moreValidation($validator);
        }

        return $validator;
    }

    /**
     * Check that losers score is not higher than the winners score.
     */
    public function moreValidation($validator)
    {
        $match = Result::where('match_id', '=', $this->input('match_id'))->first();

        $booking = Booking::find($this->input('match_id'));

        $validator->after(function ($validator) use ($match,$booking) {

            if ($this->input('win')) {
                if ($this->input('user_score') < $this->input('opponent_score')) {
                    $validator->errors()->add('user_score', ' Your score must be higher than your opponent');
                }
            } elseif (!$this->input('result_by_default')) {

                if ($this->input('user_score') > $this->input('opponent_score')) {
                    $validator->errors()->add('user_score', ' Your score must be lower than your opponent');
                }
            };

            if($match->created_at != $match->created_at){
                $validator->errors()->add('match_id', 'Match score already entered by opponent');

            };

            if($booking->booking_date > date('Y-m-d')){
                $validator->errors()->add('match_id', 'Match has not been played yet');

            }

        });
    }
}
