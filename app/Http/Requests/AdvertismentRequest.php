<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertismentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title'=>'required',
            'images'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'advertisment_type_id'=>'required',
            'tips'=>'string',
            'is_shown'=>'required',
            'description'=>'max:255' ,
            'course_id'=>'max:255'  
        ];
    }
}
