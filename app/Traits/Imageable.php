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
    use InteractsWithMedia {
        InteractsWithMedia::registerMediaConversions as parentRegisterMediaConversions;
    }

    use HasAvatar {
        HasAvatar::registerMediaConversions as avatarRegisterMediaConversions;
    }


    public function saveImage($image, string $collectionName = 'default')
    {
        $file =   $image instanceof ImageCopyright ?
            $this->addMedia($image->getFile())
            ->withCustomProperties($image->getCustomProperties())
            :
            $this->addMediaFromUrl($image);

        $file

            ->usingFileName(
                $image instanceof ImageCopyright ?
                    uniqid() . '.' . $image->getFile()->getClientOriginalExtension()
                    :
                    uniqid() . '.' . $image->getClientOriginalExtension()
            )
            // ->withResponsiveImages()
            ->toMediaCollection($collectionName);
    }

    public function syncImages(array $images, string $collectionName = 'default')
    {
        $this->clearMediaCollection($collectionName);
        if (count($images)) {
            $this->saveImages($images, $collectionName);
        }
    }

    public function saveImages(array $images, string $collectionName = 'default')
    {
        array_walk(
            $images,
            fn ($image) =>
                $this->saveImage($image instanceof UploadedFile ? new ImageCopyright($image): $image, $collectionName)
        );
    }

    public function getImageAttribute()
    {
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

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP,  700, 450)
            ->nonQueued();

        $this
            ->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 50, 50)
            ->nonQueued();
    }
}
