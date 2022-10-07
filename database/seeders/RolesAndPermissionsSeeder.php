<?php

namespace Database\Seeders;

use App\Enums\RolesAndPermissionsEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (RolesAndPermissionsEnum::cases() as $case) {
            $permissions = collect($case->getPermissions())
                ->each(fn ($item) => Permission::firstOrCreate(['name' => $item]));

            Role::firstOrCreate(['name' => $case->value])
                ->givePermissionTo($permissions);
        };

        RolesAndPermissionsEnum::getFreePermissions()->each(
            fn ($permission) => Permission::firstOrCreate(['name' => $permission])
        );
    }
}
