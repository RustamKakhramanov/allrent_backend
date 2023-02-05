<?php

namespace App\Transformers;

use App\DTOs\ImageDTO;
use App\DTOs\RatingDTO;
use App\Models\Location\Place;
use League\Fractal\TransformerAbstract;
use stdClass;

class PlaceTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        'reviews_count', 'price'
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */

    protected array $availableIncludes = [
        'companies',
        'company',
        'city',
        'rents',
        'schedules',
        'completed_today_schedule',
        'free_today_schedule',
        'images',
        'reviews',
        'abilities'
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
            'coordinates' => is_string($place->coordinates) ? json_decode($place->coordinates) : $place->coordinates,
            'rating' => $place->rating ?? 4.4,
            'info' => $place->info
        ];
    }


    public function includePrice(Place $place)
    {
        return $place->price ? $this->item(
            $place->price,
            new PriceTransformer
        ) : $this->null();
    }


    public function includeImages(Place $place)
    {
        return $this->collection(
            ImageDTO::mockCollection(true),
            new ImageTransformer
        );
    }


    public function includeAbilities(Place $place)
    {
        $ab = [];

        for ($i = 1; $i <= 20; $i++) {
            $ab[] = (object) [
                'id' => $i, 
                'name' => 'Petfriendly', 
                'icon' => fake()->imageUrl(30,30), 
                'value' => 'У нас можно приходить с питомцами, есть посуда и корм ' .$i
            ];
        }

        return $this->collection(
            // $place->abilities,
            $ab,
            new AbilitiesTransformer
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
        return $place->free_today_schedule ? $this->item($place->free_today_schedule, new ScheduleTransformer) : $this->null();
    }

    public function includeRents(Place $place)
    {
        return $place->rents ? $this->collection($place->rents, new RentTransformer()) : $this->primitive([]);
    }


    public function includeReviews(Place $place)
    {
        return $this->collection($place->reviews, new ReviewTransformer);
    }

    public function includeReviewsCount(Place $place)
    {
        return $this->primitive(collect(RatingDTO::mockReviews())->count());
    }
}
