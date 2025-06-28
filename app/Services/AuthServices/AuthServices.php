<?php

namespace App\Services\AuthServices;


use App\Enums\Users\UserType;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
use SebastianBergmann\CodeUnit\FileUnit;
use Spatie\Permission\Models\Role;

class AuthServices
{
    protected $verificationService;
    public function __Construct(VerificationController $verificationService){
        $this->verificationService = $verificationService;
    }


//'first_name' => 'required|string|between:2,100',
//'last_name' => 'required|string|between:2,100',
//'birthday' => 'required|date',
//'gender' => 'required|string|in:male,female',
//'email' => 'required|string|email|max:100|unique:users',
//'password' => 'required|string|confirmed|min:8',
//'phone' => 'required|string|between:10,20',

public function register($request){
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
                'user_type'  => UserType::Patient,
            ]);

            $patient = Patient::create([
                'user_id'            => $user->id,
                'civil_id_number'    => $request->civil_id_number,
                'alternative_phone'  => $request->alternative_phone,
            ]);

            $medical_history = MedicalHistory::create([
                'patient_id'         => $patient->id,
                'chronic_diseases'   => $request->chronic_diseases,
                'new_diseases'       => $request->new_diseases,
                'allergies'          => $request->allergies,
                'blood_group'        => $request->blood_group,
                'weight'             => $request->weight,
                'height'             => $request->height,
            ]);

            $user->assignRole(UserType::Patient->defaultRole());
//            $user->load('roles.permissions');
            return $user;
            });
        $this->verificationService->sendCode($registered,'phone');
        $this->verificationService->sendCode($registered,'email');
        return $registered;
        } catch (\Exception $e) {
            // ممكن ترجع رد مناسب:
        throw new \Exception("Registration failed: " . $e->getMessage(), 500);
        }
}
}
