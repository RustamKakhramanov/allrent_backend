<?php

namespace Database\Seeders;

use App\Admin\Models\Admin;
use App\Enums\AdminRolesEnum;
use App\Admin\Models\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    const ADMIN_INIT_LOGIN = 'admin';
    const ADMIN_INIT_PASSWORD = 'qazwsx12';

    public function run()
    {
        // create a user.
        // Administrator::truncate();
        $admin = Admin::firstOrCreate([
            'username' => self::ADMIN_INIT_LOGIN,
            'name'     => 'Administrator',
        ], ['password' => Hash::make(self::ADMIN_INIT_PASSWORD),]);

        // create a role.
        // Role::truncate();
        collect(AdminRolesEnum::cases())->each(
            fn (AdminRolesEnum $i) => Role::firstOrCreate([
                'name' => $i->getName(),
                'slug' => $i->value,
            ])
        );

        $roles = Role::all();

        $adminRole = $roles->where('slug', AdminRolesEnum::Administrator())->first();
        $placeOwner = $roles->where('slug', AdminRolesEnum::PlaceOwner())->first();
        $companyOwner = $roles->where('slug', AdminRolesEnum::CompanyOwner())->first();
        $manager = $roles->where('slug', AdminRolesEnum::Manager())->first();

        // add role to user.
        $admin->roles()->save($adminRole);

        //create a permission
        Permission::truncate();
        Permission::insert([
            [
                'name'        => 'Полной доступ',
                'slug'        => '*',
                'http_method' => '',
                'http_path'   => '*',
            ],
            [
                'name'        => 'Панель',
                'slug'        => 'dashboard',
                'http_method' => 'GET',
                'http_path'   => '/',
            ],
            [
                'name'        => 'Вопросы',
                'slug'        => 'questions',
                'http_method' => 'GET',
                'http_path'   => '/questions*',
            ],
            [
                'name'        => 'Авторизация',
                'slug'        => 'auth.login',
                'http_method' => '',
                'http_path'   => "/auth/login\r\n/auth/logout",
            ],
            [
                'name'        => 'Настройка профиля',
                'slug'        => 'auth.setting',
                'http_method' => 'GET,PUT',
                'http_path'   => '/auth/setting',
            ],
            [
                'name'        => 'Управление арендами',
                'slug'        => 'rents',
                'http_method' => '',
                'http_path'   => '/rents*',
            ],
            [
                'name'        => 'Управление компанией',
                'slug'        => 'companies',
                'http_method' => '',
                'http_path'   => '/companies*',
            ],
            [
                'name'        => 'Управление местами',
                'slug'        => 'places',
                'http_method' => '',
                'http_path'   => '/places*',
            ],
            [
                'name'        => 'Управление графиками',
                'slug'        => 'schedules',
                'http_method' => '',
                'http_path'   => '/schedules*',
            ],
           
            [
                'name'        => 'Управление городами',
                'slug'        => 'cities',
                'http_method' => '',
                'http_path'   => '/cities*',
            ],
            [
                'name'        => 'Управление тегами',
                'slug'        => 'tags',
                'http_method' => '',
                'http_path'   => '/tags*',
            ],
            [
                'name'        => 'Управление страницами',
                'slug'        => 'pages',
                'http_method' => '',
                'http_path'   => '/pages*',
            ],
            [
                'name'        => 'Управление пользователями',
                'slug'        => 'users',
                'http_method' => '',
                'http_path'   => '/users*',
            ],
            [
                'name'        => 'Настройка контента',
                'slug'        => 'settings',
                'http_method' => '',
                'http_path'   => '/settings*',
            ],
            [
                'name'        => 'История',
                'slug'        => 'history',
                'http_method' => '',
                'http_path'   => '/history*',
            ],
            [
                'name'        => 'Настройка доступов',
                'slug'        => 'auth.management',
                'http_method' => '',
                'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
            ]
        ]);

        $adminRole->permissions()->sync(Permission::query()->whereIn('slug',['*'])->get()->pluck('id')->toArray());
        // Permission::query()->whereIn('slug', ['orders','users', 'auth.login', 'questions', 'dashboard' ])->each(function (Permission $permission) use($managerRole) {
        //     $managerRole->permissions()->save($permission);
        // });

      
        $permissionsCompany =  Permission::query()->whereIn('slug', ['settings', 'auth.login', 'schedules', 'companies', 'places', 'rents'])->get()->pluck('id')->toArray();
        $permissionsForPlace = Permission::query()->whereIn('slug', ['auth.login', 'history', 'dashboard', 'schedules', 'places', 'rents'])->pluck('id')->toArray();
        $permissionsForManagers = Permission::query()->whereIn('slug', ['auth.login', 'history', 'dashboard', 'rents',])->pluck('id')->toArray();

        $placeOwner->permissions()->sync($permissionsForPlace);
        $companyOwner->permissions()->sync($permissionsCompany);
        $manager->permissions()->sync($permissionsForManagers);

        Artisan::call('passport:install');

        // add default menus.
        Menu::truncate();
        Menu::insert([
            [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Панель',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],

            [
                'parent_id' => 0,
                'order'     => 2,
                'title'     => 'Администрирование',
                'icon'      => 'fa-tasks',
                'uri'       => '',
            ],
            [
                'parent_id' => 2,
                'order'     => 3,
                'title'     => 'Юзеры панели',
                'icon'      => 'fa-users',
                'uri'       => 'auth/users',
            ],
            [
                'parent_id' => 2,
                'order'     => 4,
                'title'     => 'Роли',
                'icon'      => 'fa-user',
                'uri'       => 'auth/roles',
            ],
            [
                'parent_id' => 2,
                'order'     => 5,
                'title'     => 'Доступы',
                'icon'      => 'fa-ban',
                'uri'       => 'auth/permissions',
            ],


            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Аренды',
                'icon'      => 'fa-save',
                'uri'       => 'rents',
            ],
            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Компании',
                'icon'      => 'fa-group',
                'uri'       => 'companies',
            ],
            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Места',
                'icon'      => 'fa-list-ul',
                'uri'       => 'places',
            ],
            [
                'parent_id' => 0,
                'order'     => 8,
                'title'     => 'Графики',
                'icon'      => 'fa-calendar',
                'uri'       => 'schedules',
            ],

             
        ]);

        Menu::find(2)->roles()->save($adminRole);

        Menu::query()->whereIn('uri', ['rents', 'companies', 'places', 'schedules'])->each(function (Menu $menu) use ($companyOwner) {
            $menu->roles()->save($companyOwner);
        });
        Menu::query()->whereIn('uri', ['rents', 'places', 'schedules'])->each(function (Menu $menu) use ($placeOwner) {
            $menu->roles()->save($placeOwner);
        });
        
        Menu::query()->whereIn('uri', ['rents', 'rents'])->each(function (Menu $menu) use ($manager) {
            $menu->roles()->save($manager);
        });
    }
}
