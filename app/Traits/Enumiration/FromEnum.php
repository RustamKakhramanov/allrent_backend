<?php

namespace App\Traits\Enumiration;

use ValueError;
use Illuminate\Support\Arr;

trait FromEnum
{
    use EnumProperties;

    /**
     * Gets the Enum by name, if it exists, for "Pure" enums.
     *
     * This will not override the `from()` method on BackedEnums
     *
     * @throws ValueError
     */
    public static function from(string $case): static
    {
        return static::fromName($case);
    }

    /**
     * Gets the Enum by name, if it exists, for "Pure" enums.
     *
     * This will not override the `from()` method on BackedEnums
     *
     * @throws ValueError
     */
    public static function tryKeyLowerFrom(string $case)
    {
        return strtolower(static::tryFrom($case)->name ?? null);
    }



    /**
     * Gets the Enum by name, if it exists, for "Pure" enums.
     *
     * This will not override the `tryFrom()` method on BackedEnums
     */
    public static function tryFrom(string $case): ?static
    {
        return static::tryFromName($case);
    }

    /**
     * Gets the Enum by name.
     *
     * @throws ValueError
     */
    public static function fromName(string $case): static
    {
        return static::tryFromName($case) ?? throw new ValueError('"' . $case . '" is not a valid name for enum "' . static::class . '"');
    }

    /**
     * Gets the Enum by name, if it exists.
     */
    public static function tryFromNameValue(string $case): ?string
    {
        return static::tryFromName($case)->value ?? null;
    }

    public static function tryFromName(string $case): ?static
    {
        $cases = array_filter(
            static::cases(),
            fn ($c) => strtolower($c->name) === strtolower($case)
        );

        return Arr::first($cases);
    }



    public static function tryFromMatchValue($case): ?string
    {
        return static::tryFromMatch($case)->value ?? null;
    }


    public static function tryFromMatch($case): ?static
    {
        $cases = array_filter(
            static::cases(),
            fn ($c) => method_exists(static::class, 'value') ? $c->value() == $case : null
        );

        return Arr::first($cases);
    }
}
