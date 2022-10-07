<?php

namespace App\Policies\Rent;

use App\Models\User;
use App\Models\Record\Rent;
use App\Models\Company\Company;
use App\Models\Location\Place;
use Illuminate\Auth\Access\HandlesAuthorization;

class RentPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        $r_company = request()->route('company');
        $r_place = request()->route('place');
        $this->company = is_string($r_company)? Company::findBySlug($r_company) :$r_company;
        $this->place = is_string($r_place)? Place::findBySlug($r_place) :$r_place;

        if (
            !isset($this->company->id)
            ||
            !isset($this->place->id)
            ||
            $this->company->id !== $this->place->company_id
        ) {
            return false;
        }
    }
    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->isMember(Company::findBySlug(request()->route('company')));
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Rent $rent)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->can('rent.store');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Rent $rent)
    {
        return $user->can('rent.update');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Rent $rent)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Rent $rent)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Record\Rent  $rent
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Rent $rent)
    {
        //
    }
}
