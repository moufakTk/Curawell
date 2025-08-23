<?php

namespace App\Http\Requests\Admin;

use App\Enums\Services\SectionType;
use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompetenceStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'service_id' => [
                'required','integer',
                Rule::exists('services','id')->where(function($q){
                    // competence فقط لخدمات ضمن قسم Clinic
                    $q->whereExists(function($sub){
                        $sub->select('id')
                            ->from('sections')
                            ->whereColumn('sections.id','services.section_id')
                            ->where('sections.section_type', SectionType::Clinics->value);
                    });
                })
            ],
            'name_en' => ['required','string','max:160'],
            'name_ar' => ['required','string','max:160'],
            'brief_description_en' => ['nullable','array'],
            'brief_description_ar' => ['nullable','array'],
        ];
    }
}
