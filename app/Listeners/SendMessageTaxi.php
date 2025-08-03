<?php

namespace App\Listeners;

use App\Events\WhatsAppTaxi;
use App\Services\Appointment\AppointmentService;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageTaxi
{

    protected $verificationService;
    /**
     * Create the event listener.
     */
    public function __construct(VerificationService $verificationService)
    {
        //
        $this->verificationService = $verificationService;
    }

    /**
     * Handle the event.
     */
    public function handle(WhatsAppTaxi $event): void
    {
        //
        $message = "مرحباً،
نُحيطكم علمًا بوجود طلب لتوصيل أحد المرضى إلى المركز، ونرجو منكم التواصل معه لتأكيد التفاصيل:

👤 *الاسم الكامل*: {$event->user->first_name} {$event->user->last_name}
📍 *الموقع*: {$event->order->address}
📞 *رقم الهاتف*: {$event->order->phone}
🗓 *التاريخ والوقت*: " . $event->order->date->format('Y-m-d h:i:s') . "

📌 يرجى التواصل في أقرب وقت ممكن لإتمام التنسيق اللازم.

🌟 مع جزيل الشكر والتقدير.";



        $this->verificationService->whatsappMessage('0992721424', $message);
    }
}
