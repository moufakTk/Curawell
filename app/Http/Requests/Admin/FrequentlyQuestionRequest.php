<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FrequentlyQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // غيّرها حسب الصلاحيات عندك
    }

    public function rules(): array
    {
        return [
            'question_en' => 'required|string|max:255',
            'question_ar' => 'required|string|max:255',
            'answer_en'   => 'required|string',
            'answer_ar'   => 'required|string',
            'status'      => 'boolean',
        ];
    }
}
