<?php

namespace App\Traits;

use Spatie\Image\Manipulations;
use App\Services\Media\ImageCopyright;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait ImageCopyrightTrait
{
    public function saveImage(ImageCopyright $imageCopyright, string $collectionName)
    {
        $this->addMedia($imageCopyright->getFile())
            ->usingFileName($imageCopyright->getNextFileName())
            ->withCustomProperties($imageCopyright->getCustomProperties())
            ->toMediaCollection($collectionName);
    }

    public function saveImages(array $images, string $collectionName)
    {
        array_walk(
            $images, fn($image) => $this->saveImage(ImageCopyright::createFromArray($image), $collectionName)
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}