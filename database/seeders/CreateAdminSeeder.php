<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Enums\Users\UserType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $admin =User::where('id',1)->first();

        if(!$admin) {

            $admin =User::create([
                'first_name'=>'moufak',
                'last_name'=>'AlTaklh',
                'email'=>'muofak@gmail.com',
                'password'=>Hash::make('moufak123456789'),
                'age'=>21 ,
                'gender'=>Gender::MALE,
                'user_type'=>UserType::Admin ,
                'is_active'=>true,
            ]);

        }

        if(!$admin->hasRole(UserType::Admin->defaultRole())) {
            $admin->assignRole(UserType::Admin->defaultRole());
        }

        $this->command->info('✓ Admin created or already exists');

    }
}
