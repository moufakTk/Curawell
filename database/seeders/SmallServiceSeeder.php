<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Models\Section;
use App\Models\SmallService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SmallServiceSeeder extends Seeder
{
    public function run(): void
    {
        // ====== جلب الأقسام ======
        $clinicSectionId = Section::where('section_type', SectionType::Clinics)->value('id');
        if (!$clinicSectionId) {
            throw new \RuntimeException('Clinics section not found. Seed sections first.');
        }

        // نحاول نجيب قسم الأشعة بعدة طرق (enum أو بالاسم)
        $radiologySectionId =
            (defined(SectionType::class.'::Radiology')
                ? Section::where('section_type', SectionType::Radiology)->value('id')
                : null)
            ?? Section::whereIn('name_en', ['Radiology','Imaging','X-Ray'])
            ->orWhereIn('name_ar', ['الأشعة','قسم الأشعة','تصوير شعاعي'])
            ->value('id');

        // ====== مولّد سعر بسيط حسب الفئة ======
        $price = function (int $min, int $max) {
            return (int)random_int($min, $max);
        };

        // ====== قوالب خدمات العيادات (هنطلع منهم ~100 عنصر) ======
        // لكل فئة: عناصر EN/AR + مجال سعر
        $catalog = [
            [
                'min'=>200, 'max'=>800,
                'en'=>[
                    'Dental Scaling & Polishing','Composite Filling (Single Surface)','Tooth Extraction (Simple)',
                    'Root Canal Treatment (Single Canal)','Panoramic Dental Consultation','Fluoride Application',
                    'Pit & Fissure Sealant','Orthodontic Consultation','Metal Braces Activation',
                    'Ceramic Braces Activation','Clear Aligner Checkup','Fixed Retainer Repair',
                    'Gum Treatment (Gingivitis)','Teeth Whitening (In-office)','Night Guard (Hard)',
                    'Crown Cementation','Temporary Filling','Desensitizing Treatment',
                ],
                'ar'=>[
                    'تنظيف وتلميع الأسنان','حشوة كومبوزيت (سطح واحد)','قلع سن بسيط',
                    'علاج عصب (قناة واحدة)','استشارة سنية بانورامية','تطبيق الفلورايد',
                    'سدادات شقوق وحفر','استشارة تقويم','تفعيل تقويم معدني',
                    'تفعيل تقويم خزفي','متابعة تقويم شفاف','إصلاح مثبت ثابت',
                    'علاج التهاب اللثة','تبييض أسنان بالعيادة','واقي ليلي (قاسي)',
                    'تلصيق تاج','حشوة مؤقتة','علاج حساسية الأسنان',
                ],
            ],
            [
                'min'=>150, 'max'=>600,
                'en'=>[
                    'Acne Consultation','Chemical Peel (Light)','Microneedling Session',
                    'Pigmentation Treatment Session','Wart/Skin Tag Removal (Single)','Mole Evaluation',
                    'PRP for Face','Mesotherapy (Face)','Deep Facial Cleansing',
                    'Dermatitis Follow-up','Psoriasis Review','Keloid Injection',
                ],
                'ar'=>[
                    'استشارة حب الشباب','تقشير كيميائي خفيف','جلسة إبر دقيقة (Microneedling)',
                    'جلسة علاج التصبغات','إزالة زائدة جلدية/ثؤلول (واحد)','تقييم شامة جلدية',
                    'حقن PRP للوجه','ميزوثيرابي للوجه','تنظيف بشرة عميق',
                    'متابعة التهاب الجلد','مراجعة صدفية','حقن كلويد',
                ],
            ],
            [
                'min'=>180, 'max'=>700,
                'en'=>[
                    'ENT Consultation','Ear Wax Removal (Both Ears)','Acute Otitis Media Management',
                    'Allergic Rhinitis Plan','Sinusitis Medical Treatment','Epistaxis Control (Conservative)',
                    'Laryngitis Review','Hearing Test (Screen)','Nasal Endoscopy (Office)',
                ],
                'ar'=>[
                    'استشارة أنف أذن حنجرة','تنظيف شمع الأذن (كلتا الأذنين)','تدبير التهاب الأذن الوسطى الحاد',
                    'خطة التهاب الأنف التحسسي','علاج دوائي لالتهاب الجيوب','إيقاف نزف أنفي محافظ',
                    'مراجعة التهاب الحنجرة','فحص سمعي بسيط','تنظير أنفي بالعيادة',
                ],
            ],
            [
                'min'=>180, 'max'=>750,
                'en'=>[
                    'Ophthalmology Consultation','Refraction & Prescription','Dry Eye Management Session',
                    'Conjunctivitis Treatment','Intraocular Pressure Check','Fundus Examination',
                    'Contact Lens Fitting (Soft)','Foreign Body Removal (Superficial)','Lid Chalazion Review',
                ],
                'ar'=>[
                    'استشارة عينية','فحص انكسار ووصفة نظارات','جلسة تدبير جفاف العين',
                    'علاج التهاب الملتحمة','قياس ضغط العين','فحص قاع العين',
                    'تركيب عدسات لاصقة (طرية)','إزالة جسم غريب سطحي','مراجعة شحاذ الجفن',
                ],
            ],
            [
                'min'=>200, 'max'=>900,
                'en'=>[
                    'Orthopedic Consultation','Back Pain Plan (Conservative)','Knee Pain Assessment',
                    'Sprain Management (Ankle)','Shoulder Impingement Plan','Tennis Elbow Injection (Conservative)',
                    'Cast Application (Short Arm)','Cast Removal','Post-Fracture Follow-up',
                ],
                'ar'=>[
                    'استشارة عظمية','خطة ألم الظهر (محافظ)','تقييم ألم الركبة',
                    'تدبير التواء الكاحل','خطة انحشار الكتف','حقنة مرفق التنس (محافظة)',
                    'وضع جبس (ساعد قصير)','إزالة الجبس','متابعة ما بعد الكسر',
                ],
            ],
            [
                'min'=>200, 'max'=>800,
                'en'=>[
                    'Neurology Consultation','Migraine Prophylaxis Plan','Dizziness & Balance Assessment',
                    'Peripheral Neuropathy Review','Seizure Follow-up','Sleep Hygiene Counseling (Neuro)',
                ],
                'ar'=>[
                    'استشارة أعصاب','خطة وقاية من الشقيقة','تقييم الدوخة والتوازن',
                    'مراجعة اعتلال الأعصاب الطرفية','متابعة نوبات الصرع','إرشاد نوم صحي (عصبي)',
                ],
            ],
            [
                'min'=>180, 'max'=>650,
                'en'=>[
                    'Aesthetic Consultation','Botox (Per Area)','Filler (Per Syringe)',
                    'Dark Circles Treatment','Skin Tightening (Non-surgical)','Post-Procedure Checkup',
                ],
                'ar'=>[
                    'استشارة تجميلية','بوتوكس (لكل منطقة)','فيلر (لكل أمبولة)',
                    'علاج هالات سوداء','شد بشرة غير جراحي','مراجعة ما بعد الإجراء',
                ],
            ],
            [
                'min'=>150, 'max'=>500,
                'en'=>[
                    'General Checkup (Clinic)','Vital Signs & Triage','Medical Report (Simple)',
                    'Vaccination Counseling','Travel Health Advice','Dietary Counseling (Brief)',
                ],
                'ar'=>[
                    'فحص عام (عيادة)','قياسات أساسية وفرز','تقرير طبي بسيط',
                    'استشارة لقاحات','نصائح صحة السفر','إرشاد غذائي مختصر',
                ],
            ],
        ];

        // نبني قائمة خدمات العيادات
        $clinicServices = [];
        foreach ($catalog as $block) {
            $count = min(count($block['en']), count($block['ar']));
            for ($i = 0; $i < $count; $i++) {
                $clinicServices[] = [
                    'name_en'        => $block['en'][$i],
                    'name_ar'        => $block['ar'][$i],
                    'price'          => $price($block['min'], $block['max']),
                    'description_en' => 'Outpatient procedure/service – clinic based.',
                    'description_ar' => 'خدمة/إجراء ضمن العيادة.',
                ];
            }
        }

        // إذا أقل من 100، نكرر بعض البنود بأسماء موسّعة (Level 2 / Plus / Advanced) للوصول للعدد
        while (count($clinicServices) < 100) {
            $pick = $clinicServices[array_rand($clinicServices)];
            $suffixEn = Arr::random([' — Plus',' — Advanced',' (Level 2)',' (Follow-up)',' — Extended']);
            $suffixAr = Arr::random([' — بlus',' — متقدم',' (المستوى 2)',' (متابعة)',' — موسّع']);
            $clinicServices[] = [
                'name_en'        => $pick['name_en'].$suffixEn,
                'name_ar'        => $pick['name_ar'].$suffixAr,
                'price'          => $price(200, 900),
                'description_en' => $pick['description_en'],
                'description_ar' => $pick['description_ar'],
            ];
        }

        // خذ أول 100 فقط
        $clinicServices = array_slice($clinicServices, 0, 100);

        // upsert للعيادات
        foreach ($clinicServices as $svc) {
            SmallService::updateOrCreate(
                ['section_id' => $clinicSectionId, 'name_en' => $svc['name_en']],
                [
                    'name_ar'        => $svc['name_ar'],
                    'price'          => $svc['price'],
                    'description_en' => $svc['description_en'],
                    'description_ar' => $svc['description_ar'],
                ]
            );
        }

        // ====== خدمات الأشعة (5–6 عناصر) ======
        if ($radiologySectionId) {
            $radiology = [
                [
                    'en' => 'X-Ray Chest (PA/AP)',
                    'ar' => 'أشعة سينية للصدر (أمامي/خلفي)',
                    'min'=>200,'max'=>350,
                    'den'=>'Standard chest radiograph for lung and cardiac evaluation.',
                    'dar'=>'صورة أشعة صدر قياسية لتقييم الرئتين والقلب.',
                ],
                [
                    'en' => 'X-Ray Abdomen (KUB)',
                    'ar' => 'أشعة سينية للبطن (KUB)',
                    'min'=>220,'max'=>380,
                    'den'=>'Plain film of kidneys, ureters and bladder.',
                    'dar'=>'فيلم بسيط لتصوير الكليتين والحالبين والمثانة.',
                ],
                [
                    'en' => 'Ultrasound Abdomen',
                    'ar' => 'إيكو البطن',
                    'min'=>400,'max'=>700,
                    'den'=>'Ultrasound examination of abdominal organs.',
                    'dar'=>'تصوير بالأمواج فوق الصوتية لأعضاء البطن.',
                ],
                [
                    'en' => 'Doppler Ultrasound (Lower Limb)',
                    'ar' => 'دوبلر أطراف سفلية',
                    'min'=>500,'max'=>900,
                    'den'=>'Vascular Doppler study for DVT/flow assessment.',
                    'dar'=>'فحص دوبلر وعائي لتقييم الجريان/الخثار الوريدي.',
                ],
                [
                    'en' => 'CT Head (Non-contrast)',
                    'ar' => 'طبقي محوري للرأس (بدون حقن)',
                    'min'=>900,'max'=>1400,
                    'den'=>'Non-contrast cranial CT for acute pathology.',
                    'dar'=>'طبقي محوري للدماغ دون حقن للحالات الحادة.',
                ],
                [
                    'en' => 'MRI Knee (One Side)',
                    'ar' => 'رنين مغناطيسي للركبة (جهة واحدة)',
                    'min'=>1800,'max'=>2600,
                    'den'=>'MRI evaluation of menisci and ligaments.',
                    'dar'=>'رنين لتقييم الغضاريف والأربطة.',
                ],
            ];

            foreach ($radiology as $r) {
                SmallService::updateOrCreate(
                    ['section_id' => $radiologySectionId, 'name_en' => $r['en']],
                    [
                        'name_ar'        => $r['ar'],
                        'price'          => $price($r['min'], $r['max']),
                        'description_en' => $r['den'],
                        'description_ar' => $r['dar'],
                    ]
                );
            }
        }
    }
}
