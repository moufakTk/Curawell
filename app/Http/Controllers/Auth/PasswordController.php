<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'sometimes|string|email|exists:users,email',
            'phone'    => 'sometimes|string|exists:users,phone',
            'reset_password_token' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        try {

            $user = User::when($request->email, fn($q) => $q->where('email', $request->email))
                ->when($request->phone, fn($q) => $q->where('phone', $request->phone))
                ->first();

            if (!$user || $user->reset_password_token !== $request->reset_password_token) {
                return response()->json(['message' => 'Invalid token or user not found.'], 422);
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_password_token' => null,
                'reset_password_token_expires_at' => null,
            ]);

            return response()->json(['message' => 'Password has been reset successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

}
