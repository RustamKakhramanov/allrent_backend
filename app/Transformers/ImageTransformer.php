<?php

namespace App\Transformers;

use App\DTOs\ImageDto;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'user'
    ];
    
    public function transform(ImageDto $image){
        return [
            'url' => $image->url,
            'description' => $image->description
        ];
    }
}
