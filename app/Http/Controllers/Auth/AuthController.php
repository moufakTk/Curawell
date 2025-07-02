<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Models\User;
use App\Services\AuthServices\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpKernel\Exception\HttpException;


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
        try {
         $data = $this->authServices->login($request);

            return response()->json($data, 200);
        }catch (\Exception $e){
            $status = $e->getCode() ?: 400;
            return response()->json([
                'message' => $e->getMessage()
            ], $status);
        }

    }

    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->getAccessTokenResponse($request->code); // أول شي منبدل الكود بالتوكن

            $accessToken = $googleUser['access_token'];

            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($accessToken); // بعدين منجيب معلومات المستخدم

            // هون بتحط الكود تبع إنشاء أو تسجيل الدخول للمستخدم
            $user = $this->authServices->loginWithGoogle($googleUser);

            return response()->json([
                'user' => $user,
                'token' => $user->createToken('google_login')->plainTextToken
                ,'google_user'=>$googleUser
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
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
            ], $e->getCode());
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
