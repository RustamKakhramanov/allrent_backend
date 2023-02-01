<?php

namespace App\Enums;

use Illuminate\Support\Str;
use App\Traits\Enumiration\FullEnum;

enum TimeEnum: string
{
    use FullEnum;

    case Morning = 'morning';
    case Day = 'day';
    case Evening = 'evening';
    case Night = 'night';

    public function getTime()
    {
        return match ($this) {
            static::Night  => call_user_func_array('btime_intervals', $this->getIntervals()),
            static::Morning  => call_user_func_array('btime_intervals', $this->getIntervals()),
            static::Day  => call_user_func_array('btime_intervals', $this->getIntervals()),
            static::Evening  => call_user_func_array('btime_intervals', $this->getIntervals()),
        };
    }

    public function getIntervals()
    {
        return match ($this) {
            static::Night  => ['00:00', '06:00'],
            static::Morning =>  ['06:00', '12:00'],
            static::Day =>  ['12:00', '18:00'],
            static::Evening =>  ['18:00', '24:00'],
        };
    }

    public static function defaultFormats(){
        return ['H:i', true];
    }
    
    public static function getAllIntervals()
    {
        return collect(static::cases())
            ->mapWithKeys(
                fn ($enum) => [
                    $enum->value => call_user_func_array('btime_intervals', array_merge($enum->getIntervals(), static::defaultFormats()))
                ]
            )->toArray();
    }

    public static function getCallectWithoutParsingIntervals()
    {
        return collect(static::cases())->mapWithKeys(fn ($enum) => [$enum->value => $enum->getIntervals()])->toArray();
    }

    public static function __callStatic($name, $arguments)
    {
        if (Str::startsWith($name, 'is')) {
            $name = Str::after($name, 'is');
            return get_day_time($arguments[0]) === static::tryFromNameValue($name);
        }

        return static::options()[$name] ?? null;
    }
}
