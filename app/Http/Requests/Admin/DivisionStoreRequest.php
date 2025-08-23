<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivisionStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'small_service_id' => ['required','integer', Rule::exists('small_services','id')],
            'doctor_id'        => ['required','integer', Rule::exists('doctors','id')],
            'is_discounted'    => ['nullable','boolean'],
            'discount_rate'    => ['nullable','numeric','min:0','max:100'],


            Rule::unique('divisions','doctor_id')
                ->where(fn($q) => $q->where('small_service_id', $this->input('small_service_id')))
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.unique' => 'هذا الطبيب مضاف مسبقًا إلى نفس الخدمة.',
        ];
    }
}
