<?php

namespace Database\Seeders;

use App\Models\Point;
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


    }
}
