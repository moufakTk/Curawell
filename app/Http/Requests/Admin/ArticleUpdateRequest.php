<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ضيف صلاحياتك حسب الحاجة
    }

    public function rules(): array
    {

        return [
            'title_en'  => ['sometimes' ,'string','max:255'],
            'title_ar'  => ['sometimes','string','max:255'],

            'brief_description_en' => ['sometimes','string'],
            'brief_description_ar' => [ 'sometimes','string'],

            'path_link' => [ 'nullable','url','max:255'],
            'is_active' => ['sometimes','boolean'],

            // الصورة مطلوبة عند الإنشاء فقط
            'image'     => ['sometimes' ,'image','mimes:jpg,jpeg,png,webp','max:5120'],
        ];
    }
}
