<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

class CompletedSheduleDTO extends DTO
{
    public Carbon $time;
    public $active;

    public function setActualyDate(Carbon $period)
    {
        $this->time = Carbon::parse($period)
            ->setHours($this->time->get('hour'))
            ->setMinute($this->time->get('minute'));

        return $this;
    }
}
