<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->count(67)->doctor()->create();
        User::factory()->count(30)->patient()->create();
        User::factory()->count(20)->nurse()->create();
        User::factory()->count(4)->reception()->create();
        User::factory()->count(7)->secretary()->create();
        User::factory()->count(2)->driver()->create();

    }
}
