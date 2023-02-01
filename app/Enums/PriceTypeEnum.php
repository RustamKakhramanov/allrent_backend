<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum PriceTypeEnum: string
{
    use FullEnum;
    
    case PerHour = 'per_hour';
    case Initial = 'initial';
}
