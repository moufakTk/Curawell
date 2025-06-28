<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDoctorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //

            //  user table

            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:male,female',
//            'email' => 'required|string|email|max:100|unique:users',
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'required|string|between:10,20',
            'address'=> 'required|string',
            'user_type'=>'required',



            //doctor table
            'respective_en'=>'required|string|between:10,100',
            'respective_ar'=>'required|string|between:10,100',
            'experience_years'=>'required||integer|min:1',
            'services_en'=>'required|array|between:1,100',
            'services_ar'=>'required|array|between:1,100',
            'bloodGroup'=>'sometimes|string|between:1,15',
            'start_in'=>'required|date|date_format:Y-m-d|before:hold_end',
            'hold_end'=>'required|date|date_format:Y-m-d|after:start_in',
            'doctor_type'=>'required',
        ];
    }
}
