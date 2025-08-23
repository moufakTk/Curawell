<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SmallServiceStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id'     => ['required','integer', Rule::exists('sections','id')],
            'name_en'        => ['required','string','max:160'],
            'name_ar'        => ['required','string','max:160'],
            'price'          => ['nullable','numeric','min:0'],
            'description_en' => ['nullable','string'],
            'description_ar' => ['nullable','string'],
        ];
    }
}
