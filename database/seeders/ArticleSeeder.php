<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [
            [
                'title_en' => 'The Impact of Artificial Intelligence on Healthcare',
                'title_ar' => 'تأثير الذكاء الاصطناعي على الرعاية الصحية',
                'brief_description_en' => 'A scientific overview of how AI is transforming diagnostics, patient monitoring, and clinical decision-making.',
                'brief_description_ar' => 'نظرة علمية حول كيفية قيام الذكاء الاصطناعي بإحداث تحول في التشخيص، ومراقبة المرضى، ودعم القرارات السريرية.',
                'path_link' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC6616181/',
            ],
            [
                'title_en' => 'Diabetes Management and Lifestyle Interventions',
                'title_ar' => 'إدارة مرض السكري والتدخلات المتعلقة بنمط الحياة',
                'brief_description_en' => 'Discusses the importance of diet, exercise, and early screening in managing type 2 diabetes.',
                'brief_description_ar' => 'تناقش المقالة أهمية النظام الغذائي، والرياضة، والكشف المبكر في إدارة داء السكري من النوع الثاني.',
                'path_link' => 'https://www.who.int/news-room/fact-sheets/detail/diabetes',
            ],
            [
                'title_en' => 'Cardiovascular Disease Prevention Strategies',
                'title_ar' => 'استراتيجيات الوقاية من أمراض القلب والأوعية الدموية',
                'brief_description_en' => 'An evidence-based review on how to reduce cardiovascular risk factors.',
                'brief_description_ar' => 'مراجعة علمية مبنية على الأدلة حول كيفية تقليل عوامل الخطر المتعلقة بأمراض القلب.',
                'path_link' => 'https://www.ahajournals.org/doi/full/10.1161/CIRCULATIONAHA.110.968735',
            ],
            [
                'title_en' => 'Advances in Cancer Research and Treatment',
                'title_ar' => 'التطورات في أبحاث وعلاج السرطان',
                'brief_description_en' => 'Covers modern therapeutic approaches like immunotherapy and targeted therapy.',
                'brief_description_ar' => 'تغطي المقالة الأساليب العلاجية الحديثة مثل العلاج المناعي والعلاج الموجه.',
                'path_link' => 'https://www.cancer.gov/about-cancer/treatment/research',
            ],
            [
                'title_en' => 'The Role of Nutrition in Mental Health',
                'title_ar' => 'دور التغذية في الصحة النفسية',
                'brief_description_en' => 'Explores the link between dietary patterns and mental well-being.',
                'brief_description_ar' => 'تستعرض العلاقة بين الأنماط الغذائية والصحة النفسية.',
                'path_link' => 'https://www.ncbi.nlm.nih.gov/pmc/articles/PMC2738337/',
            ],
        ];

        foreach ($articles as $article) {
            Article::firstOrCreate(
                ['title_en' => $article['title_en']], // حتى ما تتكرر إذا شغلت seeder مرة تانية
                $article
            );
        }
    }
}
