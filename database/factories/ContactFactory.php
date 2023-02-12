<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
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
