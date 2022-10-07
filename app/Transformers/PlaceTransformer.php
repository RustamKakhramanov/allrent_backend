<?php

namespace App\Transformers;

use App\DTOs\ImageDto;
use App\Models\Location\Place;
use League\Fractal\TransformerAbstract;

class PlaceTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'city'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */

    protected array $availableIncludes = [
        'companies', 'company', 'rents', 'schedules', 'completed_today_schedule', 'free_today_schedule', 'images'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Place $place)
    {
        return [
            'id' => $place->id,
            'name' => $place->name,
            'slug' => $place->slug,
            'description' => $place->description,
            'address' => $place->address,
            'cooordinates' => $place->cooordinates,
            'info' => $place->info
        ];
    }

    public function includeImages()
    {
        $images = [
            ImageDto::make([
                'url' => url('/storage/images/first.jpg'),
                'description' => fake()->title()
            ]),
            ImageDto::make([
                'url' => fake()->imageUrl(),
                'description' => fake()->title()
            ]),
            ImageDto::make([
                'url' => fake()->imageUrl(),
                'description' => fake()->title()
            ]),
            ImageDto::make([
                'url' => fake()->imageUrl(),
                'description' => fake()->title()
            ]),
            ImageDto::make([
                'url' => fake()->imageUrl(),
                'description' => fake()->title()
            ]),
            ImageDto::make([
                'url' => fake()->imageUrl(),
                'description' => fake()->title()
            ]),
        ];

        return $this->collection(
            collect($images),
            new ImageTransformer
        );
    }

    public function includeCompany(Place $place)
    {
        return $place->company ? $this->item($place->company, new CompanyTransformer()) : $this->null();
    }

    public function includeCity(Place $place)
    {
        return $place->city ? $this->item($place->city, new CityTransformer()) : $this->null();
    }

    public function includeCompanies(Place $place)
    {
        return $place->companies ? $this->collection($place->companies, new CompanyTransformer) : $this->primitive([]);
    }

    public function includeSchedules(Place $place)
    {
        return $place->schedules ? $this->collection($place->schedules, new ScheduleTransformer()) : $this->primitive([]);
    }

    public function includeCompletedTodaySchedule(Place $place)
    {
        return $place->completed_today_schedule ? $this->primitive($place->completed_today_schedule) : $this->null();
    }

    public function includeFreeTodaySchedule(Place $place)
    {
        return $place->free_today_schedule ? $this->primitive($place->free_today_schedule) : $this->null();
    }

    public function includeRents(Place $place)
    {
        return $place->rents ? $this->collection($place->rents, new RentTransformer()) : $this->primitive([]);
    }
}
