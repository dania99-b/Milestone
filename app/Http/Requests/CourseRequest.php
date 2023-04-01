<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
            'class_name' => 'required|string|max:255',
            'course_ename'=>'string|max:255',
            'start_hour' => 'max:255',
            'end_hour' => 'max:255',
            'start_day' => 'required|string|max:255',
            'end_day' => 'required|string|max:20',
            'status' => 'required|string|max:20',
            'qr_code' => 'required|string|max:20',
            'days'=>'required'
        ];
    }
}
