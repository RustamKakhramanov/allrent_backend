<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'icon',
        'name',
        'value',
    ];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class, PlaceHasAbility::class);
    }
}
