<?php

namespace App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;
use App\Enums\WeekDayEnum;


/**
 *  @method static whereWeekDay(WeekDayEnum $day, $column = 'date')
 */
trait TimeQueries
{
    public function scopeWhereWeekDay(Builder $builder, WeekDayEnum $day, $column = 'date')
    {
        $column = $this->scopeTimeColumn ?? $column;

        $builder->whereRaw('WEEKDAY(' . $column . ') = ' . $day->toSqlFormat());
    }
}
