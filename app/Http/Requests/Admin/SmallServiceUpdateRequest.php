<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmallServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id'     => ['sometimes','integer', Rule::exists('sections','id')],
            'name_en'        => ['sometimes','string','max:160'],
            'name_ar'        => ['sometimes','string','max:160'],
            'price'          => ['sometimes','nullable','numeric','min:0'],
            'description_en' => ['sometimes','nullable','string'],
            'description_ar' => ['sometimes','nullable','string'],
        ];
    }
}
