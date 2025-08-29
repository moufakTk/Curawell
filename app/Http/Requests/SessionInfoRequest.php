<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SessionInfoRequest extends FormRequest
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
            'session_id'=>'required|exists:session_centers,id',
            'diagnosis_name'=>'nullable|string',
            'diagnosis'=>'nullable|array',
            'diagnosis.report' => 'nullable|string',
            'diagnosis.description' => 'nullable|string',
            'medicines'=>'nullable|string',

        ];
    }
}
