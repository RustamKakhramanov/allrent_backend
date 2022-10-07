<?php

namespace App\Repositories\Location;

use Illuminate\Support\Carbon;
use App\Enums\ScheduleTypeEnum;
use App\Models\Location\Place;
use App\Models\Record\Rent;
use App\Repositories\Repository;
use Illuminate\Support\Collection;

class PlaceRepository extends Repository
{

    public function init($place)
    {
        return $place;
    }

    public static function getCompletedSchedule($place, $period)
    {
        if (is_array($period)) {
            [$start, $end] = $period;
        }

        $schedule_query =  $place->schedules()
            ->orderByRaw(
                $place->prepareOrderByRaw(
                    ScheduleTypeEnum::getCasesByPriority(),
                    'type'
                )
            )
            ->where(
                'type',
                ScheduleTypeEnum::Day()
            );

        if (is_array($period)) {
            [$start, $end] = $period;
            $schedule_query = $schedule_query->whereBetween('date', [cparse($start), cparse($end)]);
        } else {
            $schedule_query = $schedule_query->whereDay('date', cparse($period));
        }

        $schedule =   $schedule_query
            ->orWhere('type',  ScheduleTypeEnum::Default())
            ->first();

        if (!$schedule && !$schedule->schedules) {
            return collect();
        }


        $rents = $place->rents()
            ->whereDay('scheduled_at', cparse($period))
            ->whereDay('scheduled_end_at', cparse($period))
            ->get()
            ->map(
                fn ($item) => btime_intervals($item->scheduled_at, $item->scheduled_end_at, 'H:i', true)
            )->collapse();

        return  collect($schedule->schedule)->map(fn ($item) => [$item => $rents->contains(date('H:i', $item))]);
    }

    public static function getFreeSchedule($place, $period = null)
    {
        return (new self)->getCompletedSchedule($place, $period ?: now())->filter(fn ($item) => array_values($item)[1] ?? false);
    }

    public static function getPlacesWithFreeSchedules($date)
    {
        return static::get()->map(function ($item) use ($date) {
            $item->schedule = static::getFreeSchedule($item, $date);
            return $item;
        });
    }
}
