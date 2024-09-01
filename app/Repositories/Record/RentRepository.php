<?php

namespace App\Repositories\Record;

use App\Repositories\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use App\Models\Record\Rent;

/**
 * @property Rent $model
 */
class RentRepository extends Repository
{
    public function init(Builder $rent)
    {
        return $rent;
    }

    public function hasAtDay(Carbon $date, int $userId, int $count = 0)
    {
        return $this
            ->query()
            ->where('user_id', $userId)
            ->whereDay('scheduled_at', $date->day)
            ->whereMonth('scheduled_at', $date->month)
            ->whereYear('scheduled_at', $date->year)
            ->count() > $count;
    }
}
