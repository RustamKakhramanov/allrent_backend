<?php

namespace App\Transformers;

use App\Enums\PriceTypeEnum;
use App\Models\Record\Price;
use League\Fractal\TransformerAbstract;

class PriceTransformer extends TransformerAbstract
{
    public function transform(Price $price)
    {
        return [
            'name' => PriceTypeEnum::tryFrom($price->type)->getName(),
            'type' => $price->type,
            'currency' => $price->currency,
            'value' => $price->value,
            'start_date' => $price->start_date,
            'end_date' => $price->end_date,
        ];
    }
}
