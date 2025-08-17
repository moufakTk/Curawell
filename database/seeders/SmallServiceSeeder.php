<?php

namespace Database\Seeders;

use App\Models\SmallService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmallServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        SmallService::factory()->count(100)->create();
    }


}
