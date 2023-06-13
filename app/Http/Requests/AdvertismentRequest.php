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
            'tips'=>'string|nullable',
            'is_shown'=>'required',
            'description'=>'max:5000' ,
            'course_id'=>'max:255' ,
            'published_at'=>'date',
            'expired_at'=>'date|after:today' 
        ];
    }
}
