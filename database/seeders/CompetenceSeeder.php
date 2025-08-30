<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Service;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    public function run(): void
    {
        // helper لإنشاء مجموعة اختصاصات لخدمة معيّنة
        $seed = function (string $serviceEn, array $items) {
            $serviceId = Service::where('name_en', $serviceEn)->value('id');
            if (!$serviceId) {
                throw new \RuntimeException("Service '{$serviceEn}' not found. Seed services first.");
            }

            foreach ($items as $c) {
                // name_en, name_ar إجباريين
                Competence::updateOrCreate(
                    [
                        'service_id' => $serviceId,
                        'name_en'    => $c['name_en'],
                    ],
                    [
                        'name_ar'               => $c['name_ar'],
                        'brief_description_en'  => $c['brief_description_en'] ?? null,
                        'brief_description_ar'  => $c['brief_description_ar'] ?? null,
                    ]
                );
            }
        };

        // ===== Dental =====
        $seed('Dental', [
            [
                'name_en'=>'Orthodontics',
                'name_ar'=>'تقويم الأسنان',
                'brief_description_en'=>[
                    'brief_description'=>'Diagnosis and correction of malocclusion using braces/aligners.',
                    'details'=>[
                        'Metal/ceramic/clear aligners.',
                        'Crowding/spacing correction.',
                        'Overbite/underbite management.',
                        'Retention and relapse prevention.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تشخيص وعلاج سوء الإطباق باستخدام التقويم/الشفاف.',
                    'details'=>[
                        'تقويم معدني/خزفي/شفاف.',
                        'تصحيح التزاحم والفراغات.',
                        'علاج البروز والعَضّات.',
                        'تثبيت النتائج ومنع الانتكاس.',
                    ],
                ],
            ],
            [
                'name_en'=>'Oral & Maxillofacial Surgery',
                'name_ar'=>'جراحة الفم والوجه والفكين',
                'brief_description_en'=>[
                    'brief_description'=>'Surgical management of teeth, jaws and facial structures.',
                    'details'=>[
                        'Surgical extraction (wisdom tooth).',
                        'Cyst/tumor removal.',
                        'Fracture and trauma care.',
                        'Dental implants.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'إجراءات جراحية للأسنان والفكين والبنى الوجهية.',
                    'details'=>[
                        'قلع جراحي (ضرس العقل).',
                        'استئصال كيس/ورم.',
                        'علاج الكسور والرضوض.',
                        'زرعات سنية.',
                    ],
                ],
            ],
            [
                'name_en'=>'Pediatric Dentistry',
                'name_ar'=>'طب أسنان الأطفال',
                'brief_description_en'=>[
                    'brief_description'=>'Dental care from infancy through adolescence.',
                    'details'=>[
                        'Fluoride and sealants.',
                        'Caries treatment in deciduous teeth.',
                        'Behavior management.',
                        'Growth and eruption monitoring.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'رعاية أسنان الأطفال من الطفولة للمراهقة.',
                    'details'=>[
                        'وقاية بالفلور والسدادات.',
                        'علاج تسوس الأسنان اللبنية.',
                        'تدبير سلوك الطفل.',
                        'متابعة النمو والبزوغ.',
                    ],
                ],
            ],
            [
                'name_en'=>'Periodontics',
                'name_ar'=>'أمراض اللثة',
                'brief_description_en'=>[
                    'brief_description'=>'Prevention and treatment of gum diseases.',
                    'details'=>[
                        'Scaling and root planing.',
                        'Gum grafts for recession.',
                        'Perio-maintenance programs.',
                        'Implant-related peri-implantitis care.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'الوقاية وعلاج أمراض اللثة.',
                    'details'=>[
                        'تنظيف عميق وتجريف الجذور.',
                        'ترقيع اللثة للانحسار.',
                        'برامج صيانة دورية.',
                        'علاج التهابات حول الزرعات.',
                    ],
                ],
            ],
        ]);

        // ===== Beauty =====
        $seed('Beauty', [
            [
                'name_en'=>'Rhinoplasty (Cosmetic Nose)',
                'name_ar'=>'تجميل الأنف',
                'brief_description_en'=>[
                    'brief_description'=>'Improves nasal aesthetics and function.',
                    'details'=>[
                        'Dorsal hump reduction.',
                        'Tip refinement.',
                        'Septoplasty for breathing.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تحسين الشكل والوظيفة التنفسية للأنف.',
                    'details'=>[
                        'تقليل حدبة الظهر الأنفي.',
                        'تحسين طرف الأنف.',
                        'تقويم الحاجز لتحسين التنفس.',
                    ],
                ],
            ],
            [
                'name_en'=>'Facelift & Neck Lift',
                'name_ar'=>'شد الوجه والرقبة',
                'brief_description_en'=>[
                    'brief_description'=>'Reduces wrinkles and laxity, restores youthful contour.',
                    'details'=>[
                        'SMAS plication/platysmaplasty.',
                        'Lower face & neck contouring.',
                        'Adjunct fat grafting.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تقليل التجاعيد والترهلات واستعادة ملامح الشباب.',
                    'details'=>[
                        'شد طبقة SMAS/البلاتيزما.',
                        'تحسين محيط أسفل الوجه والرقبة.',
                        'حقن دهون تكميلي.',
                    ],
                ],
            ],
            [
                'name_en'=>'Laser Aesthetics',
                'name_ar'=>'التجميل بالليزر',
                'brief_description_en'=>[
                    'brief_description'=>'Resurfacing, pigment removal, hair reduction.',
                    'details'=>[
                        'Fractional resurfacing.',
                        'Pigmented lesion treatment.',
                        'Laser hair removal.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تقشير وتجديد البشرة، إزالة التصبغات والشعر.',
                    'details'=>[
                        'فراكشونال لإعادة التسطيح.',
                        'علاج الآفات المصطبغة.',
                        'إزالة الشعر بالليزر.',
                    ],
                ],
            ],
            [
                'name_en'=>'Skin Care & Injectables',
                'name_ar'=>'العناية بالبشرة والحقن',
                'brief_description_en'=>[
                    'brief_description'=>'Botox/fillers, peels, medical facials.',
                    'details'=>[
                        'Botox for lines.',
                        'Hyaluronic fillers.',
                        'Chemical peels & facials.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'بوتوكس/فيلر، تقشير، فيشل طبي.',
                    'details'=>[
                        'بوتوكس للتجاعيد.',
                        'فيلر هيالورونيك.',
                        'تقشير وفحوصات بشرة.',
                    ],
                ],
            ],
        ]);

        // ===== Neurology =====
        $seed('Neurology', [
            [
                'name_en'=>'Headache & Migraine',
                'name_ar'=>'الصداع والشقيقة',
                'brief_description_en'=>[
                    'brief_description'=>'Evaluation and long-term management of primary headaches.',
                    'details'=>[
                        'Headache diaries and triggers.',
                        'Prophylactic therapies.',
                        'Acute rescue plans.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تقييم وإدارة الصداع الأولي على المدى الطويل.',
                    'details'=>[
                        'مذكرات الصداع والمحفزات.',
                        'علاجات وقائية.',
                        'خطط إسعافية للنوبات.',
                    ],
                ],
            ],
            [
                'name_en'=>'Epilepsy & Seizure',
                'name_ar'=>'الصرع والنوبات',
                'brief_description_en'=>[
                    'brief_description'=>'Diagnosis, EEG and medication titration.',
                    'details'=>[
                        'EEG monitoring.',
                        'ASM titration.',
                        'Counseling and safety.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تشخيص، تخطيط دماغ وضبط الجرعات.',
                    'details'=>[
                        'مراقبة EEG.',
                        'تعديل أدوية الصرع.',
                        'إرشاد وسلامة.',
                    ],
                ],
            ],
            [
                'name_en'=>'Demyelinating Disorders (MS)',
                'name_ar'=>'اضطرابات إزالة الميالين (MS)',
                'brief_description_en'=>[
                    'brief_description'=>'Diagnosis and disease-modifying therapies.',
                    'details'=>[
                        'MRI-based diagnosis.',
                        'DMT selection and follow-up.',
                        'Relapse management.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تشخيص وعلاجات معدّلة للمرض.',
                    'details'=>[
                        'تشخيص عبر الـ MRI.',
                        'اختيار DMT والمتابعة.',
                        'تدبير الهجمات.',
                    ],
                ],
            ],
        ]);

        // ===== Dermatology =====
        $seed('Dermatology', [
            [
                'name_en'=>'Acne & Scar Management',
                'name_ar'=>'حب الشباب وآثاره',
                'brief_description_en'=>[
                    'brief_description'=>'Medical and procedural acne care.',
                    'details'=>[
                        'Topicals and antibiotics.',
                        'Chemical peels/microneedling.',
                        'Scar revision.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'علاج دوائي وإجرائي لحب الشباب.',
                    'details'=>[
                        'مستحضرات موضعية وصادات.',
                        'تقشير/إبر دقيقة.',
                        'تصحيح الندبات.',
                    ],
                ],
            ],
            [
                'name_en'=>'Pigmentation Disorders',
                'name_ar'=>'اضطرابات التصبغ',
                'brief_description_en'=>[
                    'brief_description'=>'Melasma, PIH, vitiligo approaches.',
                    'details'=>[
                        'Topicals and sun protection.',
                        'Procedural options.',
                        'Maintenance plans.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'كلف/تصبغات/بهاق.',
                    'details'=>[
                        'علاجات موضعية وواقي شمس.',
                        'إجراءات تجميلية.',
                        'خطط محافظة.',
                    ],
                ],
            ],
            [
                'name_en'=>'Psoriasis & Eczema',
                'name_ar'=>'الصدفية والأكزيما',
                'brief_description_en'=>[
                    'brief_description'=>'Chronic inflammatory dermatoses care.',
                    'details'=>[
                        'Topicals/phototherapy.',
                        'Systemics/biologics.',
                        'Education and flares control.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'علاج أمراض جلدية التهابية مزمنة.',
                    'details'=>[
                        'موضعي/ضوئي.',
                        'أدوية جهازية/حيوية.',
                        'تثقيف وضبط النكس.',
                    ],
                ],
            ],
        ]);

        // ===== Orthopedic =====
        $seed('Orthopedic', [
            [
                'name_en'=>'Trauma & Fractures',
                'name_ar'=>'الرضوض والكسور',
                'brief_description_en'=>[
                    'brief_description'=>'Acute fracture management and follow-up.',
                    'details'=>[
                        'Reduction and casting.',
                        'ORIF when indicated.',
                        'Rehab plans.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'تدبير الكسور الحادة والمتابعة.',
                    'details'=>[
                        'رد وتجبير.',
                        'تثبيت جراحي عند اللزوم.',
                        'خطة تأهيل.',
                    ],
                ],
            ],
            [
                'name_en'=>'Sports Injuries',
                'name_ar'=>'إصابات رياضية',
                'brief_description_en'=>[
                    'brief_description'=>'Ligament/tendon and overuse injuries.',
                    'details'=>[
                        'ACL/meniscus issues.',
                        'Shoulder injuries.',
                        'Return-to-play protocols.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'أربطة/أوتار وإصابات الإجهاد.',
                    'details'=>[
                        'الرباط الصليبي/الغضروف.',
                        'إصابات الكتف.',
                        'بروتوكولات العودة للرياضة.',
                    ],
                ],
            ],
            [
                'name_en'=>'Spine Disorders',
                'name_ar'=>'اضطرابات العمود الفقري',
                'brief_description_en'=>[
                    'brief_description'=>'Back pain, disc herniation and stenosis.',
                    'details'=>[
                        'Conservative measures.',
                        'Injections when needed.',
                        'Surgical referrals.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'ألم الظهر والديسك وتضيّق القناة.',
                    'details'=>[
                        'علاج محافظ.',
                        'حقن عند الحاجة.',
                        'تحويلات جراحية.',
                    ],
                ],
            ],
        ]);

        // ===== ENT =====
        $seed('ENT', [
            [
                'name_en'=>'Otology & Hearing',
                'name_ar'=>'أمراض الأذن والسمع',
                'brief_description_en'=>[
                    'brief_description'=>'Ear infections, hearing loss and balance.',
                    'details'=>[
                        'Otitis media/externa.',
                        'Audiometry and tympanometry.',
                        'Vertigo management.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'التهابات الأذن والسمع والتوازن.',
                    'details'=>[
                        'أذن وسطى/خارجية.',
                        'قياس سمع وطبل.',
                        'تدبير الدوخة.',
                    ],
                ],
            ],
            [
                'name_en'=>'Rhinology & Allergy',
                'name_ar'=>'أمراض الأنف والحساسية',
                'brief_description_en'=>[
                    'brief_description'=>'Nasal obstruction, sinusitis and allergy.',
                    'details'=>[
                        'Rhinitis and sinusitis care.',
                        'Allergy testing/therapy.',
                        'Septal deviation work-up.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'انسداد الأنف والجيوب والحساسية.',
                    'details'=>[
                        'علاج التهاب الأنف والجيوب.',
                        'اختبارات/علاج الحساسية.',
                        'تقييم انحراف الوتيرة.',
                    ],
                ],
            ],
            [
                'name_en'=>'Laryngology & Voice',
                'name_ar'=>'أمراض الحنجرة والصوت',
                'brief_description_en'=>[
                    'brief_description'=>'Voice disorders and laryngeal disease.',
                    'details'=>[
                        'Stroboscopy if available.',
                        'VF nodules/polyps care.',
                        'Reflux-related laryngitis.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'اضطرابات الصوت وأمراض الحنجرة.',
                    'details'=>[
                        'تنظير ضوئي عند التوفر.',
                        'عقد/سلائل الحبال الصوتية.',
                        'التهاب حنجرة متعلق بالارتجاع.',
                    ],
                ],
            ],
        ]);

        // ===== Ophthalmology =====
        $seed('Ophthalmology', [
            [
                'name_en'=>'Anterior Segment',
                'name_ar'=>'القطعة الأمامية',
                'brief_description_en'=>[
                    'brief_description'=>'Cornea, conjunctiva and lens disorders.',
                    'details'=>[
                        'Dry eye management.',
                        'Keratitis and conjunctivitis.',
                        'Cataract screening/referrals.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'أمراض القرنية والملتحمة والعدسة.',
                    'details'=>[
                        'تدبير جفاف العين.',
                        'التهاب القرنية والملتحمة.',
                        'كشف الساد وتحويلاته.',
                    ],
                ],
            ],
            [
                'name_en'=>'Glaucoma',
                'name_ar'=>'الزرق (الغلوكوما)',
                'brief_description_en'=>[
                    'brief_description'=>'IOP control and optic nerve protection.',
                    'details'=>[
                        'Tonometry and OCT when available.',
                        'Medical IOP-lowering therapy.',
                        'Monitoring visual fields.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'ضبط ضغط العين وحماية العصب البصري.',
                    'details'=>[
                        'قياس الضغط و(OCT) إن توفر.',
                        'أدوية خافضة للضغط.',
                        'متابعة مجالات الرؤية.',
                    ],
                ],
            ],
            [
                'name_en'=>'Retina & Diabetes',
                'name_ar'=>'الشبكية واعتلال السكري',
                'brief_description_en'=>[
                    'brief_description'=>'Diabetic retinopathy screening and care.',
                    'details'=>[
                        'Fundus exam and imaging.',
                        'Injection referrals when needed.',
                        'Tight systemic control advice.',
                    ],
                ],
                'brief_description_ar'=>[
                    'brief_description'=>'كشف وعلاج اعتلال الشبكية السكري.',
                    'details'=>[
                        'فحص قاع العين وتصوير.',
                        'تحويلات للحقن عند الحاجة.',
                        'نصائح ضبط السكري والضغط.',
                    ],
                ],
            ],
        ]);
    }
}
