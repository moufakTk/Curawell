<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Enums\Users\UserType;
use App\Enums\Users\DoctorType;

class UserStoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            // User
            'first_name' => ['required','string','max:120'],
            'last_name'  => ['required','string','max:120'],
            'email'      => ['nullable','email','max:190','unique:users,email'],
            'phone'      => ['nullable','string','max:40','unique:users,phone'],
            'password'   => ['required','string','min:6'],
            'user_type'  => ['required', Rule::in([
                UserType::Admin->value, UserType::Doctor->value, UserType::Nurse->value,
                UserType::Secretary->value, UserType::Reception->value, UserType::Driver->value, UserType::Patient->value
            ])],
            'is_active'  => ['nullable','boolean'],

            // Doctor (عند user_type=Doctor)
            'doctor'                 => ['required_if:user_type,Doctor','array'],
            'doctor.doctor_type'     => ['required_if:user_type,Doctor', Rule::in([
                DoctorType::Clinic->value, DoctorType::Laboratory->value, DoctorType::Radiographer->value, DoctorType::Relief->value
            ])],
            'doctor.start_in'        => ['nullable','date'],
            'doctor.hold_end'        => ['nullable','date','after_or_equal:doctor.start_in'],

            // Nurse mode: homecare or center (اختياري—افتراضي center)
            'nurse_mode'             => ['nullable', Rule::in(['homecare','center'])],

            // جدول أسبوعي (اختياري). إذا ما بعتّه للمستخدمين غير الهوم كير، رح نعبّي 09:00–18:00 افتراضيًا
            // [{"day_en":"Sunday","timeStart":"09:00:00","timeEnd":"18:00:00"}, ...]
            'weekly_times'              => ['nullable','array'],
            'weekly_times.*.day_en'     => ['required_with:weekly_times','string','in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'],
            'weekly_times.*.timeStart'  => ['nullable','date_format:H:i:s'],
            'weekly_times.*.timeEnd'    => ['nullable','date_format:H:i:s','after:weekly_times.*.timeStart'],

            // توليد مباشر بعد الإنشاء (اختياري)
            'seed_days'   => ['nullable','integer','min:1','max:120'],
        ];
    }
}
