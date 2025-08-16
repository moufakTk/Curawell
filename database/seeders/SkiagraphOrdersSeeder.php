<?php

namespace Database\Seeders;

use App\Models\SkiagraphOrder;
use Database\Factories\SkiagraphOrderFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkiagraphOrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SkiagraphOrder::factory()->count(10)->create();
    }
}
