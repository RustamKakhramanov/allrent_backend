<?php

namespace Database\Factories\Record;

use App\Models\User;
use App\Enums\TimeEnum;
use App\Models\Location\Place;
use Illuminate\Support\Carbon;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Record\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $schedule = TimeEnum::getAllIntervals();
        $iterations =  fake()->numberBetween(1, $schedule);
        $factory_schedule = [];
        $iteration = 1;

        foreach ($schedule as $times) {
            $factory_schedule = array_merge($times, $factory_schedule);
            $iteration++;
            if ($iteration == $iterations) break;
        }

        return [
            'user_id' => User::factory(),
            'company_id' => Company::factory(),
            'schedule' => $factory_schedule,
            'date' => fake()->dateTimeBetween('now', Carbon::now()->addMonth()),
        ];
    }
}
