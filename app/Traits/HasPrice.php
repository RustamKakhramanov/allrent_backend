<?php

namespace App\Traits;

use App\Enums\PriceTypeEnum;
use App\Models\Record\Price;

trait HasPrice
{
    public function prices()
    {
        return $this->allPrices()
            ->where('start_date', '>=', now())
            ->whereNull('end_date')
            ->orWhere(function ($query) {
                $query
                    ->where('start_date', '>=', now())
                    ->where('end_date', '<', now());
            });
    }
    
    public function allPrices()
    {
        return $this->morphMany(Price::class, 'has_price');
    }

    public function price()
    {
        return $this->morphOne(Price::class, 'has_price')
            ->whereType(PriceTypeEnum::PerHour)
            ->where('has_price_id', $this->id)
            ->where('start_date', '<=', now())
            ->whereNull('end_date')
            ->orWhere(function ($query) {
                $query
                ->where('has_price_id', $this->id)
                    ->whereType(PriceTypeEnum::PerHour)
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>', now());
            });
    }
}
