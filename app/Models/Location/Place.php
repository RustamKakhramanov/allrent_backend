<?php

namespace App\Models\Location;

use App\Traits\HasPrice;
use App\Models\Record\Rent;
use App\Enums\PriceTypeEnum;
use App\Models\Record\Price;
use App\Models\Location\City;
use App\Enums\ScheduleTypeEnum;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use App\Models\Review;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use App\Traits\Eloquent\Sluggable;

use App\Traits\Eloquent\Lessorable;
use App\Traits\Eloquent\ExtendedBuilds;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Repositories\Location\PlaceRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 *
 * @property int $id
 * @property int $city_id
 * @property int $company_id
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $address
 * @property array $coordinates
 * @property array $info
 * @property User|null $owner
 * @property Builder|Rent [] $rents
 * @property Collection|Schedule[]  $schedules
 * @property null|Schedule  $default_schedule
 * @property null|Schedule  $today_schedule
 * @property string $description
 * @method static Builder|User newQuery()
 */
class Place extends Model implements HasMedia
{
    use HasFactory, Lessorable, Sluggable;
    use ExtendedBuilds;
    use InteractsWithMedia;
    use HasPrice;
    
    protected $fillable = [
        'company_id',
        'city_id',
        'name',
        'description',
        'address',
        'coordinates',
        'info',
        'slug'
    ];

    protected $casts = ['coordinates' => 'array', 'info' => 'array'];
    protected $with = ['price'];


    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_places');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getTodayScheduleAttribute()
    {
        return $this->schedules()
            ->whereToday()
            ->orWhere('type',  ScheduleTypeEnum::Default())
            ->orderByRaw(
                $this->prepareOrderByRaw(ScheduleTypeEnum::getCasesByPriority(), 'type')
            )
            ->first();
    }

    public function getDefaultScheduleAttribute()
    {
        return $this->schedules()->whereDefault()->first();
    }

    public function getTodayRentsAttribute()
    {
        return $this->rents()->whereDay('scheduled_at', now())->first();
    }

    public function getCompletedTodayScheduleAttribute()
    {
        return PlaceRepository::getCompletedSchedule($this, now());
    }

    public function getFreeTodayScheduleAttribute()
    {
        return PlaceRepository::getFreeSchedule($this, now());
    }

    public function reviews(){
        return $this->morphMany(Review::class, 'reviewed');
    }
}
