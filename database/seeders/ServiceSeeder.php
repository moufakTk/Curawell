<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Models\Section;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $services_en = [
            'Dental','Beauty','Neurology','Dermatology','Orthopedic','ENT','Ophthalmology',
        ];
        $services_ar =[
            'أسنان','تجميل','أعصاب','جلدية','عظمية','أنف أذن حنجرة','عينية',
        ];
        $details_services_en=[
            [
                'brief_description'=>'This department focuses on diagnosing and treating oral, dental, and gum diseases, along with offering cosmetic and preventive dental care.',
                'details'=>[
                    'Routine dental cleaning and polishing.',
                    'Cavity treatment and fillings.',
                    'Braces and orthodontic care for adults and children.',
                    'Simple and surgical tooth extraction.',
                    'Gum infection and oral disease treatment.',
                    'Dental prosthetics (crowns, bridges, dentures).',
                ]
            ],

            [
                'brief_description'=>'The cosmetic department provides non-surgical aesthetic treatments using safe, modern techniques under specialist supervision.',
                'details'=>[
                    'Botox and filler injections.',
                    'Deep facial cleansing and chemical peels.',
                    'Treatment of pigmentation and acne scars.',
                    'Non-surgical skin tightening.',
                    'Fine line and wrinkle treatment.',
                     'Dark circle treatment.',
                ]
            ],

            [
                'brief_description'=>'This department treats disorders of the central and peripheral nervous system, including brain, spinal cord, and nerve conditions.',
                'details'=>[
                    'Treatment of chronic headaches and migraines.',
                    'Evaluation of seizures and epilepsy.',
                    'Management of peripheral nerve diseases.',
                    'Diagnosis of multiple sclerosis and brain disorders.',
                    'Treatment of dizziness and balance issues.',
                    'Sleep disorder management with neurological causes.',
                ]
            ],

            [
                'brief_description'=>'This department handles the diagnosis and treatment of skin, hair, and nail conditions, as well as advanced cosmetic skin care.',
                'details'=>[
                    'Acne treatment and scar removal.',
                    'Pigmentation and skin spot therapy.',
                    'Hair loss and alopecia treatment.',
                    'Facial cleansing and exfoliation sessions.',
                    'Treatment of eczema and psoriasis.',
                    'Removal of skin tags and warts.',
                ]
            ],

            [
                'brief_description'=>'This specialty focuses on diagnosing and treating conditions of the musculoskeletal system, including bones, joints, muscles, and tendons.',
                'details'=>[
                    'Diagnosis and treatment of fractures and injuries.',
                    'Management of joint and bone inflammation.',
                    'Treatment of disc herniation and spinal issues.',
                    'Sports injury care.',
                    'Application of casts and braces.',
                    'Evaluation and treatment of knee, shoulder, and hip pain.',
                ]
            ],

            [
                'brief_description'=>'This department specializes in the treatment of diseases and disorders related to the ear, nose, throat, head, and neck, focusing on respiratory, hearing, and voice issues.',
                'details'=>[
                    'Diagnosis and treatment of ear infections and sinusitis.',
                    'Hearing and balance tests.',
                    'Treatment of nasal congestion and allergies.',
                    'Management of laryngeal and voice disorders.',
                    'Earwax or foreign object removal.',
                    'Snoring and sleep disorder evaluation.',
                ]
            ],

            [
                'brief_description'=>'This specialty focuses on the diagnosis and treatment of eye and vision disorders, from routine exams to complex medical cases.',
                'details'=>[
                    'Comprehensive eye exams and vision assessments.',
                    'Diagnosis and treatment of conjunctivitis.',
                    'Management of dry eyes and corneal problems.',
                    'Eye pressure screening for glaucoma.',
                    'Diabetic eye exams and retina evaluation.',
                    'Prescription of eyeglasses and contact lenses.',
                ]
            ],
        ];

        $details_services_ar = [
            [
                'brief_description' => 'يُعنى هذا القسم بتشخيص وعلاج أمراض الفم والأسنان واللثة، إلى جانب تقديم خدمات العناية التجميلية والوقائية للفم.',
                'details' => [
                    'تنظيف الأسنان والتلميع الدوري.',
                    'علاج التسوس وحشو الأسنان.',
                    'تقويم الأسنان للكبار والأطفال.',
                    'خلع الأسنان البسيط والجراحي.',
                    'علاج التهابات اللثة وأمراض الفم.',
                    'تركيبات سنية (تيجان، جسور، أطقم).',
                ]
            ],

            [
                'brief_description' => 'يقدّم قسم التجميل حلولًا طبية غير جراحية لتحسين المظهر الخارجي، باستخدام تقنيات حديثة وآمنة بإشراف مختصين.',
                'details' => [
                    'حقن البوتوكس والفيلر.',
                    'جلسات تنظيف بشرة عميقة وتقشير.',
                    'إزالة التصبغات وآثار الحبوب.',
                    'شد البشرة بدون جراحة.',
                    'علاج التجاعيد والخطوط الدقيقة.',
                    'علاج الهالات السوداء.',
                ]
            ],

            [
                'brief_description' => 'يعالج هذا القسم أمراض الجهاز العصبي المركزي والطرفي، ويهتم بحالات الدماغ، الأعصاب، النخاع الشوكي والعضلات.',
                'details' => [
                    'علاج الصداع المزمن والصداع النصفي.',
                    'تقييم وتشخيص حالات التشنجات والصرع.',
                    'متابعة أمراض الأعصاب الطرفية.',
                    'تشخيص التصلب اللويحي وأمراض الدماغ.',
                    'علاج مشاكل التوازن والدوخة.',
                    'متابعة اضطرابات النوم العصبية.',
                ]
            ],

            [
                'brief_description' => 'يعنى قسم الجلدية بتشخيص وعلاج أمراض الجلد والشعر والأظافر، بالإضافة إلى تقديم حلول تجميلية متقدمة للعناية بالبشرة.',
                'details' => [
                    'علاج حب الشباب وآثاره.',
                    'إزالة التصبغات والبقع الجلدية.',
                    'علاج تساقط الشعر والثعلبة.',
                    'جلسات تنظيف وتقشير البشرة.',
                    'علاج الأكزيما والصدفية.',
                    'إزالة الزوائد الجلدية والثآليل.',
                ]
            ],

            [
                'brief_description' => 'يُعنى اختصاص العظمية بتشخيص وعلاج أمراض وإصابات الجهاز العضلي الهيكلي، بما يشمل العظام والمفاصل والعضلات والأوتار.',
                'details' => [
                    'تشخيص وعلاج الكسور والرضوض.',
                    'متابعة التهابات المفاصل والعظام.',
                    'معالجة الانزلاق الغضروفي ومشاكل العمود الفقري.',
                    'علاج الإصابات الرياضية.',
                    'تركيب الجبائر والدعامات.',
                    'تقييم وعلاج آلام الركبة والكتف والورك.',
                ]
            ],

            [
                'brief_description' => 'يُعنى هذا الاختصاص بعلاج أمراض واضطرابات الأنف، الأذن، الحنجرة، الرأس والعنق، مع التركيز على الجوانب التنفسية والسمعية والصوتية.',
                'details' => [
                    'تشخيص وعلاج التهابات الأذن والجيوب الأنفية.',
                    'فحص السمع والتوازن.',
                    'علاج انسداد الأنف والحساسية الأنفية.',
                    'معالجة مشاكل الحنجرة والصوت.',
                    'إزالة الشمع الزائد أو الأجسام الغريبة.',
                    'متابعة الشخير واضطرابات النوم.',
                ]
            ],

            [
                'brief_description' => 'يُعنى هذا القسم بتشخيص وعلاج أمراض العيون والبصر، من الفحوصات الروتينية وحتى الحالات الجراحية المعقدة.',
                'details' => [
                    'فحص النظر الشامل وتقييم النظر.',
                    'تشخيص ومعالجة التهاب ملتحمة العين.',
                    'علاج جفاف العين ومشاكل القرنية.',
                    'فحص ضغط العين للكشف عن الزرق (Glaucoma).',
                    'متابعة مرضى السكري وتقييم الشبكية.',
                    'وصف النظارات والعدسات الطبية.',
                ]
            ],
        ];

        $section=Section::where('section_type',SectionType::Clinics)->value('id');

        for ($i = 0; $i < count($services_en); $i++) {
            Service::create([
                'section_id'=>$section,
                'name_en'=>$services_en[$i],
                'name_ar'=>$services_ar[$i],
                'details_services_en'=>$details_services_en[$i],
                'details_services_ar'=>$details_services_ar[$i],
            ]);
        }


        $services_home_en=['General Medical Checkup','Physical Therapy','Sample Collection'];
        $services_home_ar=['فحص طبي عام','علاج فيزيائي','سحب عينة'];
        $details_services_home_en=[

            [
                'brief_description' => 'This section provides comprehensive medical checkups to help detect early health issues and assess the overall condition of the patient.',
                'details' => [
                    'Measuring blood pressure, temperature, and pulse.',
                    'General evaluation of health status.',
                    'Monitoring chronic conditions such as hypertension and diabetes.',
                    'Providing preventive health advice and consultations.',
                    'Referring patients to the appropriate specialty if needed.',
                ]
            ],

            [
                'brief_description' => 'Physical therapy relies on therapeutic exercises and techniques to relieve pain, improve mobility, and restore musculoskeletal function.',
                'details' => [
                    'Treatment of neck, back, and joint pain.',
                    'Rehabilitation after fractures and surgeries.',
                    'Customized exercises for balance and mobility.',
                    'Treatment of paralysis or nerve injuries.',
                    'Therapies using heat, cold, or ultrasound.',
                ]
            ],

            [
                'brief_description' => 'The sample collection department offers safe and professional services for collecting and preparing laboratory samples from patients.',
                'details' => [
                    'Drawing and analyzing blood samples.',
                    'Collecting urine, stool, and sputum samples.',
                    'Ensuring safety and hygiene during sample collection.',
                    'Preparing samples for transfer to the lab.',
                    'Educating patients on pre-test preparation.',
                ]
            ],


        ];
        $details_services_home_ar=[

            [
                'brief_description' => 'يهدف هذا القسم إلى تقديم فحص طبي شامل يساعد على الكشف المبكر عن المشاكل الصحية وتقييم الحالة العامة للمريض.',
                'details' => [
                    'قياس الضغط والحرارة ونبض القلب.',
                    'تحليل عام للحالة الصحية.',
                    'متابعة الأمراض المزمنة مثل الضغط والسكري.',
                    'تقديم استشارات صحية ونصائح وقائية.',
                    'تحويل المريض للاختصاص المناسب إن لزم.',
                ]
            ],

            [
            'brief_description' => 'يعتمد العلاج الفيزيائي على تمارين وحركات علاجية لتخفيف الألم وتحسين الحركة واستعادة الوظائف العضلية والهيكلية.',
            'details' => [
                'علاج آلام الرقبة والظهر والمفاصل.',
                'إعادة تأهيل بعد الكسور والعمليات الجراحية.',
                'تمارين مخصصة لتحسين التوازن والحركة.',
                'علاج حالات الشلل النصفي أو إصابات الأعصاب.',
                'تقنيات علاج بالحرارة، البرودة أو الأمواج الصوتية.',
                ]
            ],

            [
            'brief_description' => 'يقدّم قسم سحب العينات خدمات دقيقة وسريعة لجمع وتحضير العينات المخبرية من المرضى بشكل آمن ومهني.',
            'details' => [
                'سحب عينات دم وفحصها.',
                'جمع عينات البول والبراز والبلغم.',
                'التأكد من شروط السلامة والنظافة أثناء السحب.',
                'تحضير العينات للنقل إلى المختبر.',
                'إعلام المرضى بتعليمات التحضير قبل الفحوصات.',
                 ]
            ],
        ];

        $services_homeCare=Section::where('section_type',SectionType::HomeCare)->value('id');
        for ($i=0; $i<count($details_services_home_ar); $i++) {
            Service::create([
                'section_id'=>$services_homeCare,
                'name_en'=>$services_home_en[$i],
                'name_ar'=>$services_home_ar[$i],
                'details_services_en'=>$details_services_home_en[$i],
                'details_services_ar'=>$details_services_ar[$i],
            ]);
        }

    }
}
