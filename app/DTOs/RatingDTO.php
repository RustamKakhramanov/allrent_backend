<?php

namespace App\DTOs;

use App\Models\Specialist\SpecialistProfile;
use App\Models\User;
use stdClass;

class RatingDTO extends DTO
{
    protected string $rating;
    protected int $reviews_count;
    protected ?array $reviews;

    public static function mock()
    {
        $reviews_count = rand(1, 14);

        $data = [
            'rating' => 9.3,
            'reviews_count' =>$reviews_count
        ];

        for ($i = 1; $i <= $reviews_count; $i++) {
            $data['reviews'][] = static::mockReview();
        }


        return static::make($data);
    }

    public static function mockReview(){

        $review = new stdClass;
        $review->id = rand(0, 14);
        $review->reviewer = SpecialistProfile::inRandomOrder()->first();
        $review->text = fake()->text();
        $review->images = ImageDTO::mock(false);

        return $review;
    }
}
