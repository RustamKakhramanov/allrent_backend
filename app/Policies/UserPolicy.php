<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any customers.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('users.browse') || $user->can('customers.add') || $user->can('customers.edit');
    }

    /**
     * Determine whether the user can view the customer.
     *
     * @param User $user
     * @param User $userModel
     * @return mixed
     */
    public function view(User $user, User $userModel)
    {
        return $user->can('users.read');
    }

    /**
     * Determine whether the user can create customers.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('users.add');
    }

    /**
     * Determine whether the user can update the customer.
     *
     * @param User $user
     * @param User $userModel
     * @return mixed
     */
    public function update(User $user, User $userModel)
    {
        return $user->can('users.edit');
    }

    /**
     * Determine whether the user can delete the customer.
     *
     * @param User $user
     * @param User $userModel
     * @return mixed
     */
    public function delete(User $user, User $userModel)
    {
        return $user->can('users.delete');
    }
}
