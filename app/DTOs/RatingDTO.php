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
            $review->comment = fake()->text();
            $review->advantages = fake()->text();
            $review->disadvantages = fake()->text();
            $review->images = ImageDTO::mockCollection(false);
            $review->rating = rand(1,5);
            $review->created_at = now();
            $reviews[] = $review;
        }

        return $reviews;
    }
}
