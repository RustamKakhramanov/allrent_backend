<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum CompanyRolesEnum:string
{
    use FullEnum;

    case Member = 'member';
    case Owner = 'owner';
    case Manager = 'manager';
}
