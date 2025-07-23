<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\Verification\CheckExpiredVerificationCodeJob;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use App\Models\VerificationCode;
use App\Services\AuthServices\VerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    protected $VerificationService;

    public function __construct(VerificationService $VerificationService)
    {
        $this->VerificationService = $VerificationService;
    }

    public function sendcode(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|exists:users,phone',
            'email' => 'nullable|exists:users,email',
            'type' => 'required|in:verify,reset_password',
            'channel' => 'required|in:phone,email',
        ]);

        try {
            $data = $this->VerificationService->sendCode($request);
            return response()->json($data, 200);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], $exception->getCode()?0:404);
        }
    }


    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone'   => 'sometimes|exists:users,phone',
            'email'   => 'sometimes|exists:users,email',
            'type'    => 'required|in:verify,reset_password',
            'channel' => 'required_if:type,verify|in:phone,email',
            'code'    => 'required|min:6|max:6|exists:verification_codes,code',
        ]);

        try {
            $data = $this->VerificationService->verifyCode($request);
            return response()->json($data);
        } catch (\Exception $e) {
            $status = $e->getCode() ?: 500;

            return response()->json([
                'message' => $e->getMessage()
            ], $status);
        }
    }


}
