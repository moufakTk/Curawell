<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'doctor_session_id'=>'required|exists:doctor_sessions,id',
            'phone'=>'required',
            'taxi_order'=>'required|boolean',
            'location_order'=>'required_if:taxi_order,true|string',


        ];
    }
}
