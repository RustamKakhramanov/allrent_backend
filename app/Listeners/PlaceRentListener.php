<?php

namespace App\Listeners;

use App\Events\PlaceRented;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class PlaceRentListener implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function handleRent(PlaceRented $event){
        echo(11);
    }


    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            PlaceRented::class,
            [PlaceRentListener::class, 'handleRent']
        );
    }
}
