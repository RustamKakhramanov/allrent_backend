<?php

namespace Database\Factories\Record;

use App\Enums\CurrencyEnum;
use App\Enums\PriceTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record\Rent>
 */
class PriceFactory extends Factory
{
    public function definition()
    {
        return [
            'start_date' => now(),
            'currency' => CurrencyEnum::KZT(),
            'value' => random_int(700, 3000),
            'type' => PriceTypeEnum::PerHour(),
        ];
    }
}
