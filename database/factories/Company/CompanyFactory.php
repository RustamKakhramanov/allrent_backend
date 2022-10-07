<?php

namespace Database\Factories\Company;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->company(),
            'slug' => fake()->slug(1),
            'description' => fake()->text(),
            'info' => fake()->randomElements(),
        ];
    }
}
