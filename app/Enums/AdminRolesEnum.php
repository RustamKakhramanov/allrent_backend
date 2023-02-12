<?php

namespace App\Enums;

use App\Enums\VacancyDirectionsEnum;
use App\Traits\Enumiration\FullEnum;
use Illuminate\Support\Collection;

/**
 * @method static string  Administrator()
 * @method static string  Recruiter()
 * @method static string  ContentManager()
 * @method static string  ManagerLogistic()
 * @method static string  ManagerOffice()
 * @method static string  ManagerSales()
 */
enum AdminRolesEnum: string
{
    use FullEnum;

    case  Administrator = "administrator";
    case  CompanyOwner = "company-owner";
    case  PlaceOwner = "place-owner";
    case  Manager = "manager";

    public function getName(): string
    {

        return match ($this) {
            static::Administrator => 'Администратор',
            static::CompanyOwner => 'Менеджер компании',
            static::PlaceOwner => 'Менеджер мест',
            static::Manager => 'Ряовой менеджер',
        };
    }

}
