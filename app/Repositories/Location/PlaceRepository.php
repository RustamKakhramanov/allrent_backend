<?php

namespace App\Repositories\Location;

use App\Enums\ScheduleTypeEnum;
use App\Repositories\Repository;
use App\DTOs\CompletedSheduleDTO;
use App\Models\Location\Place;
use App\Models\Record\Schedule;
use Illuminate\Database\Eloquent\Builder;

class PlaceRepository extends Repository
{

    public function init(Builder $place)
    {
        return $place;
    }

    public static function getCompletedSchedule(Place $place, $period, $free = false)
    {
        if (is_array($period)) {
            [$start, $end] = $period;
        } else {
            $period = cparse($period);
        }

        // $schedule_query =  $place->schedules()
        $schedule_query = Schedule::query()
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
            $schedule_query = $schedule_query->whereDay('date', $period);
        }
        // dd($place->schedules()->toSql());
        $schedule =   $schedule_query
            ->orWhere(
                fn ($i) =>
                $i->where('type',  ScheduleTypeEnum::Default())
                    ->where('schedulable_id', $place->id)

            )
            ->with('price')
            ->first();

        if (!$schedule && !$schedule->schedules) {
            return collect();
        }

        $rents = $place->rents()
            ->whereDay('scheduled_at', $period)
            ->whereDay('scheduled_end_at', $period)
            ->get()
            ->map(
                fn ($item) => btime_intervals($item->scheduled_at, $item->scheduled_end_at, 'H:i', true)
            )->collapse();

        $completed = collect($schedule->schedule)
            ->map(
                fn ($item) =>
                CompletedSheduleDTO::make(
                    ['time' => cparse($item), 'active' => (bool) $rents->contains($item) || cparse($period)->setHours($item)->lessThanOrEqualTo(now()->addHours(+6))] // окстыль со временем, но ограничение чтобы не выбрали раньше чем сейчас + 1ч
                )->setActualyDate($period)
            );

        if ($free) {
            $completed =  $completed->filter(fn (CompletedSheduleDTO $item) => !$item->active);
        }

        $schedule->schedule = $completed->values()
            ->map(fn (CompletedSheduleDTO $item) => ['time' => $item->time->addHours(-5)->format('Y-m-d\TH:i:s.uP'), 'active' => $item->active]) //временный костыль, так как не понятна универсальность подсчета времени
            ->toArray();

        return $schedule;
    }

    public static function getFreeSchedule($place, $period = null)
    {
        return (new self)->getCompletedSchedule($place, $period ?: now(), true);
    }

    public static function getPlacesWithFreeSchedules($date)
    {
        return static::get()->map(function ($item) use ($date) {
            $item->schedule = static::getFreeSchedule($item, $date);
            return $item;
        });
    }
}
