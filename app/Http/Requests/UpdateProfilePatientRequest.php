<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePatientRequest extends FormRequest
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

            //user table
            'first_name'=>'nullable|string',
            'last_name'=>'nullable|string',
            "address"=>'nullable|string',
            'gender'=>'nullable|string|in:male,female',
            'birthday'=>'nullable|date',

            //patient table

            //Medical history table
            'chronic_diseases'    => 'nullable|array',
            'hereditary_diseases' => 'nullable|array',
            'new_diseases'        => 'nullable|array',
            'allergies'           => 'nullable|array',
            'blood_group'         => 'sometimes|string|between:1,15',
            'weight'              => 'sometimes|string|between:1,15',
            'height'              => 'sometimes|string|between:1,15',

        ];
    }
}
