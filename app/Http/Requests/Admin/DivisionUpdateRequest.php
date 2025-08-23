<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DivisionUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('division')?->id ?? $this->route('division');

        return [
            'small_service_id' => ['sometimes','integer', Rule::exists('small_services','id')],
            'doctor_id'        => ['sometimes','integer', Rule::exists('doctors','id')],
            'is_discounted'    => ['sometimes','boolean'],
            'discount_rate'    => ['sometimes','numeric','min:0','max:100'],

            Rule::unique('divisions','doctor_id')
                ->ignore($id)
                ->where(fn($q) => $q->where('small_service_id', $this->input('small_service_id', $this->division->small_service_id ?? null))),
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.unique' => 'هذا الطبيب مضاف مسبقًا إلى نفس الخدمة.',
        ];
    }
}
