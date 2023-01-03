<?php

namespace App\Transformers;

use App\DTOs\RatingDTO;
use App\Transformers\ReviewTransformer;
use League\Fractal\TransformerAbstract;

class RatingTransformer extends TransformerAbstract
{

    protected array $defaultIncludes = [
        'reviews'
    ];

    public function transform(RatingDTO $rating)
    {
        return [
            'rating' => $rating->rating,
            'reviews_count' => $rating->reviews_count,
        ];
    }

    public function includeReviews(RatingDTO $rating)
    {
        return $this->collection($rating->reviews, new ReviewTransformer);
    }
}
