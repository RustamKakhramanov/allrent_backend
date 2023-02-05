<?php

namespace App\Traits;

use Spatie\Image\Manipulations;
use Illuminate\Http\UploadedFile;
use App\Services\Media\ImageCopyright;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait Imageable
{
    use InteractsWithMedia;
    use HasAvatar;
    

    public function saveImage($image, string $collectionName = 'default')
    {
        $file =   $image instanceof ImageCopyright ?  $this->addMedia($image->getFile()) : $this->addMediaFromUrl($image);

        $file
            ->withCustomProperties($image->getCustomProperties())
            ->usingFileName(uniqid())
            // ->withResponsiveImages()
            ->toMediaCollection($collectionName);
    }

    public function saveImages(array $images, string $collectionName = 'default')
    {
        array_walk(
            $images,
            fn ($image) => $image instanceof UploadedFile ?
                $this->saveImage(new ImageCopyright($image), $collectionName) : $image
        );
    }

    public function getImageAttribute(){

    }
    public function getImages(string $collectionName = 'default', array|callable $filters = [])
    {
        return $this->getMedia($collectionName, $filters);
    }

    protected function images(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->getImages(),
        );
    }
}
