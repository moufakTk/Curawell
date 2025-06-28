<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Models\User;
use App\Services\AuthServices\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $authServices;
    public function __construct(AuthServices $authServices){
        $this->authServices = $authServices;
    }
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Incorrect credentials.'
            ], 401);
        }

        if ($request->login === $user->email && !$user->email_verified_at) {
            return response()->json([
                'message' => 'Email not verified. Please verify first.',
                'needs_verification' => true,
                'channel' => 'email',
                'type' => 'verify'
            ], 403);
        }

        if ($request->login === $user->phone && !$user->phone_verified_at) {
            return response()->json([
                'message' => 'Phone number not verified. Please verify first.',
                'needs_verification' => true,
                'channel' => 'phone',
                'type' => 'verify'
            ], 403);
        }


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => $user->load('patient.medical_history')
        ]);
    }
    Public function register(RegisterRrequest $request){
        try {

            $user =   $this->authServices->register($request);
             return response()->json([
                'message' => __('messages.register_success'),
                'data' => $user

            ],201);

        }catch (\Exception $e){
            return response()->json([
                'message' => __('messages.register_failed'),
                'error'   => $e->getMessage()
            ], 500);
        }

    }
    public function logout()
    {
        try {
            auth()->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'تم تسجيل الخروج بنجاح.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل تسجيل الخروج: ' . $e->getMessage()
            ], 500);
        }
    }





}
