<?php

namespace App\Observers;

use App\Models\AnalyzeOrder;

class AnalyzeOrderObserver
{
    /**
     * Handle the AnalyzeOrder "created" event.
     */
    public function created(AnalyzeOrder $analyzeOrder): void
    {
        //
        $analyzeOrder->bill_num="#003_".$analyzeOrder->id;
        $analyzeOrder->save();
    }

    /**
     * Handle the AnalyzeOrder "updated" event.
     */
    public function updated(AnalyzeOrder $analyzeOrder): void
    {
        //
    }

    /**
     * Handle the AnalyzeOrder "deleted" event.
     */
    public function deleted(AnalyzeOrder $analyzeOrder): void
    {
        //
    }

    /**
     * Handle the AnalyzeOrder "restored" event.
     */
    public function restored(AnalyzeOrder $analyzeOrder): void
    {
        //
    }

    /**
     * Handle the AnalyzeOrder "force deleted" event.
     */
    public function forceDeleted(AnalyzeOrder $analyzeOrder): void
    {
        //
    }
}
