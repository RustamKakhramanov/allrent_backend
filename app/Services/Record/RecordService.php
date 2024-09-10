<?php

namespace App\Services\Record;

use App\Models\Location\Place;
use App\Repositories\Location\PlaceRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Repositories\Record\RentRepository;
use App\Events\RecordCreated;
use App\Models\Record\Rent;

class RecordService
{
    const ALLOW_RENTS_COUNT = 2;

    public function __construct(protected PlaceRepository $placeRepository, protected RentRepository $rentRepository) {}

    public function handle(Place $place, array $requestData)
    {
        $date =  cparse($requestData['scheduled_at']);
        if ($this->rentRepository->hasAtDay($date, auth()->user()->id, static::ALLOW_RENTS_COUNT, $place->rents())) {
            throw new HttpException(400, 'У вас уже имеется более одной записи в этот день');
        }

        $schedule = $this->placeRepository->getCompletedSchedule($place, $date);

        $exists = collect($schedule->schedule)->contains(function ($item) use ($date) {
            return cparse($item['time'])->addHours(5)->eq($date);
        });

        if (!$exists) {
            throw new HttpException(400, 'Время отсуствует в графике');
        }

        $requestData['amount'] = $schedule->price->value;

        $data = [
            'user_id' => auth()->user()->id,
            'rentable_id' => $place->id,
            'rentable_type' => $place::class,
            ...$requestData
        ];

        $rent =  Rent::create($data);

        event(new RecordCreated($rent));

        return $rent;
    }
}
