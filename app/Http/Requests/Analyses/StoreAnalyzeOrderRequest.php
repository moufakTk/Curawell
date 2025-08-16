<?php

namespace App\Http\Requests\Analyses;

use App\Enums\Orders\AnalyzeOrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAnalyzeOrderRequest extends FormRequest
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

            public function rules(): array {
        return [
            'doctor_id'   => ['nullable','exists:doctors,id'],
            'doctor_name' => ['nullable','string','max:255'],
            'name'        => ['required','string','max:255'],
            'status'      => ['', Rule::enum(AnalyzeOrderStatus::class)],
            'sample_type' => ['nullable','string','max:50'],
            'analyses'    => ['required','array','min:1'],
            'analyses.*.analyze_id' => ['required','exists:analyzes,id'],
            'samples'     => ['nullable','array'],
            'samples.*.sample_id'   => ['integer','exists:samples,id'],
        ];

    }
}
