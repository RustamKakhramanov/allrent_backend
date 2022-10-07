<?php

namespace App\Traits\Enumiration;

trait EnumProperties
{
    public static function names(): array
    {
        return array_column(static::cases(), 'name');
    }

    public static function options(): array
    {
        $cases = static::cases();

        return  array_column($cases, 'value', 'name');
    }

    public static function values(): array
    {
        $cases = static::cases();

        return array_column($cases, 'value');
    }

    public static function __callStatic($name, $arguments)
    {
        // Note: value of $name is case sensitive.
        return static::options()[$name] ?? null;
    }
}
