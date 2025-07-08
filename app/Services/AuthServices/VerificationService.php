<?php

namespace App\Services\AuthServices;

use App\Mail\VerificationCodeEmail;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationService
{

    public function sendCode($request)
    {

        $user = User::query();

        if ($request->filled('phone')) {
            $user->where('phone', $request->phone);
            $user = $user->first();

        }

        if ($request->filled('email')) {
            $user->orWhere('email', $request->email);
            $user = $user->first();

        }



        if (!$user) {
            throw new \Exception('User not found', 404);
        }

      $code  =  $this->sendVerificationCode($user, $request->channel, $request->type);

        return [
            'message' => __('messages.code_send_successfully'),
            'code' => $code
        ];

    }


    public function sendVerificationCode($user, $channel, $type)
    {
        $c = random_int(100000, 999999);

        VerificationCode::where('user_id', $user->id)
            ->when($type === 'verify', fn($q) => $q->where('type', $type)
                ->where('channel', $channel))
            ->when($type === 'reset_password', fn($q) => $q->where('type', $type))
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
        return $code->code;
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
    public function verifyCode($request)
    {



        $user = User::when($request->email, fn($q) => $q->where('email', $request->email))
            ->when($request->phone, fn($q) => $q->orWhere('phone', $request->phone))
            ->firstOrFail();


        if ($request->type === 'verify') {
            $verification = VerificationCode::where('user_id', $user->id)
                ->where('type', 'verify')
                ->where('channel', $request->channel)
                ->where('code', $request->code)
                ->first();
        } elseif ($request->type === 'reset_password') {
            $verification = VerificationCode::where('user_id', $user->id)
                ->where('type', 'reset_password')
                ->where('code', $request->code)
                ->first();
        }


        if (!$verification) {
                throw new \Exception('Invalid code.', 422);
            }

            if ($request->type === 'verify') {
                if ($request->channel === 'phone') {
                    $user->update(['phone_verified_at' => now()]);
                } elseif ($request->channel === 'email') {
                    $user->update(['email_verified_at' => now()]);
                }

                $verification->delete();

                return ['message' => 'Verification successful.'];
            }

            if ($request->type === 'reset_password') {
                $token = Str::random(60);

                $user->update([
                    'reset_password_token' => $token,
                    'reset_password_expires_at' => now()->addMinutes(10),
                ]);

                $verification->delete();

                return [
                    'message' => 'Code verified. Use the token to reset your password.',
                    'reset_token' => $token
                ];
            }

    }

}
