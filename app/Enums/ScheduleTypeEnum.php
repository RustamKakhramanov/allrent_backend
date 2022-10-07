<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum ScheduleTypeEnum: string
{
    use FullEnum;

    case Default = 'default';
    case Period  = 'period';
    case Day =  'day';
    case Month =  'month';
    case Week =  'week';
    case WeekDay = 'week_day';
    case MonthDay = 'month_day';


    public  function getRules()
    {
        return match ($this) {
            static::Default => [],
            static::Period =>  [],
            static::Month => [],
            static::Week => [],
            static::Day =>  [],
            static::WeekDay =>  [],
            static::MonthDay =>  [],
        };
    }

    public static function getCasesByPriority()
    {
        return [
            static::Day(),
            static::WeekDay(),
            static::MonthDay(),
            static::Week(),
            static::Month(),
            static::Period(),
            static::Default(),
        ];
    }
}
