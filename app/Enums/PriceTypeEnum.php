<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum PriceTypeEnum: string
{
    use FullEnum;
    
    case PerHour = 'per_hour';
    case Initial = 'initial';
    case Session = 'session';

    public function getName(){
        return match($this){
            static::PerHour => 'Час',
            static::Initial => 'День',
            static::Session => 'Посещение',
        };
    }
}
