<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
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
           
            //'days_attend' => 'required|max:255',
            'med'=>'required|int',
            'presentation'=>'required|int',
            'oral'=>'required|int',
            'final'=>'required|int',
            'homework'=>'required|int',
            'student_id'=>'required|int'
        ];
    }
}
