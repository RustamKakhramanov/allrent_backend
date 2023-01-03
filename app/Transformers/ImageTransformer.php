<?php

namespace App\Transformers;

use App\DTOs\ImageDTO;
use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'user'
    ];
    
    public function transform(ImageDTO $image){
        return [
            'url' => $image->url,
            'description' => $image->description
        ];
    }
}
