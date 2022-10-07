<?php

namespace App\Observers;

use App\Models\Record\Schedule;
use Illuminate\Support\Facades\Auth;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function creating(Schedule $schedule)
    {
        if (!$schedule->user_id) {
            $schedule->user_id = Auth::user()->id ??  null;
        }

        if ($company = request()->route('company')) {
            $schedule->company_id = $company->id;
        }
    }

    /**
     * Handle the Schedule "created" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function created(Schedule $schedule)
    {
    }

    /**
     * Handle the Schedule "updated" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function updated(Schedule $schedule)
    {
        //
    }

    /**
     * Handle the Schedule "deleted" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function deleted(Schedule $schedule)
    {
        //
    }

    /**
     * Handle the Schedule "restored" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function restored(Schedule $schedule)
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     *
     * @param  \App\Models\Record\Schedule  $schedule
     * @return void
     */
    public function forceDeleted(Schedule $schedule)
    {
        //
    }
}
