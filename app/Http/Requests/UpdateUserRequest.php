<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;
use Validator;

class UpdateUserRequest extends Request
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

            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Check when user updates details, that if email has changed it is not taken by another user.
     */
    public function validator()
    {

        $validator = Validator::make($this->input(), $this->rules(), $this->messages());

        $validator->sometimes('email', 'unique:users', function ($input) {

            return $input->email != \Auth::user()->email;

        });

        return $validator;
    }

}
