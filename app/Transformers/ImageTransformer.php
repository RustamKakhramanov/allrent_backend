<?php

namespace App\Transformers;

use App\DTOs\ImageDTO;
use League\Fractal\TransformerAbstract;
use PDO;

class ImageTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'user'
    ];
    
    public function transform( $image){
        if($image instanceof ImageDTO){
            return [
                'url' => $image->url,
                'description' => $image->description
            ];
        }
        
        if($image instanceof \Spatie\MediaLibrary\MediaCollections\Models\Media){
            return [
                'url' => $image->getFullUrl(),
                'preview_url' => $image->getFullUrl('preview'),
                'icon_url' => $image->getFullUrl('icon'),
                'description' => $image->description
            ];
        }
       
    }
}
