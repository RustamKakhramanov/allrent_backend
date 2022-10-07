<?php

namespace App\Traits\Eloquent;

use App\Traits\Eloquent\TimeQueries;

/**
 * @property Collection|Rent[]  $rents
 */
trait Rentable
{
    use TimeQueries;
    
    protected $scopeTimeColumn = 'schedules.date';
    
    public function rents()
    {
        return $this->morphMany(Schedule::class, 'rentable');
    }
}
