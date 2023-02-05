<?php

namespace App\Traits;

use Spatie\Image\Manipulations;
use App\Services\Media\ImageCopyright;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasAvatar
{
    use InteractsWithMedia {
        InteractsWithMedia::registerMediaConversions as parentRegisterMediaConversions;
    }


    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->getFirstMedia('avatar'),
        );
    }

    public function setAvatar($image)
    {
        $this->addMedia(
            $image instanceof ImageCopyright ? $image->getFile() : $image
        )
        ->usingFileName(uniqid())
        ->toMediaCollection('avatar');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('icon')
            ->fit(Manipulations::FIT_CROP, 50, 50)
            ->nonQueued();
    }
}
