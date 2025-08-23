<?php

namespace App\Http\Requests\Admin\Discounts;

use Illuminate\Foundation\Http\FormRequest;

class DiscountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // عدّلها إذا عندك صلاحيات
    }

    public function rules(): array
    {
        return [
            'name_en'        => 'required|string|max:255',
            'name_ar'        => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',

            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',

            // النسبة العامة الافتراضية % (بتنطبق إذا ما في override)
            'discount_rate'  => 'required|numeric|min:0',

            'active'         => 'sometimes|boolean',

            // لازم تحدد دكتور واحد أو أكثر
            'doctor_ids'     => 'required|array|min:1',
            'doctor_ids.*'   => 'integer|exists:doctors,id',

            // الخدمات المختارة (SmallService IDs)
            'service_ids'    => 'required|array|min:1',
            'service_ids.*'  => 'integer|exists:small_services,id',

            // (اختياري) Override لكل خدمة
            'overrides'                       => 'sometimes|array',
            'overrides.*.small_service_id'    => 'required|integer|exists:small_services,id',
            'overrides.*.discount_amount'     => 'required|numeric|min:0',
        ];
    }
}
