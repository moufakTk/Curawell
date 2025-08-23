<?php

namespace App\Http\Requests\Admin;

use App\Enums\Services\SectionType;
use App\Models\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id' => [
                'required',
                'integer',
                Rule::exists('sections','id')->where(function($q){
                    $q->whereIn('section_type', [SectionType::Clinics->value, SectionType::HomeCare->value]);
                })
            ],
            'name_en' => ['required','string','max:160'],
            'name_ar' => ['required','string','max:160'],
            'details_services_en' => ['nullable','array'],
            'details_services_ar' => ['nullable','array'],
        ];
    }
}
