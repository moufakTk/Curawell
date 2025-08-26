<?php

namespace App\Observers;

use App\Enums\Sessions\SessionDoctorStatus;
use App\Enums\WorkStatus\PeriodStatus;
use App\Models\DoctorSession;
use App\Models\WorkEmployee;

class UpdateStstusDayDoctor
{
    /**
     * Handle the WorkEmployee "created" event.
     */
    public function created(WorkEmployee $workEmployee): void
    {
        //
    }

    /**
     * Handle the WorkEmployee "updated" event.
     */
    public function updated(WorkEmployee $workEmployee): void
    {
        //
        $doctor_session=$workEmployee->doctor_sessions;

        if($workEmployee->status ==PeriodStatus::FORBIDDEN){
            if($doctor_session->isNotEmpty()){
                $workEmployee->doctor_sessions->each(function ($doctorSession)  {
                    $doctorSession->update(['status' => SessionDoctorStatus::TurnOff]);
                });
            }

        }

    }

    /**
     * Handle the WorkEmployee "deleted" event.
     */
    public function deleted(WorkEmployee $workEmployee): void
    {
        //
    }

    /**
     * Handle the WorkEmployee "restored" event.
     */
    public function restored(WorkEmployee $workEmployee): void
    {
        //
    }

    /**
     * Handle the WorkEmployee "force deleted" event.
     */
    public function forceDeleted(WorkEmployee $workEmployee): void
    {
        //
    }
}
