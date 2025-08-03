<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $competences_Dental_en = ['orthodontia','Oral and Maxillofacial Surgery','Pediatric Dentistry','Periodontics',"General"];
        $competences_Dental_ar =['تقويم الأسنان','جراحة الفم والفكين','طب أسنان الأطفال ',' اللثة وأمراض دواعم الأسنان','عام'];
        $competences_Dental_details_en=[
            [
                'description_en' => 'Orthodontics focuses on correcting misaligned teeth and jaws using braces or other appliances.',
                'details_en' => [
                    'Diagnosis and treatment of dental misalignment.',
                    'Installation of metal or clear braces.',
                    'Treatment of overbite, underbite, and teeth crowding.',
                    'Use of retainers to maintain alignment after treatment.',
                ],
            ],
            [
                'description_en' => 'This specialty deals with surgical procedures involving the mouth, jaws, and facial structures.',
                'details_en' => [
                    'Surgical extraction of impacted teeth (e.g., wisdom teeth).',
                    'Treatment of jaw fractures and facial trauma.',
                    'Removal of oral cysts or tumors.',
                    'Dental implants placement.',
                ],
            ],
            [
                'description_en' => 'Pediatric dentistry provides dental care for children from infancy through adolescence.',
                'details_en' => [
                    'Preventive treatments such as fluoride and sealants.',
                    'Treatment of baby teeth decay.',
                    'Behavior management and dental education for kids.',
                    'Monitoring the growth and development of teeth.',
                ],
            ],
            [
                'description_en' => 'Periodontics focuses on the prevention, diagnosis, and treatment of gum diseases and supporting structures of the teeth.',
                'details_en' => [
                    'Treatment of gum inflammation and infections (e.g., gingivitis, periodontitis).',
                    'Deep cleaning procedures like scaling and root planing.',
                    'Gum graft surgeries for receding gums.',
                    'Maintenance therapy for patients with periodontal disease.',
                ],
            ],
            [
                'description_en'=>'',
                'details_en' => '',
            ]

        ];
        $competences_Dental_details_ar=[
            [
                'description_ar' => 'يعنى تقويم الأسنان بتصحيح اصطفاف الأسنان والفكين باستخدام أجهزة مثل التقويم.',
                'details_ar' => [
                    'تشخيص وعلاج مشاكل اصطفاف الأسنان.',
                    'تركيب تقويم معدني أو شفاف.',
                    'علاج حالات بروز أو تزاحم الأسنان.',
                    'استخدام المثبتات للحفاظ على نتائج التقويم.',
                ],
            ],
            [
                'description_ar' => 'يعنى هذا الاختصاص بالإجراءات الجراحية التي تشمل الفم والفكين والوجه.',
                'details_ar' => [
                    'خلع الأسنان المنطمرة جراحياً (مثل ضرس العقل).',
                    'علاج كسور الفك وإصابات الوجه.',
                    'إزالة الأكياس أو الأورام داخل الفم.',
                    'زرع الأسنان جراحياً.',
                ],
            ],
            [
                'description_ar' => 'يعنى طب أسنان الأطفال برعاية الفم والأسنان لدى الأطفال من الطفولة حتى المراهقة.',
                'details_ar' => [
                    'العلاجات الوقائية مثل الفلوريد والسدادات.',
                    'علاج تسوس الأسنان اللبنية.',
                    'إدارة سلوك الأطفال وتثقيفهم صحياً.',
                    'متابعة نمو وتطور الأسنان.',
                ],
            ],
            [
                'description_ar' => 'يعنى طب اللثة بتشخيص وعلاج أمراض اللثة والأنسجة الداعمة للأسنان.',
                'details_ar' => [
                    'علاج التهابات اللثة والأمراض الداعمة للأسنان (مثل التهاب اللثة والتهاب دواعم الأسنان).',
                    'إجراءات تنظيف عميق مثل التجريف وتنعيم الجذور.',
                    'عمليات ترقيع اللثة لحالات انحسار اللثة.',
                    'متابعة وعلاج مستمر لمرضى اللثة المزمنين.',
                ],
            ],
            [
                'description_en'=>'',
                'details_en' => '',
            ],
        ];

        $service1 =Service::where('name_en','Dental')->value('id');
        for($i=0 ;$i<count($competences_Dental_ar) ;$i++){
            Competence::create([
                'service_id'=>$service1,
                'name_en'=>$competences_Dental_en[$i],
                'name_ar'=>$competences_Dental_ar[$i],
                'brief_description_en'=>$competences_Dental_details_en[$i],
                'brief_description_ar'=>$competences_Dental_details_ar[$i],
            ]);
        }





        $competences_Beauty_en = [
            "Cosmetic Nose Surgery",
            "Facelift and Neck Lift Surgery",
            "Hair Transplantation",
            "Laser Aesthetics",
            "Skin Care",
            'General'];
        $competences_Beauty_ar=[
            "جراحة الأنف التجميلية",
            "جراحة شد الوجه والرقبة",
            "زراعة الشعر",
            "التجميل بالليزر",
            "العناية بالبشرة",
            'عام'];
        $competences_Beauty_details_en=[


                [

                    "brief_description" => "Focuses on improving the shape and harmony of the nose with facial features.",
                    "details" => [
                        "Correcting nasal deviation or size.",
                        "Treating breathing issues caused by nasal deformities.",
                        "Enhancing the aesthetic appearance of the nose."
                    ]
                ],
                [

                    "brief_description" => "Reduces wrinkles and tightens skin to improve the appearance of the face and neck.",
                    "details" => [
                        "Removing sagging in the face and neck.",
                        "Tightening skin and underlying tissues.",
                        "Improving signs of aging."
                    ]
                ],
                [

                    "brief_description" => "Targets baldness and restores hair in a natural way.",
                    "details" => [
                        "Transplanting hair follicles from dense to bald areas.",
                        "Using techniques like FUE or FUT.",
                        "Permanent results with natural appearance."
                    ]
                ],
                [

                    "brief_description" => "Uses laser technology to improve skin appearance and treat issues.",
                    "details" => [
                        "Removing pigmentation and acne scars.",
                        "Skin tightening and collagen stimulation.",
                        "Laser hair removal."
                    ]
                ],
                [

                    "brief_description" => "Aims to maintain skin freshness and health.",
                    "details" => [
                        "Deep facial cleansing.",
                        "Peeling and moisturizing sessions.",
                        "Treating acne and dark spots."
                    ]
                ],
                [
                    'description_en'=>'',
                    'details_en' => '',
                ],


        ];
        $competences_Beauty_details_ar=[

            [
                "brief_description"=> "تُعنى بتحسين شكل الأنف وتناسقه مع ملامح الوجه.",
                "details"=> [
                  "تصحيح انحراف الأنف أو حجم الأنف.",
                  "إصلاح مشاكل تنفسية ناتجة عن تشوهات الأنف.",
                  "تحسين الشكل الجمالي للأنف."
                ]
            ],
            [
                "brief_description"=> "تعمل على تقليل التجاعيد وشد الجلد لتحسين مظهر الوجه والرقبة.",
                "details"=> [
                  "إزالة الترهلات في الوجه والرقبة.",
                  "شد الجلد والأنسجة تحت الجلد.",
                  "تحسين مظهر علامات التقدم في السن."
                ],
            ],
            [

                "brief_description"=> "تستهدف علاج الصلع واستعادة الشعر بطريقة طبيعية.",
                "details"=> [
                  "نقل بصيلات الشعر من المناطق الكثيفة إلى مناطق الصلع.",
                  "استخدام تقنيات مثل FUE أو FUT.",
                  "نتائج دائمة وشكل طبيعي."
                ],
            ],
            [

                "brief_description"=> "يستخدم الليزر لتحسين مظهر البشرة ومعالجة مشاكلها.",
                "details"=> [
                  "إزالة التصبغات وآثار الحبوب.",
                  "شد البشرة وتحفيز الكولاجين.",
                  "إزالة الشعر الزائد بالليزر."
                ],
            ],

            [

                "brief_description"=> "تهدف إلى الحفاظ على نضارة وصحة البشرة.",
                "details"=> [
                  "تنظيف عميق للبشرة.",
                  "جلسات تقشير وترطيب.",
                  "علاج حب الشباب والبقع الداكنة."
                ]
            ],
            [
                'description_en'=>'',
                'details_en' => '',
            ],
        ];

        $service2 =Service::where('name_en','Beauty')->value('id');
        for($i=0 ;$i<count($competences_Beauty_en) ;$i++){
            Competence::create([
                'service_id'=>$service2,
                'name_en'=>$competences_Beauty_en[$i],
                'name_ar'=>$competences_Beauty_ar[$i],
                 'brief_description_en'=>$competences_Beauty_details_en[$i],
                'brief_description_ar'=>$competences_Beauty_details_ar[$i],
            ]);
        }





        $competences_Neurology_en=['General'];
        $competences_Neurology_ar=['عام'];
        $service3= Service::where('name_en','Neurology')->value('id');
        Competence::create([
            'service_id'=>$service3,
            'name_en'=>$competences_Neurology_en[0],
            'name_ar'=>$competences_Neurology_ar[0],
        ]);
//        $competences_Neurology_details_en=[];
//        $competences_Neurology_details_ar=[];
//
//
//
        $competences_Dermatology_en=['General'];
        $competences_Dermatology_ar=['عام'];
        $service4= Service::where('name_en','Dermatology')->value('id');
        Competence::create([
            'service_id'=>$service4,
            'name_en'=>$competences_Dermatology_en[0],
            'name_ar'=>$competences_Dermatology_ar[0],
        ]);
//        $competences_Dermatology_details_en=[];
//        $competences_Dermatology_details_ar=[];
//
//
        $competences_Orthopedic_en=['General'];
        $competences_Orthopedic_ar=['عام'];
        $service5= Service::where('name_en','Orthopedic')->value('id');
        Competence::create([
            'service_id'=>$service5,
            'name_en'=>$competences_Orthopedic_en[0],
            'name_ar'=>$competences_Orthopedic_ar[0],
        ]);
//        $competences_Orthopedic_details_en=[];
//        $competences_Orthopedic_details_ar=[];
//
//
//
        $competences_ENT_en=['General'];
        $competences_ENT_ar=['عام'];
        $service6= Service::where('name_en','ENT')->value('id');
        Competence::create([
            'service_id'=>$service6,
            'name_en'=>$competences_ENT_en[0],
            'name_ar'=>$competences_ENT_ar[0],
        ]);
//        $competences_ENT_details_en=[];
//        $competences_ENT_details_ar=[];
//
//
        $competences_Ophthalmology_en=['General'];
        $competences_Ophthalmology_ar=['عام'];
        $service7= Service::where('name_en','Ophthalmology')->value('id');
        Competence::create([
            'service_id'=>$service7,
            'name_en'=>$competences_Ophthalmology_en[0],
            'name_ar'=>$competences_Ophthalmology_ar[0],
        ]);
//        $competences_Ophthalmology_details_en=[];
//        $competences_Ophthalmology_details_ar=[];




    }
}
