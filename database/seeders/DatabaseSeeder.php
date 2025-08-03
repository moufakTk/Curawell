<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
////            'name' => 'Test User',
////            'email' => 'test@example.com',
////        ]);

        Artisan::call('permissions:add');

        $this->call([
            CreateAdminSeeder::class,
            SectionSeeder::class,
            ServiceSeeder::class,
            CompetenceSeeder::class,
            UserSeeder::class,
            DoctorSeeder::class,
            PatientSeeder::class,
            ArticleSeeder::class,
            EvaluctionSeeder::class,
            SettingSeeder::class,
            FrequentlyQuestionSeeder::class,
            SmallServiceSeeder::class,
            DivisionSeeder::class,
            DiscountSeeder::class,
            WorkLocationSeeder::class,
            WorkDaySeeder::class,
            WorkEmployeeSeeder::class,
            PointSeeder::class,
        ]);

    }
}
