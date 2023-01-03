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
            'text' => $review->text,
        ];
    }

    public function includeReviewer($review)
    {
        return $this->primitive([
            'profile_id' => $review->reviewer->id,
            'user_id' => $review->reviewer->user_id,
            'name' => $review->reviewer->user->name,
            'specialty' => $review->reviewer->speciality,
        ]);
    }


    public function includeImages()
    {
        return $this->collection(
            ImageDTO::mock(false),
            new ImageTransformer
        );
    }
}
