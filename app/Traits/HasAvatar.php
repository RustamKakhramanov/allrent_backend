<?php

namespace App\Traits;

use Spatie\MediaLibrary\InteractsWithMedia;

trait HasAvatar
{
    use InteractsWithMedia;

    public function getAvatarAttribute()
    {
        $this->getFirstMediaUrl('avatar');
    }

    public function setAvatarAttribute($value)
    {
        $this->addMedia($value)->toMediaCollection('avatar');
    }
}
