<?php

namespace App\Observers;

use App\Models\UserReplacement;

class UserReplacementObserver
{

    public function creating(UserReplacement $userReplacement): void
    {



    }



    /**
     * Handle the UserReplacement "created" event.
     */
    public function created(UserReplacement $userReplacement): void
    {
        //
        $userReplacement->replacement_time = $userReplacement->created_at;
        $userReplacement->save();

    }

    /**
     * Handle the UserReplacement "updated" event.
     */
    public function updated(UserReplacement $userReplacement): void
    {
        //
    }

    /**
     * Handle the UserReplacement "deleted" event.
     */
    public function deleted(UserReplacement $userReplacement): void
    {
        //
    }

    /**
     * Handle the UserReplacement "restored" event.
     */
    public function restored(UserReplacement $userReplacement): void
    {
        //
    }

    /**
     * Handle the UserReplacement "force deleted" event.
     */
    public function forceDeleted(UserReplacement $userReplacement): void
    {
        //
    }
}
