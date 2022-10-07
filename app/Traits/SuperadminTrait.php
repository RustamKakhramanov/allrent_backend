<?php


namespace App\Traits;

use App\Models\User;



trait SuperadminTrait
{
    public function before(User $user, $ability)
    {
        if ($user->can('superadmin')) {
            return true;
        }
    }
}
