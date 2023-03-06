<?php

use App\Admin\Models\Admin;
use App\Enums\TimeEnum;
use App\Models\User;
use App\Helpers\LettersHelper;
use Illuminate\Support\Carbon;

if (!function_exists('latin_to_cyrillic')) {
    function latin_to_cyrillic($latinString)
    {
        $cyr = LettersHelper::getCyrilicLetters();
        $lat = LettersHelper::getLatinLetters();

        return str_replace($lat, $cyr, $latinString);
    }
}

if (!function_exists('strtoslug')) {
    function strtoslug($string)
    {
        $str = strtolower(cyrillic_to_latin($string));
        $replace = ['-' => ' ', '' => ['.', ',', '', '(', ')']];

        foreach ($replace as $replaced => $search) {
            $str = str_replace($search, $replaced, $str);
        }

        return $str;
    }
}

if (!function_exists('cyrillic_to_latin')) {
    function cyrillic_to_latin($cyrillicString)
    {
        $cyr = LettersHelper::getCyrilicLetters();
        $lat = LettersHelper::getLatinLetters();

        return str_replace($cyr, $lat, $cyrillicString);
    }
}

if (!function_exists('is_local')) {
    function is_local()
    {
        return env('APP_ENV') === 'local';
    }
}

if (!function_exists('is_production')) {
    function is_production()
    {
        return env('APP_ENV') === 'production';
    }
}

if (!function_exists('btime_intervals')) {
    function btime_intervals($time_begin, $time_end, $format = '', $last = false)
    {
        $begin = new DateTime($time_begin);
        $end = new DateTime($time_end);

        $interval = new DateInterval('PT30M');
        $periods = new DatePeriod($begin, $interval, $end);

        $intervals = collect(iterator_to_array($periods))->map(fn (DateTime $time) => $format ? $time->format($format) : $time->getTimestamp());

        $c_end = $format ? cparse($time_end)->format($format) : cparse($time_end)->getTimestamp();

        if ($last && $intervals->doesntContain($c_end)) {
            $intervals = $intervals->push($c_end);
        }

        return $intervals;
    }
}


if (!function_exists('to_timestamp')) {
    function to_timestamp($value)
    {
        return is_timestamp($value) ? Carbon::parse($value)->getTimestamp() : false;
    }
}

if (!function_exists('cparse')) {
    function cparse($value)
    {   
        if(request('test') === 1){
            date_default_timezone_set('Asia/Almaty');

            dd( date( 'Y,m H:i', 1662489600));
        }
        
        try{
            return Carbon::parse($value);

        }catch(\Exception){
            return Carbon::parse($value);

        }
    }
}

if (!function_exists('is_timestamp')) {
    function is_timestamp($value)
    {
        if ($value instanceof Carbon) {
            $value = $value->getTimestamp();
        }

        return ((string) (int) $value === $value)
            && ($value <= PHP_INT_MAX)
            && ($value >= ~PHP_INT_MAX);
    }
}

if (!function_exists('get_day_time')) {
    function get_day_time($need)
    {
        $parsed  = Carbon::parse($need)->format('H:i');

        foreach (TimeEnum::getCallectWithoutParsingIntervals() as $time => $interval) {
            [$start, $end] = $interval;

            if ($parsed > $start && $parsed <= $end) {
                return  $time;
            }
        }
    }
}

if (!function_exists('user')) {
    function user()
    {
        return  auth()->user();
    }
}
if (!function_exists('admin')){
    function admin():User 
    {
        return  User::find(auth()->user()->id);
    }
}
