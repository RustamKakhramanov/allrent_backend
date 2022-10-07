<?php

namespace App\Models\Specialist;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $specialist_profile_id
 * @property int $speciality_id
 * @property bool $is_main
 * @property Builder|SpecialistProfile  $profile
 * @property Builder|Speciality  $speciality
 */
class ProfileHasSpeciality extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialist_profile_id',
        'speciality_id',
        'is_main'
    ];



    public function  profile(){
        return $this->belongsTo(SpecialistProfile::class);
    }

    public function  speciality(){
        return $this->belongsTo(Speciality::class);
    }
}
