<?php

namespace App\Enums;

enum WeekDayEnum: string
{

    case Monday = 'monday';
    case Tuesday = 'tuesday';
    case Wednesday = 'wednesday';
    case Thursday = 'thursday';
    case Friday = 'friday';
    case Saturday = 'saturday';
    case Sunday = 'sunday';

    public function toSqlFormat()
    {
        return match ($this) {
            static::Monday => 0,
            static::Tuesday => 1,
            static::Wednesday => 2,
            static::Thursday => 3,
            static::Friday => 4,
            static::Saturday => 5,
            static::Sunday => 6,
        };
    }
}
