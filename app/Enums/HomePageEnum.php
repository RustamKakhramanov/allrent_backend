<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum HomePageEnum: string
{
    use FullEnum;

    case Company = 'company';
    case Place = 'place';


    public function getDataIncludes()
    {
        return match ($this) {
            static::Company => [
                'company.logo', 'company.places.free_today_schedule', 'company.places.images'
            ],
            static::Place => [
                'place.free_today_schedule', 'place.images', 'place.contacts', 'place.company', 'place.abilities', 'place.reviews'
            ],
        };
    }
}
