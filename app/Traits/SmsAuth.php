<?php


namespace App\Traits;


use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

trait SmsAuth
{
    public function generateAuthCode($expire_in_minutes = 10): int
    {
        $code = App::isProduction() ? rand(1000, 9999) : 1111;
        
        Cache::forget("user_code_{$this->id}");
        Cache::put("user_code_{$this->id}", $code, now()->addMinutes($expire_in_minutes));

        return $code;
    }

    public function verifyAuthCode(int $code)
    {
        $saved_code = intval(Cache::get("user_code_{$this->id}"));

        return $code === $saved_code;
    }

    public function forgetAuthCode()
    {
        return Cache::forget("user_code_{$this->id}");
    }
}
