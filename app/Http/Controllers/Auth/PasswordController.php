<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthServices\PasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    Protected $passwordService;
    public function __construct(PasswordService $passwordService){
        $this->passwordService = $passwordService;
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'sometimes|string|email|exists:users,email',
            'phone'    => 'sometimes|string|exists:users,phone',
            'reset_password_token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        try {

               $this->passwordService->resetPassword($request);

            return response()->json(['message' => 'Password has been reset successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

}
