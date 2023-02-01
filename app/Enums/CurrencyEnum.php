<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum CurrencyEnum :string
{
    use FullEnum;
    
    case KZT = 'kzt';
}
