<?php

namespace Database\Seeders;

use App\Enums\Users\UserType;

use App\Models\Service;
use App\Models\User;
use App\Models\WorkEmployee;
use App\Models\WorkLocation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecretaryWorkLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //


        $service =Service::pluck('id')->toArray();
        $i =0;

        $user =User::where('user_type',UserType::Secretary)->get()->each(function ($user) use ($service ,&$i) {

            $user->active_work_location()->create([
                'locationable_type'=>Service::class,
                'locationable_id'=>$service[$i] ,
                'active'=>true
            ]);
            $i++;
        });




    }
}
