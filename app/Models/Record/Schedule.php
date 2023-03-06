<?php

namespace App\Models\Record;

use App\Models\User;
use App\Traits\HasPrice;
use App\Enums\ScheduleTypeEnum;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @property int $id
 * @property string $name
 * @property array $info
 * @property  $schedulable
 * @property null|Company $company
 * @property null|User $user
 * @property string $description
 * @method static Builder|User newQuery()
 * @method static Builder whereToday()
 * @method static Builder whereDefault()
 */
class Schedule extends Model
{
    use HasFactory, HasPrice;

    protected $fillable = [
        'user_id',
        'company_id',
        'schedulable_id',
        'schedulable_type',
        'schedule',
        'type',
        'date'
    ];

    protected $appends = ['start_at', 'end_at'];
    protected $dates = ['date'];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function schedulable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeWhereToday(Builder $builder)
    {
        return $builder->where('type', ScheduleTypeEnum::Day())->whereDay('date', now());
    }

    public function scopeWhereDefault(Builder $builder)
    {
        return $builder->where('type', ScheduleTypeEnum::Default());
    }

    public function getStartAtAttribute()
    {
        return $this->schedule[0];
    }

    public function getEndAtAttribute()
    {
        return array_values($this->schedule)[count($this->schedule) - 1];
    }
}
