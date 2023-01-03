<?php

namespace App\Models\Specialist;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $name
 * @property int $company_id
 * @property array $info
 * @property BelongsTo|SpecialistProfile[]  $profiles
 * @method static whereCompany(Company|int $company)
 */
class Speciality extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'info', 'is_main'
    ];

    protected $casts = ['info' => 'array'];

    public function profiles()
    {
        return $this->belongsTo(SpecialistProfile::class, 'profile_has_specialities');
    }
}
