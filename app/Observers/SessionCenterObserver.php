<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Models\Doctor_examin;
use App\Models\Relief;
use App\Models\SessionCenter;
use App\Models\Waiting;

class SessionCenterObserver
{
    /**
     * Handle the SessionCenter "created" event.
     */
    public function created(SessionCenter $sessionCenter): void
    {
        //


        $doctor_id =$sessionCenter->sessionable->doctor_id;
        $de=Doctor_examin::where('doctor_id', $doctor_id)->first();
        $sessionCenter->doctor_examination=$de->price;
        $sessionCenter->doctor_examination_discount=$de->discount_rate;
        $sessionCenter->save();



    }

    /**
     * Handle the SessionCenter "updated" event.
     */
    public function updated(SessionCenter $sessionCenter): void
    {
        //
    }

    /**
     * Handle the SessionCenter "deleted" event.
     */
    public function deleted(SessionCenter $sessionCenter): void
    {
        //
    }

    /**
     * Handle the SessionCenter "restored" event.
     */
    public function restored(SessionCenter $sessionCenter): void
    {
        //
    }

    /**
     * Handle the SessionCenter "force deleted" event.
     */
    public function forceDeleted(SessionCenter $sessionCenter): void
    {
        //
    }
}
