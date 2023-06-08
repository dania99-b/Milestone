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
            'class_id' => 'required|string|max:255',
            'start_hour' => 'required|date_format:H:i',
            'end_hour' => 'required|date_format:H:i',
            'qr_code' => 'required|string',
            'days'=>'required',
            'course_name_id'=>'required|string|max:20',
            'teacher_id'=>'required|string|max:20',
            'period_id' => 'required|string|max:255',
        ];
    }
}
