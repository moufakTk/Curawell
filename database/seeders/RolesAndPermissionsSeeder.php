<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */



    public function run(): void
    {
        //

        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Secretary']);
        Role::create(['name' => 'Reception']);
        Role::create(['name' => 'Driver']);
        Role::create(['name' => 'Doctor']);
        Role::create(['name' => 'Patient']);




    }
}
