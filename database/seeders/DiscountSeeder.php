<?php

namespace Database\Seeders;

use App\Enums\Users\DoctorType;
use App\Models\Discount;
use App\Models\DiscountDivision;
use App\Models\DiscountDoctor;
use App\Models\Division;
use App\Models\Doctor;
use App\Models\Doctor_examin;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $de =Doctor::where('doctor_type',DoctorType::Clinic)->pluck('id');
        $di =Division::where('is_discounted' ,1)->pluck('id');



//        collect(range(1, 2))->each(function () use ($de) {
//            $discount =Discount::factory()->create(['discountable_type'=>Doctor_examin::class,'discountable_id'=>$d=$de->random()]);
//            Doctor_examin::where('id',$d)->update(['discount_rate'=>$discount->discount_rate]);
//        });
//
//        collect(range(1, 2))->each(function () use ($di) {
//            $discount =Discount::factory()->create(['discountable_type'=>Division::class,'discountable_id'=>$d=$di->random()]);
//            Division::where('id',$d)->update(['discount_rate'=>$discount->discount_rate]);
//        });

        for($i=0 ;$i<3 ;$i++){
            $deRandom = $de->random(2)->values();
            $diRandom = $di->random(2)->values();
            $discount=Discount::factory()->create();
            for ($j = 0 ; $j < 2 ; $j++) {
                DiscountDivision::create([
                    'discount_id'=>$discount->id,
                    'division_id'=>$diRandom[$j],
                    'discount_amount'=>$discount->discount_rate,
                ]);

                DiscountDoctor::create([
                    'discount_id'=>$discount->id,
                    'doctor_id'=>$deRandom[$j],

                ]);

            }



        }


    }
}
