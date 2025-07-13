<?php

namespace Database\Seeders;

use App\Enums\Users\UserType;
use App\Models\Comment;
use App\Models\Doctor;
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
