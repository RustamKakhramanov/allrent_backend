<?php

namespace App\Traits\Eloquent;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder whereSlug(string $slug)
 */
trait Sluggable
{
    public static function findBySlug(string $slug){
        return static::whereSlug($slug)->first();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
