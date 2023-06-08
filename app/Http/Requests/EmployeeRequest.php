<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users|max:255',
        'password' => 'required|string|min:6|max:255',
        'phone' => 'required|string|max:20|unique:users',
        'username'=> 'required|string|max:255|unique:users',
        'image'=>'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'birth'=>'date|before:today',
        'period_id' => 'required',
        ];
    }
}
