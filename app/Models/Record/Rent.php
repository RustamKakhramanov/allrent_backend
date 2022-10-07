<?php

namespace App\Models\Record;

use App\Models\User;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialist\SpecialistProfile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @property int $id
 * @property int $user_id
 * @property int $company_id
 * @property int $specialist_profile_id
 * @property int $rentable_id
 * @property string $name
 * @property array $detail
 * @property Company $company
 * @property User $user
 * @property SpecialistProfile $specialist
 * @property Carbon $scheduled_at
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'specialist_profile_id',
        'rentable_id',
        'rentable_type',
        'type',
        'currency',
        'is_paid',
        'amount',
        'detail',
        'scheduled_at',
        'scheduled_end_at',
        'start_at',
        'end_at',
    ];

    protected $dates = [
        'scheduled_at',
        'scheduled_end_at',
        'start_at',
        'end_at'
    ];

    protected $casts = [
      'detail' => 'array'  
    ];


    public function rentable()
    {
        return $this->morphTo();
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function specialist(){
        return $this->belongsTo(SpecialistProfile::class);
    }
}
