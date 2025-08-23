<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRrequest extends FormRequest
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

            //  user table
//            'email' => 'required|string|email|max:100',

            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'birthday' => 'required|date',
            'gender' => 'required|string|in:male,female',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'phone' => 'required|string|between:10,20|unique:users,phone',
            'address'=> 'required|string',
            //'user_type'=>'required',



            // patient table
            'civil_id_number' => 'required|string|between:8,15|unique:patients,civil_id_number',
//            'civil_id_number' => 'required|string|between:8,15',
            'alternative_phone'=> 'required|string|between:8,15',

            //medical history table

            //arrays
            'chronic_diseases'    => 'sometimes|array',
            'hereditary_diseases' => 'sometimes|array',
            'new_diseases'        => 'sometimes|array',
            'allergies'           => 'sometimes|array',

            'blood_group'         => 'sometimes|string|between:1,15',
            'weight'              => 'sometimes|string|between:1,15',
            'height'              => 'sometimes|string|between:1,15',


        ];
    }
}
