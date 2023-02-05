<?php

namespace App\Transformers;

use App\DTOs\ImageDTO;
use League\Fractal\TransformerAbstract;

class ReviewTransformer extends TransformerAbstract
{

    protected array $defaultIncludes = [
        'reviewer', 'images'
    ];

    public function transform($review)
    {
        return [
            'id' => $review->id,
            'comment' => $review->comment,
            'advantages' => $review->disadvantages,
            'disadvantages' => $review->disadvantages,
            'rating' => $review->rating,
            'created_at' => $review->created_at,
        ];
    }

    public function includeReviewer($review)
    {
        $profile = $review->reviewer->profiles()->first();

        return $this->primitive([
            'profile_id' => $profile->id??null,
            'user_id' => $review->reviewer->id,
            'name' => $review->reviewer->name,
            'avatar' => null, //TODOO,
            'specialty' => $profile->speciality ?? null,
        ]);
    }


    public function includeImages($review)
    {
        return $this->collection(
            $review->images,
            new ImageTransformer
        );
    }
}
