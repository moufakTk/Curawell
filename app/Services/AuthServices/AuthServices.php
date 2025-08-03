<?php

namespace App\Services\AuthServices;


use App\Enums\Users\UserType;
use App\Http\Controllers\Auth\VerificationController;
use App\Models\MedicalHistory;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use PhpParser\Node\Expr\FuncCall;
use SebastianBergmann\CodeUnit\FileUnit;
use Spatie\Permission\Models\Role;

class AuthServices
{
    protected $verificationService;
    public function __Construct(VerificationService $verificationService){
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
                'hereditary_diseases'=>$request->hereditary_diseases,
                'allergies'          => $request->allergies,
                'blood_group'        => $request->blood_group,
                'weight'             => $request->weight,
                'height'             => $request->height,
            ]);

            $user->assignRole(UserType::Patient->defaultRole());
//            $user->load('roles.permissions');
            return $user;
            });
        $this->verificationService->sendVerificationCode($registered,'phone','verify');
//        $this->verificationService->sendVerificationCode($registered,'email','verify');
        return $registered;

}
    public function login( $request)
    {

            $user = User::where('email', $request->login)
                ->orWhere('phone', $request->login)
                ->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw new \Exception(__('messages.Incorrect_credentials'),401);
            }

//            if ($request->login === $user->email && !$user->email_verified_at) {
//                throw new \Exception('Email not verified. Please verify first.',403);
//            }
//
//            if ($request->login === $user->phone && !$user->phone_verified_at) {
//                throw new \Exception('Email not verified. Please verify first.',403);
//
//            }


            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'message' => 'Login successful.',
                'token' => $token,
                'user' => $user->load('patient.medical_history')
            ];


    }

    public function loginWithGoogle($googleUser)
    {
        $user = User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'first_name' => Str::before($googleUser->getName(), ' '),
                'last_name'  => Str::after($googleUser->getName(), ' '),
                'age' => 0, // أو 0 إذا الحقل مش nullable
                'email_verified_at' => now(),
                'user_type' => UserType::Patient, // أو حسب نوع المستخدم
                'password' => Hash::make(Str::random(r)),
            ]
        );

        return $user;
    }

}
