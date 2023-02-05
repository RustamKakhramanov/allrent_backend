<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Location\Place;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    public function definition()
    {
        return [
            'comment' => fake()->text(),
            'advantages' => fake()->text(),
            'disadvantages' => fake()->text(),
            'rating' => fake()->randomFloat(1, 1, 5),
        ];
    }
}
