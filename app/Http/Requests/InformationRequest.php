<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InformationRequest extends FormRequest
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
            'who_we_are' => 'required|string|max:10000',  // Maximum of 5000 characters
            'contact_us' => 'required|string|max:10000',  // Maximum of 5000 characters
            'services' => 'required|string|max:10000', 
            'email' => 'required|string|email',    // Maximum of 5000 characters
        ];
    }
}
