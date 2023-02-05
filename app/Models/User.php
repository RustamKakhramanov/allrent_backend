<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Traits\HasAvatar;
use App\Models\Record\Rent;
use App\Traits\ModelHasPhone;
use App\Models\Company\Company;
use App\Models\Record\Schedule;
use App\Traits\ModelSearchable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Specialist\SpecialistProfile;
use Spatie\Permission\Traits\HasPermissions;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property Collection|null $permissions
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $last_seen
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $phone_verified_at
 * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection|Token[] $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static givePermissionTo($permission)
 * @method static assignRole($permission)
 * @method static syncPermissions($permission)
 * @method static syncRoles($permission)
 * @method static revokePermissionTo($permission)
 * @method static removeRole($permission)
 * @method static Collection getPermissionNames()
 * @method static Collection getRoleNames()
 * @method static Collection getDirectPermissions()
 * @method static Collection getPermissionsViaRoles()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User filter(\App\Http\Filters\UserFilter $filter)
 * @method  \Illuminate\Database\Eloquent\Collection|\App\Permission[] getAllPermissions()
 */

class User extends Authenticatable  implements HasMedia
{
    use HasRelationships, HasRoles, HasPermissions;

    use HasApiTokens, HasFactory, Notifiable, ModelSearchable, ModelHasPhone;
    use HasAvatar;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',  'phone_verified_at',
    ];

    protected $with = ['roles', 'permissions'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];
    
    public function rents(){
        return $this->hasMany(Rent::class);
    }

    public function profiles()
    {
        return $this->hasMany(SpecialistProfile::class);
    }

    public function compniesProfiles()
    {
        return $this->profiles()->where('company_id', '!=', null);
    }

    public function freelanceProfiles()
    {
        return $this->profiles()->whereCompanyId(null);
    }

    public function companies()
    {
        return $this->belongsToMany(Company::class, 'company_members');
    }

    public function isOwner(Company $company): bool
    {
        return $company->owner ? $company->owner->id === $this->id : false;
    }

    public function isMember(Company $company): bool
    {
        return $company->members()->get()->pluck('id')->contains($this->id);
    }

    public function isScheduler(Schedule $schedule): bool
    {
        return $schedule->user_id === $this->id;
    }

    public function isAdmin(): bool
    {
        return $this->can('admin');
    }

    public function isSuperAdmin(): bool
    {
        return $this->can('superadmin');
    }
}
