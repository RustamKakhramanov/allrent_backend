<?php

namespace App\Traits;

use App\Traits\FromEnum;
use App\Traits\EnumArrayable;


trait GetConstantValue
{
    use FromEnum;

    public static function fromStr(string $str)
    {
        return strtolower(static::tryFrom($str)->name ?? $str);
    }

}
