<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
                
                'password' => 'required|string|min:6|max:255',
                'username'=> 'required|string|max:255|unique:users',
                'images'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'birth'=>'date|before:today',
                'country_id'=>'int',
                'level'=> 'required|string',
               
                ];
    
    }
}
