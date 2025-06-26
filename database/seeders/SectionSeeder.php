<?php

namespace Database\Seeders;

use App\Enums\Services\SectionType;
use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $sections_en = ['Clinic','Laboratory','Emergency','HomeCare','Radiography'];
        $sections_ar = ['عيادات','مخبر','إسعاف','خدمة منزلية','تصوير شعاعي'];

        $section_brief_en=[
            'At our medical center, we offer specialized clinic services led by a team of highly qualified doctors across various medical fields. This service allows you to book and consult with doctors in a comfortable environment equipped with the latest medical technologies, ensuring the highest quality of care.',
            'art equipment and strict quality standards. Our experienced lab specialists ensure reliable results to support your doctor in making the right medical decisions.
            Whether routine checkups or specialized tests — your health starts with the right diagnosis.',
            ' Whether youre facing a medical emergency or need non-urgent medical transport, our trained teams are equipped to deliver immediate care and support on the move.
            Your safety and response time are our top priorities.',
            ' support, or follow-up after surgery, our qualified staff ensures professional and compassionate care in the comfort of your home.
            Because your health deserves attention — wherever you are.',
            'Our radiology unit provides high-quality imaging services including X-rays, ultrasounds, and more, performed by skilled technicians and reviewed by experienced radiologists. With modern equipment and fast results, we help your physician get a clearer picture of your health.
            Precise diagnostics for confident treatment decisions.',
        ];

        $sections_brief_ar=[
            'في مركزنا الطبي، نقدم خدمة العيادات المتخصصة التي تضم نخبة من الأطباء ذوي الكفاءة العالية في مختلف المجالات الطبية. تتيح لك هذه الخدمة حجز واستشارة الأطباء في بيئة مريحة ومجهزة بأحدث الأجهزة، لضمان أفضل رعاية صحية.',
            'في مركزنا الطبي، يقدم قسم المخبر خدمات تحليل دقيقة وسريعة باستخدام أحدث الأجهزة الطبية وأعلى معايير الجودة. يعمل فريقنا المتخصص على ضمان نتائج موثوقة تساعد طبيبك في اتخاذ القرار الطبي الصحيح.
سواء كانت تحاليل دورية أو فحوصات متخصصة – صحتك تبدأ بالتشخيص السليم.            ',
            'نُقدم خدمات إسعاف فورية وآمنة على مدار الساعة، مع فرق طبية مدربة ومجهزة للتعامل مع الحالات الطارئة أو نقل المرضى غير العاجل. نحرص على سرعة الاستجابة وتقديم الرعاية الطبية أثناء النقل لضمان سلامتك في كل لحظة.
سلامتك واستجابتنا السريعة هي أولويتنا.            ',
            'نقدّم لك الرعاية الطبية في منزلك من خلال خدماتنا المتخصصة، سواء كنت بحاجة إلى تمريض، متابعة بعد الجراحة، أو رعاية للمسنين. يحرص طاقمنا المؤهل على تقديم خدمة مهنية وإنسانية تضمن راحتك وجودة علاجك في بيئتك الخاصة.
لأن صحتك تستحق الاهتمام أينما كنت.            ',
            'فر قسم التصوير لدينا خدمات تصوير شعاعي عالية الجودة مثل الأشعة السينية والألتراساوند، بإشراف تقنيين مختصين وأطباء أشعة ذوي خبرة. نعتمد على أجهزة حديثة ونتائج سريعة لمساعدة طبيبك في تشخيص أدق لحالتك.
تشخيص دقيق لاتخاذ قرارات علاجية واثقة.            ',
        ];

        $section_type=[SectionType::Clinics,SectionType::LaboratoryAnalysis,SectionType::Emergency,SectionType::HomeCare,SectionType::Radiography];

        for($i = 0; $i <5; $i++) {
            Section::create([
                'name_en'=>$sections_en[$i],
                'name_ar'=>$sections_ar[$i],
                'brief_description_en'=>$section_brief_en[$i],
                'brief_description_ar'=>$sections_brief_ar[$i],
                'section_type'=>$section_type[$i],
            ]);
        }


    }
}
