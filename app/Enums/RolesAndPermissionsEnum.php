<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;
use Illuminate\Support\Collection;

enum RolesAndPermissionsEnum: string
{
    use FullEnum;

    case Admin = 'admin';
    case Client = 'client';
    case Customer = 'customer';
    case User = 'user';
    case Manager = 'manager';
    case CompanyMember = 'company_member';
    case Guest = 'guest';
    case Lessor = 'lessor';
    case Renter = 'renter';

    public  function getPermissions()
    {
        return match ($this) {
            static::Admin => ['admin', 'user.*', 'user.impersonate', 'company', 'rent'],
            static::Manager =>  ['user.impersonate',],
            static::Client =>  [],
            static::Customer =>  [
                'company.show',
                'company.update',
                'rent.*'
            ],
            static::User =>  ['rent.show'],
            static::CompanyMember =>  [
                'company.show',
                'company.update',
                'schedule',
                'place'
            ],

            static::Renter =>  ['rent.*'],
            static::Lessor =>  ['schedule.*'],
            static::Guest =>  ['company.show',],
        };
    }

    public static function getFreePermissions(): Collection
    {
        return collect(['superadmin']);
    }

    public static function forCompany()
    {
        collect([
            static::CompanyMember(),
            static::Renter(),
        ]);
    }

    public static function initialRoles()
    {
        return collect([
            static::Lessor(),
            static::User(),
        ]);
    }

    public static function initialPermissions()
    {
        return collect();
    }
}
