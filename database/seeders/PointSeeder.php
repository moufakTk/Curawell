<?php

namespace Database\Seeders;

use App\Models\Point;
use App\Models\Replacement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $name_en =['Book an appointment online','Request delivery service online','Request home service online'];
        $name_ar=['حجز موعد الكترونيا','طلب خدمة التوصيل الكترونيا','طلب خدمة منزلية الكترونيا'];
        $num_point=[10,5,15];

        for($i=0;$i<count($name_en);$i++){
            Point::create([
                'name_en'=>$name_en[$i],
                'name_ar'=>$name_ar[$i],
                'point_number'=>$num_point[$i],
                'has_source'=>true,
            ]);
        }

        $name_r_en=['Dental','Beauty'];
        $name_r_ar=['أسنان','تجميل'];
        $description_en=['asnan','tajmel'];
        $description_ar=['معاينة استشارية مجانية في قسم الاسنان','معاينة استشارية مجانية في قسم التجميل'];
        $num=[35,30];

        for($i=0;$i<count($name_r_en);$i++){
            Replacement::create([
                "name_en"=>$name_r_en[$i],
                "name_ar"=>$name_r_ar[$i],
                'description_en'=>$description_en[$i],
                'description_ar'=>$description_ar[$i],
                "replace_point_num"=>$num[$i],
                'is_active'=>true
            ]);
        }


    }
}
