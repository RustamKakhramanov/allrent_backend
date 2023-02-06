<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Review;
use App\Events\PlaceRent;
use App\Events\PlaceRented;
use App\Models\Record\Rent;
use App\Models\Record\Price;
use App\Models\Record\Schedule;
use App\Observers\RentObserver;
use App\Observers\UserObserver;
use App\Observers\PriceObserver;
use App\Observers\ReviewObserver;
use App\Observers\ScheduleObserver;
use App\Listeners\PlaceRentListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Listeners\GiveInitialPermissions;
use App\Listeners\PlaceOwnersNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            GiveInitialPermissions::class
        ],
        // PlaceRented::class => [
        //     PlaceRentListener::class,
        // ],
    ];

    protected $subscribe = [
        PlaceRentListener::class,
    ];

    protected $observers = [
        User::class => [UserObserver::class],
        Schedule::class => [ScheduleObserver::class],
        Rent::class => [RentObserver::class],
        Price::class => [PriceObserver::class],
        Review::class => [ReviewObserver::class],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
