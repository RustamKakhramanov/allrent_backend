<?php

namespace App\Traits\Enumiration;

trait EnumArrayable
{
    public static function toArrayCases(){
        $array = [];

        foreach(static::cases() as $item) {
            $array[$item->name] = $item->value;
        }

        return $array;
    }

    public static function toLowerArrayCases(){
        return static::toLowerCollectCases()->toArray();
    }

    public static function toCollectCases(){
        return collect(static::toArrayCases());
    }

    public static function toLowerCollectCases(){
        return collect(static::toArrayCases())->mapWithKeys(fn($value, $key) => [strtolower($key) => $value]);
    }
}
