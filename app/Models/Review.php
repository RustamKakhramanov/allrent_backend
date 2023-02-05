<?php

namespace App\Models;

use App\Traits\HasAvatar;
use App\Traits\Imageable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Models\Specialist\SpecialistProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Review extends Model  implements HasMedia
{
    use HasFactory;
    use Imageable;

    protected $fillable = [
        'comment',
        'advantages',
        'disadvantages',
        'rating'
    ];

    public function reviewer()
    {
        return $this->morphTo(__FUNCTION__, 'reviewer_type', 'reviewer_id');
    }

    public function reviewed()
    {
        return $this->morphTo(__FUNCTION__, 'reviewed_type', 'reviewed_id');
    }
}
