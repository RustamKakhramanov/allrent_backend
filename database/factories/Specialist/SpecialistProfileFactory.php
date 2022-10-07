<?php

namespace Database\Factories\Specialist;

use App\Models\Company\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SpecialistProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), 'company_id' => Company::factory(), 'info' => fake()->randomElements()
        ];
    }

    public function freelancers()
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
        ]);
    }
}
