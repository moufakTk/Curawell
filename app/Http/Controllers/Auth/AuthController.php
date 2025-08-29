<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Models\User;
use App\Services\AuthServices\AuthServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
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
        ],[
            'login.required' => __("validation.required"),
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
public function updateMissingInfo(Request $request){
        $user=auth()->user();
        $request->validate([
            'first_name' => 'required|string|between:2,100',
            'last_name'  => 'required|string|between:2,100',
            'birthday'   => 'required|date',
            'gender'     => 'required|string|in:male,female',
            'phone'      => 'required|string|between:10,20|unique:users,phone,' . $user->id,
            'address'    => 'required|string',
            'civil_id_number' => 'required|string|between:8,15|unique:patients,civil_id_number,' . optional($user->patient)->id,


        ]);
    try {
$data = $this->authServices->updateMissingData($request, $user);
return ApiResponse::success($data,'تم اضافة البيانات',200);

    }catch (\Exception $e){
        $code = (int)$e->getCode();
        if ($code < 100 || $code > 599) {
            $code = 500;
        }

        return ApiResponse::error([], $e->getMessage(), $code);    }
}
    public function loginWithGoogle(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')->stateless()
                ->userFromToken($request->token);

            $user = $this->authServices->loginWithGoogle($googleUser);

            return response()->json([
                'user' => $user['user'],
                'token' => $user['user']->createToken('google_login')->plainTextToken
//                ,'google_user'=>$googleUser
                ,'missing'=>$user['missing']
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Google login failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    Public function register(RegisterRrequest $request){


            $user =   $this->authServices->register($request);
             return response()->json([
                'message' => __('messages.register_success'),
                'data' => $user

            ],201);



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
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function callback(){
        $user =Socialite::driver('google')->stateless()->user();

//
        return response()->json([
            'adsasd'=>$user->token
        ]);

    }





}
