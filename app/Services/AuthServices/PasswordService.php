<?php

namespace App\Services\AuthServices;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
    public function resetPassword($request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::when($request->email, fn($q) => $q->where('email', $request->email))
                ->when($request->phone, fn($q) => $q->where('phone', $request->phone))
                ->whereNotNull('reset_password_token')
                ->first();

            if (!$user || $user->reset_password_token !== $request->reset_password_token) {
                throw new \Exception('Invalid or expired token.');
            }

            $user->update([
                'password' => Hash::make($request->password),
                'reset_password_token' => null,
                'reset_password_token_expires_at' => null,
            ]);

            return response()->json(['message' => 'Password reset successfully.'], 200);
        });
    }


}
