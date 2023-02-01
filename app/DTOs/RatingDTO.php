<?php

namespace App\DTOs;

use App\Models\Specialist\SpecialistProfile;
use App\Models\User;
use stdClass;

class RatingDTO extends DTO
{
    protected string $rating;

    public static function mock()
    {
        $data = [
            'rating' => 4.7
        ];

        return static::make($data);
    }

    public static function mockReviews($count = 0)
    {
        $reviews_count = $count ?: rand(1, 14);
        $reviews = [];

        for ($i = 1; $i <= $reviews_count; $i++) {
            $review = new stdClass;
            $review->id = rand(0, 14);
            $review->reviewer = SpecialistProfile::inRandomOrder()->first();
            $review->text = fake()->text();
            $review->images = ImageDTO::mock(false);
            $reviews[] = $review;
        }

        return $reviews;
    }
}
