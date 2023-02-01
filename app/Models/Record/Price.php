<?php

namespace App\Models\Record;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'currency',
        'value',
        'type',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    use HasFactory;
}
