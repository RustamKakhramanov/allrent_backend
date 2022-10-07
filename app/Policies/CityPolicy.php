<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Location\City;
use Illuminate\Auth\Access\HandlesAuthorization;

class CityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any cities.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function view(User $user, City $city)
    {
        return $user->can('core.cities.read');
    }

    /**
     * Determine whether the user can create cities.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('core.cities.add');
    }

    /**
     * Determine whether the user can update the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function update(User $user, City $city)
    {
        return $user->can('core.cities.edit');
    }

    /**
     * Determine whether the user can delete the city.
     *
     * @param User $user
     * @param City $city
     * @return mixed
     */
    public function delete(User $user, City $city)
    {
        return $user->can('core.cities.delete');
    }
}
