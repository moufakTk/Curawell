<?php

namespace App\Observers;

use App\Models\AppointmentHomeCare;

class HomeCareObserver
{
    /**
     * Handle the AppointmentHomeCare "created" event.
     */
    public function created(AppointmentHomeCare $appointmentHomeCare): void
    {
        //
        $appointmentHomeCare->bill_num='#004_'.$appointmentHomeCare->id;
        $appointmentHomeCare->save();
    }

    /**
     * Handle the AppointmentHomeCare "updated" event.
     */
    public function updated(AppointmentHomeCare $appointmentHomeCare): void
    {
        //
    }

    /**
     * Handle the AppointmentHomeCare "deleted" event.
     */
    public function deleted(AppointmentHomeCare $appointmentHomeCare): void
    {
        //
    }

    /**
     * Handle the AppointmentHomeCare "restored" event.
     */
    public function restored(AppointmentHomeCare $appointmentHomeCare): void
    {
        //
    }

    /**
     * Handle the AppointmentHomeCare "force deleted" event.
     */
    public function forceDeleted(AppointmentHomeCare $appointmentHomeCare): void
    {
        //
    }
}
