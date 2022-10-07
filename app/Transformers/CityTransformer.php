<?php

namespace App\Transformers;

use App\Models\Location\City;
use League\Fractal\TransformerAbstract;

/**
 * @OA\Schema(
 *  schema="City",
 *  title="City model",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="country_id", type="integer"),
 *  @OA\Property(property="country", type="string"),
 *  @OA\Property(property="purchase_id", type="string"),
 *  @OA\Property(property="zoom", type="integer"),
 *  @OA\Property(property="radius", type="number"),
 *  @OA\Property(property="latitude", type="number"),
 *  @OA\Property(property="longitude", type="number"),
 *  @OA\Property(property="ne_latitude", type="number"),
 *  @OA\Property(property="ne_longitude", type="number"),
 *  @OA\Property(property="sw_latitude", type="number"),
 *  @OA\Property(property="sw_longitude", type="number"),
 * )
 *
 * Class CityTransformer
 * @package App\Transformers
 */
class CityTransformer extends TransformerAbstract
{
    public function transform(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            // 'country_id' => $city->country_id,
            // 'country' => $city->country->name,
            // 'price' => $city->price,
            // 'latitude' => $city->latitude,
            // 'longitude' => $city->longitude,
            // 'radius' => $city->radius,
            // 'sw_latitude' => $city->sw_latitude,
            // 'sw_longitude' => $city->sw_longitude,
            // 'ne_latitude' => $city->ne_latitude,
            // 'ne_longitude' => $city->ne_longitude,
            // 'max_zoom_level' => $city->zoom,
            // 'purchase_id' => $city->purchase_id,
            // 'is_published' => $city->is_published,
        ];
    }
}
