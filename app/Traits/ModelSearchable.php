<?php

namespace App\Traits;

use App\Services\Http\BaseFilter;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|static::class filter(\App\Services\Http\BaseFilter $filter)
 */
trait ModelSearchable
{
    public function scopeFilter($query, BaseFilter $filter)
    {
        return $filter->apply($query);
    }
}
