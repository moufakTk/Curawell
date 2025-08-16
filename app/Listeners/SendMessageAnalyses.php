<?php

namespace App\Listeners;

use App\Events\WhatsAppAnalysesPatient;
use App\Services\AuthServices\VerificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessageAnalyses
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
    public function handle(WhatsAppAnalysesPatient $event): void
    {
        if($event->type===1){$message = "Hello {$event->user->full_name},

Your analysis results are now ready.
Please visit our center or log in to your account to view and download your report.

Order Number: {$event->analyseOrder->id}
Analysis Name: {$event->analyseOrder->name}
Status: {$event->analyseOrder->status->label()}

Thank you for choosing our services.";
        }
        elseif ($event->type===2){
            $message = "Hello {$event->user->full_name},

Your radiology image are now ready.
Please visit our center or log in to your account to view and download your report.

Order Number: {$event->analyseOrder->id}
radiology image Name: {$event->analyseOrder->skaigraph_small_service->name_en}
Status: {$event->analyseOrder->status->label()}

Thank you for choosing our services.";


        }


        $this->verificationService->whatsappMessage($event->user->phone, $message);


    }
}
