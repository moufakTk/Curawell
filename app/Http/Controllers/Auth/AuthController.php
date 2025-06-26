<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRrequest;
use App\Services\AuthServices\AuthServices;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authServices;
    public function __construct(AuthServices $authServices){
        $this->authServices = $authServices;
    }
Public function login(Request $request){}
    Public function register(RegisterRrequest $request){
        try {
         $user =   $this->authServices->register($request);
            return response()->json([
                'message' => 'User registered successfully',
                'data' => $user->load('patient.medical_history')

            ]);

        }catch (\Exception $e){
            return $e->getMessage();
        }

    }
    Public function logout(){}

}
