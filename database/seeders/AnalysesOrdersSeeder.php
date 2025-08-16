<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalysesOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\AnalyzeOrder::factory(5)
            ->withAnalyses(3)
            ->withSamples(2)
            ->create();
    }
}
