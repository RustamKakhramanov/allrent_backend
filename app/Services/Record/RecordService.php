<?php

namespace App\Services\Record;

use App\Models\Location\Place;
use App\Repositories\Location\PlaceRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RecordService
{
    public function __construct(protected PlaceRepository $placeRepository) {}

    public function handle(Place $place, array $requestData)
    {
        $date =  cparse($requestData['scheduled_at']);
        $schedule = $this->placeRepository->getCompletedSchedule($place, $date);

        $exists = collect($schedule->schedule)->contains(function ($item) use ($date) {
            return cparse($item['time'])->addHours(5)->eq($date);
        });

        if (!$exists) {
            throw new HttpException(400, 'Время отсуствует в графике');
        }

        $requestData['amount'] = $schedule->price->value;

        return [
            'rentable_id' => $place->id,
            'rentable_type' => $place::class,
            ...$requestData
        ];
    }
}
