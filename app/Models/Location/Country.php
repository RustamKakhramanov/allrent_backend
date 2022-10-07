<?php

namespace App\Models\Location;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 */
class Country extends Model
{
    use HasFactory;
    use HasRelationships;


    public $timestamps = true;

    protected $fillable = ['name'];

    protected $with = ['cities', 'regions'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function cities()
    {
        return $this->hasManyDeep(City::class, [Region::class]);
    }
}
