<?php

namespace App\Transformers;

use App\Models\Location\Country;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Country $country
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id' => $country->id,
            'name' => $country->name,
        ];
    }
}
