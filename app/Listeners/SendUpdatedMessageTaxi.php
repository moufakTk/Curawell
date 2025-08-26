<?php

namespace App\Listeners;

use App\Events\UpdateTimeTaxiOrder;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUpdatedMessageTaxi
{
    /**
     * Create the event listener.
     */

    protected $verificationService;
    public function __construct(VerificationService $verificationService)
    {
        //
        $this->verificationService = $verificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(UpdateTimeTaxiOrder $event): void
    {
        //

        $message = "نود إعلامكم بأنه تم تعديل موعد توصيل الطلب الخاص بالعميل 👤 *{$event->user->getFullNameAttribute()}* من التاريخ: *{$event->timeOldOrder}* إلى التاريخ: *{$event->timeNew}*. 📌 يرجى التواصل مع العميل للتأكيد، وشكرًا لتعاونكم.";
        $this->verificationService->whatsappMessage('0992721424', $message);

    }
}
