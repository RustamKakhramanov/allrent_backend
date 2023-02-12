<?php

namespace App\Models\Company;

use App\Models\User;
use App\Traits\HasMembers;
use App\Traits\HasContacts;
use App\Models\Location\Place;
use App\Enums\CompanyRolesEnum;
use App\Traits\Eloquent\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 *
 * @property int $id
 * @property string $name
 * @property array $info
 * @property User $owner
 * @property Builder|User [] $members
 * @property Collection|Place[]  $places
 * @property string $description
 * @method static Builder|User newQuery()
 */
class Company extends Model
{
    use HasFactory, SoftDeletes, Sluggable, HasContacts, HasMembers;

    protected $fillable = [
        'name',
        'description',
        'info',
        'slug'
    ];
    
    
    protected $casts = [
        'info' => 'array',
    ];

    protected $with = ['places'];

    protected $dates = ['deleted_at'];


    //$company->load(['places.schedules' => fn (MorphMany $to) => $to->where('company_id', $company->id)]);
    public function places()
    {
        return $this->hasMany(Place::class)
            // ->without(
            //     'rents',
            //     'schedules',
            // )
        ;
    }

    public function groupPlaces()
    {
        return $this->belongsToMany(Place::class, 'company_places')
            // ->without(
            //     'rents',
            //     'schedules',
            // )
        ;
    }

   

    public function allSchedules()
    {
        // $activities = ActivityFeed::query()
        //     ->with(['parentable' => function (MorphTo $morphTo) {
        //         $morphTo->morphWith([
        //             Event::class => ['calendar'],
        //             Photo::class => ['tags'],
        //             Post::class => ['author'],
        //         ]);
        //     }])->get();
    }
}
