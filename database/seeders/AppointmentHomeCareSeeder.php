<?php

namespace Database\Seeders;

use App\Models\AppointmentHomeCare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentHomeCareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppointmentHomeCare::factory()->count(40)->create();
    }

}
