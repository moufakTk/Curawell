<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\Verification\CheckExpiredVerificationCodeJob;
use App\Mail\VerificationCodeEmail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function __construct()
    {
    }

    public function reSendcode(Request $request)
    {
        $request->validate([
            'phone'   => 'sometimes|exists:users,phone',
            'email'   => 'sometimes|exists:users,email',
            'type'    => 'required|in:verify,reset_password',
            'channel' => 'required|in:phone,email',
        ]);

        try {
            $user = User::query();

            if ($request->filled('phone')) {
                $user->where('phone', $request->phone);
            }

            if ($request->filled('email')) {
                $user->orWhere('email', $request->email);
            }

            $user = $user->first();

            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }

            $this->sendVerificationCode($user, $request->channel, $request->type);

            return response()->json([
                'message' => 'Verification code sent successfully.'
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }


    public function sendVerificationCode($user, $channel, $type)
    {
        $c = random_int(100000, 999999);

        // ان كان في كود قديم لنفس الشغلة والنوع بيمحي القديم وبيساوي واحد جديد
        VerificationCode::where('user_id', $user->id)
            ->where('channel', $channel)
            ->where('type', $type)
            ->delete();

        $code = VerificationCode::create([
            'user_id' => $user->id,
            'code' => $c,
            'channel' => $channel,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($channel === 'email') {
            $this->sendVerifyEmailMessage($user, $code);
        } elseif ($channel === 'phone') {
            if ($type === 'verify') {
                $this->sendWhatsappCode($user, $code->code);
            } elseif ($type === 'reset_password') {
                $this->sendResetPasswordWhatsappCode($user, $code->code);
            }
        }
    }

    public function sendVerifyEmailMessage($user, $code)
    {
        Mail::to($user->email)->send(new VerificationCodeEmail($user, $code));

    }

    public function sendWhatsappCode($user, $code)
    {
        $response = Http::asForm()->post('https://api.ultramsg.com/instance118522/messages/chat', [
            'token' => '1fat1jiymupty7zc',
            'to' => '+963' . substr($user->phone, 1, 9),
            'body' => "👋 Hello {$user->first_name} {$user->last_name},\n
             \n🔐 Your verification code is: *{$code}*\n
             \nPlease enter this code to complete your login.\n
             \n✅ Thank you for using our service! 🚀"

        ]);

        if ($response->successful()) {
            return $response->json(); // أو $response->body() حسب يلي بدك ياه
        } else {
            return response()->json([
                'error' => 'Failed to send message',
                'status' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        }
    }

    public function sendResetPasswordWhatsappCode($user, $code)
    {
        $response = Http::asForm()->post('https://api.ultramsg.com/instance118522/messages/chat', [
            'token' => '1fat1jiymupty7zc',
            'to' => '+963' . substr($user->phone, 1, 9),
            'body' => "👋 Hello {$user->first_name} {$user->last_name},\n
             \n🔐 Your reset-password code is: *{$code}*\n
             \nPlease enter this code to complete your login.\n
             \n✅ Thank you for using our service! 🚀"

        ]);

        if ($response->successful()) {
            return $response->json(); // أو $response->body() حسب يلي بدك ياه
        } else {
            return response()->json([
                'error' => 'Failed to send message',
                'status' => $response->status(),
                'message' => $response->body()
            ], $response->status());
        }
    }



    //verification function we compained phone and number register ande reset password
    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone'   => 'sometimes|exists:users,phone',
            'email'   => 'sometimes|exists:users,email',
            'type'    => 'required|in:verify,reset_password',
            'channel' => 'required|in:phone,email',
            'code'    => 'required|min:6|max:6',
        ]);

        try {
            $user = User::when($request->channel === 'phone', fn($q) => $q->where('phone', $request->phone))
                ->when($request->channel === 'email', fn($q) => $q->where('email', $request->email))
                ->firstOrFail();

            $verification = VerificationCode::where('user_id', $user->id)
                ->where('channel', $request->channel)
                ->where('type', $request->type)
                ->where('code', $request->code)
                ->first();

            if (!$verification) {
                return response()->json(['message' => 'Invalid code.'], 422);
            }

            if ($request->type === 'verify') {
                if ($request->channel === 'phone') {
                    $user->update(['phone_verified_at' => now()]);
                } elseif ($request->channel === 'email') {
                    $user->update(['email_verified_at' => now()]);
                }

                $verification->delete();

                return response()->json(['message' => 'Verification successful.']);
            }

            elseif ($request->type === 'reset_password') {
                $token = Str::random(60);

$user->update([
    'reset_password_token' => $token,
    'reset_password_expires_at' => now()->addMinutes(10),
]);
                $verification->delete();

                return response()->json([
                    'message' => 'Code verified. Use the token to reset your password.',
                    'reset_token' => $token
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }




}
