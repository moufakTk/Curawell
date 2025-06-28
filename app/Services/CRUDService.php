<?php

namespace App\Services;

use App\Enums\Users\DoctorType;
use App\Enums\Users\UserType;
use App\Models\Doctor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CRUDService
{



    public function registerDoctor($request)
    {

        try {
            $registered = DB::transaction(function () use ($request) {
                $birthday = Carbon::parse($request->birthday);

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name'  => $request->last_name,
                    'birthday'   => $request->birthday,
                    'age'        => $birthday->age,
                    'gender'     => $request->gender,
                    'email'      => $request->email,
                    'password'   => Hash::make($request->password),
                    'phone'      => $request->phone,
                    'address'    => $request->address,
                    'user_type'  => UserType::Doctor,
                ]);

                $doctor = Doctor::create([
                    'user_id'   => $user->id,
                    'respective_en'=>$request->respective_en,
                    'respective_ar'=>$request->respective_ar,
                    'experience_years'=>$request->experience_years,
                    'services_en'=>$request->services_en,
                    'services_ar'=>$request->services_ar,
                    'bloodGroup'=>$request->bloodGroup,
                    'start_in'=>$request->start_in,
                    'hold_end'=>$request->hold_end,
                    'doctor_type'=>$request->doctor_type,  // لازم يفوتها متل ما موجودة بالاينوم
                ]);

                if(DoctorType::tryFrom($request->input('doctor_type')) === DoctorType::Clinic){
                    $user->assignRole(DoctorType::Clinic->defaultRole());
                }elseif (DoctorType::tryFrom($request->input('doctor_type')) === DoctorType::Laboratory){
                    $user->assignRole(DoctorType::Laboratory->defaultRole());
                }else{$user->assignRole(UserType::Doctor->defaultRole());}

                return $user->load('doctor');
            });
            return $registered ;
        } catch (\Exception $e) {
            // ممكن ترجع رد مناسب:
            throw new \Exception("Registration failed: " . $e->getMessage(), 500);
        }

    }


    public function registerUser($request){

        try {

        $birthday = Carbon::parse($request->birthday);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'birthday'   => $request->birthday,
            'age'        => $birthday->age,
            'gender'     => $request->gender,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'phone'      => $request->phone,
            'address'    => $request->address,
            'user_type'  => UserType::tryFrom($request->input('user_type')),
        ]);

        $user->assignRole(UserType::tryFrom($request->input('user_type'))->defaultRole());

        return $user;
        } catch (\Exception $e) {
            // ممكن ترجع رد مناسب:
            throw new \Exception("Registration failed: " . $e->getMessage(), 500);
        }
    }









}
