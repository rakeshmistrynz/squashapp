<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class StoreNoticeRequest extends Request
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

            'headline' => 'required|max:50',
            'body' => 'required|max:350',
            'image'=>'image|mimes:png,jpeg,bmp',
            'pdf'=>'mimes:pdf'
        ];
    }

}
