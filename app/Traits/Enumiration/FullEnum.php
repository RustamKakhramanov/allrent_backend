<?php

namespace App\Traits\Enumiration;

use App\Traits\Enumiration\EnumArrayable;
use App\Traits\Enumiration\EnumProperties;



trait FullEnum
{
    use EnumArrayable, EnumProperties, EqualEnum, FromEnum;
}
