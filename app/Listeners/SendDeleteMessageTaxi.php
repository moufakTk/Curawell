<?php

namespace App\Listeners;

use App\Events\DeleteOrderTaxi;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDeleteMessageTaxi
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
    public function handle(DeleteOrderTaxi $event): void
    {
        //

        $message =$message = "نود إعلامكم بأنه تم إلغاء طلب توصيل العميل 👤 *{$event->user->getFullNameAttribute()}* الواقع بتاريخ: *{$event->time}*. 📌 يرجى التواصل مع العميل للتأكيد، وشكرًا لتعاونكم.";
        $this->verificationService->whatsappMessage('0992721424', $message);

    }
}
