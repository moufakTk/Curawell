<?php

namespace App\Http\Requests\Admin;

use App\Enums\Services\SectionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id' => [
                'sometimes','integer',
                Rule::exists('sections','id')->where(fn($q) =>
                $q->whereIn('section_type', [SectionType::Clinics->value, SectionType::HomeCare->value])
                )
            ],
            'name_en' => ['sometimes','string','max:160'],
            'name_ar' => ['sometimes','string','max:160'],
            'details_services_en' => ['nullable','array'],
            'details_services_ar' => ['nullable','array'],
        ];
    }
}
