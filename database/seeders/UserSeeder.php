<?php

namespace Database\Seeders;

use App\Enums\Users\UserType;
use App\Models\Comment;
use App\Models\Doctor;
use App\Models\User;
use App\Models\UserDayTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $daysArabic = ['الاثنين', 'الثلاثاء', 'الأربعاء','الخميس','الجمعة','السبت', 'الأحد'];
        $daysEnglish = ['Monday', 'Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];

        User::factory()->count(81)->doctor()->create()->each(function ($user) use ($daysArabic, $daysEnglish) {
            for($i = 0; $i <count($daysArabic) ; $i++) {
                UserDayTime::factory()->create([
                    'user_id' => $user->id,
                    'day_en'=>$daysEnglish[$i],
                    'day_ar'=>$daysArabic[$i],
                ]);
            }
        });


        User::factory()->count(30)->patient()->create();


        $nurse=User::factory()->count(20)->nurse()->create();
        $nurse->each(function ($nurse) {
            $nurse->assignRole(UserType::Nurse->defaultRole());
        });


        $reception=User::factory()->count(4)->reception()->create();
        $reception->each(function ($reception) {
            $reception->assignRole(UserType::Reception->defaultRole());
        });

        $secretary=User::factory()->count(7)->secretary()->create();
        $secretary->each(function ($secretary) {
            $secretary->assignRole(UserType::Secretary->defaultRole());
        });

        $driver=User::factory()->count(2)->driver()->create();
        $driver->each(function ($driver) {
            $driver->assignRole(UserType::Driver->defaultRole());
        });

    }
}
