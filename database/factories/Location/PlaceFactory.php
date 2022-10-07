<?php

namespace Database\Factories\Location;

use App\Models\Location\City;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location\Place>
 */
class PlaceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
   
        return [
            'company_id' => Company::factory(),
            'city_id' => City::factory(),
            'name' => fake()->city(),
            'slug' => fake()->slug(1),
            'description' => fake()->text(100),
            'address' => fake()->address(),
            'cooordinates' => json_encode([
                'latitude' => fake()->latitude(),
                'longitude' => fake()->longitude(),
            ]),
            'info' => fake()->randomElements(),
            
        ];
    }
}
