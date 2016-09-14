<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Validator;

class StoreBlockBookingRequest extends Request
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

            'date' => 'required|date',
            'court' => 'required|numeric',
            'start_time' => 'required|numeric',
            'description' => 'required'

        ];
    }

    public function messages()
    {

        return [

            'start_time.required' => 'Start time is required',
            'finish_time.required' => 'Finish time is required'

        ];
    }

    /**
     * Check if a finish time is required if block booking is not for an all day block booking.
     */
    public function validator()
    {

        $validator = Validator::make($this->input(), $this->rules(), $this->messages());

        $validator->sometimes('finish_time', 'required|numeric', function ($input) {

            return $input->start != 0;
        });

        if (method_exists($this, 'moreValidation')) {

            $this->moreValidation($validator);
        }

        return $validator;
    }

    /**
     * Check the finish time is not earlier than the start time.
     */
    public function moreValidation($validator)
    {

        $validator->after(function ($validator) {

            if ($this->input('start_time') > 0) {
                if ($this->input('start_time') > $this->input('finish_time')) {

                    $validator->errors()->add('finish_time', ' Finish time can not be earlier than start time');
                }
            }

            if(!null==$this->input('finish_date')){

                $start_week_day = date('D', strtotime($this->input('date')));
                $finish_week_day = date('D', strtotime($this->input('finish_date')));

                if($start_week_day!=$finish_week_day)
                {
                    $validator->errors()->add('finish_date', ' Finish date day of the week must match the booking date\'s day of the week');
                }
            }

        });
    }

}
