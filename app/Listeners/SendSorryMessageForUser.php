<?php

namespace App\Listeners;

use App\Events\SendSorryMessage;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendSorryMessageForUser
{
    /**
     * Create the event listener.
     */

    public $verificationService;
    public function __construct(VerificationService $verificationService)
    {
        //
        $this->verificationService = $verificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(SendSorryMessage $event): void
    {
        //
        $message = "السيد/ة 👤 *{$event->user->getFullNameAttribute()}*
نقدم اعتذارنا عن الإزعاج الناتج عن إلغاء موعدكم بسبب ظروف طارئة خارجة عن إرادتنا.
نود إعلامكم بأنكم ستحصلون على نقاطكم في حال كنتم قد استخدمتم خدماتنا التي تكسب النقاط.
أما إذا لم تستخدموا موقعنا بعد، فننصحكم باستخدامه للاستفادة من خدماتنا بشكل أفضل.
نشكر تفهمكم وتعاونكم الدائم معنا. 🙏";

        $this->verificationService->whatsappMessage($event->phone_number, $message);

    }
}
