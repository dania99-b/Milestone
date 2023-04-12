<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
            'text' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'type_id' => 'required',
            'answers' => 'required|array',
            'answers.*.name' => 'required|string|max:255',
            'answers.*.is_true' => 'required',
          
            ];
    }
}
