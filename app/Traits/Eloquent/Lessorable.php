<?php

namespace App\Traits\Eloquent;

use App\Models\Record\Rent;
use App\Models\Record\Schedule;
use App\Traits\Eloquent\TimeQueries;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property Collection|Schedule[]  $schedules
 * @property Collection|Rent[]  $rents
 * @method Builder|Schedule[]  schedules()
 * @method Builder|Rent[]  rents()
 */
trait Lessorable
{
    use TimeQueries;
    
    protected $scopeTimeColumn = 'schedules.date';

    public function rents()
    {
        return $this->morphMany(Rent::class, 'rentable');
    }

    public function schedules()
    {
        return $this->morphMany(Schedule::class, 'schedulable');
    }
    
}
