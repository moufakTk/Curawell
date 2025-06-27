<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\Verification\CheckExpiredVerificationCodeJob;
use App\Jobs\Verification\SendVerificationCodeJob;
use App\Mail\VerificationCodeEmail;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class VerificationController extends Controller
{
    public function __construct(){}
    public function sendCode($user,$type){

        $c = random_int(100000, 999999);
      $code =  VerificationCode::create([
            'user_id' => $user->id,
            'code' => $c,
            'type' => $type,
            'expires_at' => now()->addMinutes(0.5)
        ]);
        if($type == 'email'){
//            $this->sendEmailMessage($user,$code->code);
//            CheckExpiredVerificationCodeJob::dispatch($code->id)->delay(now()->addMinutes(10));
        }elseif ($type == 'phone'){
//            $this->sendWhatsappCode($user,$code->code);
        }

    }
     public function sendEmailMessage($user,$code){
         Mail::to($user->email)->send(new VerificationCodeEmail($user, $code));

     }

     public function sendWhatsappCode($user,$code)
     {
         $response = Http::asForm()->post('https://api.ultramsg.com/instance118522/messages/chat', [
             'token' => '1fat1jiymupty7zc',
             'to' => '+963'.substr($user->phone, 1, 9),
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
}
