<?php

namespace App\Http\Requests\Admin;

use App\Enums\Services\SectionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SectionUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name_en'               => ['sometimes','string','max:160'],
            'name_ar'               => ['sometimes','string','max:160'],
            'brief_description_en'  => ['nullable','string'],
            'brief_description_ar'  => ['nullable','string'],
            'section_type'          => ['sometimes', new Enum(SectionType::class)],
        ];
    }
}
