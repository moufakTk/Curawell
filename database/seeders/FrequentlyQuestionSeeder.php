<?php

namespace Database\Seeders;

use App\Models\FrequentlyQuestion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FrequentlyQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        FrequentlyQuestion::factory()->count(20)->create();
    }
}
