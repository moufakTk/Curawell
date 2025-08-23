<?php

namespace App\Http\Requests\Admin;

use App\Enums\Services\SectionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompetenceUpdateRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'service_id' => [
                'sometimes','integer',
                Rule::exists('services','id')->where(function($q){
                    $q->whereExists(function($sub){
                        $sub->select('id')
                            ->from('sections')
                            ->whereColumn('sections.id','services.section_id')
                            ->where('sections.section_type', SectionType::Clinics->value);
                    });
                })
            ],
            'name_en' => ['sometimes','string','max:160'],
            'name_ar' => ['sometimes','string','max:160'],
            'brief_description_en' => ['nullable','array'],
            'brief_description_ar' => ['nullable','array'],
        ];
    }
}
