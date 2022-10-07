<?php

namespace App\Observers;

use App\Models\Record\Rent;

class RentObserver
{
    public function creating(Rent $rent){
        $rent->user_id = user()->id;
    }

    /**
     * Handle the Rent "created" event.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return void
     */
    public function created(Rent $rent)
    {
        //
    }

    /**
     * Handle the Rent "updated" event.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return void
     */
    public function updated(Rent $rent)
    {
        //
    }

    /**
     * Handle the Rent "deleted" event.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return void
     */
    public function deleted(Rent $rent)
    {
        //
    }

    /**
     * Handle the Rent "restored" event.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return void
     */
    public function restored(Rent $rent)
    {
        //
    }

    /**
     * Handle the Rent "force deleted" event.
     *
     * @param  \App\Models\Record\Rent  $rent
     * @return void
     */
    public function forceDeleted(Rent $rent)
    {
        //
    }
}
