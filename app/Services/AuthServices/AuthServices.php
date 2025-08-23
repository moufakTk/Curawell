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
                'patient_num'=>str_pad((Patient::max('id')??0 )+ 1, 8, '0', STR_PAD_LEFT)
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
    public function login(Request $request)
    {
        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw new \Exception(__('messages.Incorrect_credentials'), 401);
        }

        // تسجيل الدخول دائماً (لكن امنع العمليات الحساسة بميدلوير إذا غير مُؤكَّد)
        $token = $user->createToken('auth_token')->plainTextToken;

        $needsVerification = false;
        $verification = ['required' => false];

        // إذا الدخول كان عبر الهاتف وهو غير مؤكد
        if ($request->login === $user->phone && !$user->phone_verified_at) {
            $code = $this->verificationService->sendVerificationCode($user, 'phone', 'verify');

            $needsVerification = true;
            $verification = [
                'required'   => true,
                'channel'    => 'phone',
                'method'     => 'otp',
                'to'         => $user->phone,
                'debug_code' => app()->environment('local') ? $code : null,
            ];
        }

        // إذا الدخول كان عبر الإيميل وهو غير مؤكد
        if ($request->login === $user->email && !$user->email_verified_at) {
            $code = $this->verificationService->sendVerificationCode($user, 'email', 'verify');

            $needsVerification = true;
            $verification = [
                'required'   => true,
                'channel'    => 'email',
                'type'=>'verify',
                'to'         => $user->email,
                'debug_code' => app()->environment('local') ? $code : null,
            ];
        }
        $missing = $this->missing($user);

        return [
            'status'       => 'success',
            'message'      => $needsVerification
                ? 'Login successful. Verification required.'
                : 'Login successful.',
            'token'        => $token,
            'user'         => $user->load('patient.medical_history'),
            'verification' => $verification,
            'missing'=>$missing
        ];
    }

//    protected function maskPhone(?string $phone): ?string
//    {
//        if (!$phone) return null;
//        $len = strlen($phone);
//        if ($len <= 4) return str_repeat('*', $len);
//        return substr($phone, 0, $len - 4) . substr($phone, -4);
//    }
//
//    protected function maskEmail(?string $email): ?string
//    {
//        if (!$email || !str_contains($email, '@')) return null;
//        [$name, $domain] = explode('@', $email, 2);
//        $maskName = strlen($name) > 2 ? substr($name, 0, 2) . str_repeat('*', max(1, strlen($name) - 2)) : str_repeat('*', strlen($name));
//        // أخفي جزء من الدومين قبل النقطة الأولى
//        $parts = explode('.', $domain);
//        $parts[0] = strlen($parts[0]) > 1 ? substr($parts[0], 0, 1) . str_repeat('*', max(1, strlen($parts[0]) - 1)) : '*';
//        return $maskName . '@' . implode('.', $parts);
//    }


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
                'password' => Hash::make(Str::random(8)),
            ]

        );

        $missing = $this->missing($user);


        return ['user'=>$user,
            'missing'=>$missing];
    }

    protected array $userFields = [
        'first_name',
        'last_name',
        'birthday',
        'gender',
        'email',
        'phone',
        'address',
    ];

    protected array $patientFields = [
        'civil_id_number',
    ];

    public function missing(User $user): array
    {
        $missing = [];

        // check user fields
        foreach ($this->userFields as $field) {
            if (empty($user->{$field})) {
                $missing[] = $field;
            }
        }

        // check patient relation
        $patient = $user->patient;
        if ($patient) {
            foreach ($this->patientFields as $field) {
                if (empty($patient->{$field})) {
                    $missing[] = $field;
                }
            }
        } else {
            // ما عندو record بالـ patient أصلاً
            $missing = array_merge($missing, $this->patientFields);
        }

        return [
            'required' => count($missing) > 0, // true إذا ناقص شي
            'missing'  => $missing,            // ممكن ترجعها فاضية إذا بدك بس فلاغ
        ];
    }

    public function updateMissingData($request,$user)
    {
$user1 = DB::transaction(function () use ($request,$user) {
    $birthday = Carbon::parse($request->birthday);
        $user->update([
            'first_name' => $request->first_name??$user->first_name,
            'last_name'  => $request->last_name??$user->last_name,
            'birthday'   => $request->birthday??$user->birthday,
            'age'=>            $birthday->age??$user->age,

            'gender'     => $request->gender??$user->gender,
            'phone'      => $request->phone??$user->phone,
            'address'    => $request->address??$user->address,

        ]);

        if ($user->patient) {
            $user->patient->update([
                'civil_id_number' => $request->civil_id_number??$user->patient->civil_id_number,
            ]);
        } else {
            // إذا ما عندو patient record، أنشئ واحد
            $user->patient()->create([
                'civil_id_number' => $request->civil_id_number ??$user->id,
                'patient_num'=>str_pad((Patient::max('id')??0 )+ 1, 8, '0', STR_PAD_LEFT)
            ]);
        }
return $user;
});

        // ✅ احسب إذا بعدو ناقص شي
        $profileStatus = $this->missing($user1->fresh()->load('patient'));

        return [
            'message' => $profileStatus['required']
                ? 'Profile updated. More info still required.'
                : 'Profile completed successfully.',
            'data'=>[
                'user'    => $user->fresh()->load('patient'),
                'profile' => $profileStatus,

            ]
        ];
    }

}
