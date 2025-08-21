<?php

namespace App\Observers;

use App\Models\SkiagraphOrder;

class SkiagraphOrderObserver
{
    /**
     * Handle the SkiagraphOrder "created" event.
     */
    public function created(SkiagraphOrder $skiagraphOrder): void
    {
        //
        $skiagraphOrder->bill_num ='#002_'.$skiagraphOrder->id;
        $skiagraphOrder->save();
    }

    /**
     * Handle the SkiagraphOrder "updated" event.
     */
    public function updated(SkiagraphOrder $skiagraphOrder): void
    {
        //
    }

    /**
     * Handle the SkiagraphOrder "deleted" event.
     */
    public function deleted(SkiagraphOrder $skiagraphOrder): void
    {
        //
    }

    /**
     * Handle the SkiagraphOrder "restored" event.
     */
    public function restored(SkiagraphOrder $skiagraphOrder): void
    {
        //
    }

    /**
     * Handle the SkiagraphOrder "force deleted" event.
     */
    public function forceDeleted(SkiagraphOrder $skiagraphOrder): void
    {
        //
    }
}
