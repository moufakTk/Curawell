<?php

namespace Database\Seeders;

use App\Models\Analyze;
use Illuminate\Database\Seeder;

class AnalyzeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ملاحظة: sample_validate = عدد ساعات صلاحية العينة (تقريبية)
        $items = [
            // دم
            [
                'name_en' => 'Complete Blood Count (CBC)',
                'name_ar' => 'تعداد دم كامل',
                'type' => 'Blood',
                'price' => 60,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Fasting Blood Glucose',
                'name_ar' => 'سكر الدم (صائم)',
                'type' => 'Blood',
                'price' => 35,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'HbA1c',
                'name_ar' => 'الهيموغلوبين السكري (HbA1c)',
                'type' => 'Blood',
                'price' => 80,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Total Cholesterol',
                'name_ar' => 'كوليسترول كلي',
                'type' => 'Blood',
                'price' => 50,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'HDL Cholesterol',
                'name_ar' => 'كوليسترول HDL',
                'type' => 'Blood',
                'price' => 50,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'LDL Cholesterol',
                'name_ar' => 'كوليسترول LDL',
                'type' => 'Blood',
                'price' => 60,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Triglycerides',
                'name_ar' => 'الغليسيريدات الثلاثية',
                'type' => 'Blood',
                'price' => 50,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'ALT (GPT)',
                'name_ar' => 'ALT (GPT) ناقلة أمين الألانين',
                'type' => 'Blood',
                'price' => 45,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'AST (GOT)',
                'name_ar' => 'AST (GOT) ناقلة أمين الأسبارتات',
                'type' => 'Blood',
                'price' => 45,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Creatinine',
                'name_ar' => 'الكرياتينين',
                'type' => 'Blood',
                'price' => 50,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Urea (BUN)',
                'name_ar' => 'اليوريا (BUN)',
                'type' => 'Blood',
                'price' => 45,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'TSH',
                'name_ar' => 'هرمون منبّه الدرق (TSH)',
                'type' => 'Blood',
                'price' => 90,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'Free T4',
                'name_ar' => 'الثيروكسين الحر (Free T4)',
                'type' => 'Blood',
                'price' => 90,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],
            [
                'name_en' => 'C-Reactive Protein (CRP)',
                'name_ar' => 'البروتين التفاعلي C (CRP)',
                'type' => 'Blood',
                'price' => 70,
                'sample_type' => 'Blood',
                'sample_validate' => 24,
                'is_active' => true,
            ],

            // بول
            [
                'name_en' => 'Urine Routine (URINE R/E)',
                'name_ar' => 'فحص بول روتيني',
                'type' => 'Urine',
                'price' => 30,
                'sample_type' => 'Urine',
                'sample_validate' => 12,
                'is_active' => true,
            ],
        ];

        foreach ($items as $row) {
            Analyze::firstOrCreate(
                ['name_en' => $row['name_en']], // لمنع التكرار عند إعادة التشغيل
                $row
            );
        }
    }
}
