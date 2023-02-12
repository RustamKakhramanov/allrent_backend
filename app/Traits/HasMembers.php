<?php

namespace App\Traits;

use App\Models\User;
use App\Enums\CompanyRolesEnum;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;

trait HasMembers
{
    use HasRelationships;
    
    public function members()
    {
        return $this->belongsToMany(User::class, 'company_members');
    }

    public function getOwnerAttribute()
    {
        return $this->members()
            ->whereType(CompanyRolesEnum::Owner())
            ->oldest()
            ->first();
    }
}
