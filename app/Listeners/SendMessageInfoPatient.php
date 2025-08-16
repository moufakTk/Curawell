<?php

namespace App\Listeners;

use App\Events\WhatsAppInfoPatient;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageInfoPatient
{
    /**
     * Create the event listener.
     */
    public $verificationService;
    public function __construct(VerificationService $verificationService)
    {
        $this->verificationService = $verificationService;
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WhatsAppInfoPatient $event): void
    {
        $message = "Hello {$event->user->full_name},

Your account has been created. Here are your account details:

Phone Number: {$event->user->phone}
Address: {$event->user->address}
Gender: {$event->user->gender->label()}
National ID: {$event->user->patient->civil_id_number}
Password: {$event->password}

Please log in and change your password as soon as possible.";

        $this->verificationService->whatsappMessage($event->user->phone, $message);


    }
}
