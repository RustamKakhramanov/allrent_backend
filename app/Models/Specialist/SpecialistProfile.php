<?php

namespace App\Models\Specialist;

use App\Models\User;
use App\Traits\Imageable;
use App\Traits\HasContacts;
use App\Models\Company\Company;
use App\Traits\Eloquent\CompanySearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property array $info
 * @property Builder|SpecialistProfile  $profile
 * @property null|Company  $company
 * @property null|Speciality  $speciality
 * @property Collection|Speciality[]  $specialites 
 */
class SpecialistProfile extends Model
{
    use HasFactory, CompanySearch;
    use Imageable, HasContacts;

    protected $fillable = ['user_id', 'company_id', 'info'];

    protected $casts = [
        'info' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    public function specialities()
    {
        return $this->belongsToMany(Speciality::class, 'profile_has_specialities');
    }

    public function getSpecialityAttribute()
    {
        return $this->specialities()->orderByPivot('is_main')->first();
    }

    public function mainSpecialty(){
        return $this->belongsToMany(Speciality::class, 'profile_has_specialities')->whereIsMain(true);
    }
 
}
