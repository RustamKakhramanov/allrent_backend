<?php

namespace App\Listeners;

use App\Enums\RolesAndPermissionsEnum;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Spatie\Permission\Traits\HasPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class GiveInitialPermissions
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @var  $model
     * @return void
     */
    public function handle($event)
    {
        $model = $event->user ?? $event->model;

        if ($model instanceof HasRoles) {
            $model->assignRole(RolesAndPermissionsEnum::initialRoles());
        }

        if ($model instanceof HasPermissions) {
            $model->givePermissionTo(RolesAndPermissionsEnum::initialPermissions());
        }
    }
}
