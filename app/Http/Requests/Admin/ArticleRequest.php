<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضيف صلاحياتك حسب الحاجة
    }

    public function rules(): array
    {
        $isUpdate = in_array($this->method(), ['PUT','PATCH']);

        return [
            'title_en'  => [$isUpdate ? 'sometimes' : 'required','string','max:255'],
            'title_ar'  => [$isUpdate ? 'sometimes' : 'required','string','max:255'],

            'brief_description_en' => [$isUpdate ? 'sometimes' : 'required','string'],
            'brief_description_ar' => [$isUpdate ? 'sometimes' : 'required','string'],

            'path_link' => [$isUpdate ? 'sometimes' : 'nullable','url','max:255'],
            'is_active' => ['sometimes','boolean'],

            // الصورة مطلوبة عند الإنشاء فقط
            'image'     => [$isUpdate ? 'sometimes' : 'required','image','mimes:jpg,jpeg,png,webp','max:5120'],
        ];
    }
}
