<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WaitingRequest extends FormRequest
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
            'doctor_id'=>'required|exists:doctors,id',
            'phone'=>'required',
            //'name_patient'=>'required_if:mode,FaceToFace|string',
            'number_patient'=>'required_if:mode,FaceToFace|string',
            'type_waiting'=>'required|string|in:Emergency,Disabled,Old',

        ];
    }
}
