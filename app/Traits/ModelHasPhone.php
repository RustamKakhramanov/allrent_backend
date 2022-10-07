<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
/**
 * @property int|null $phone
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 */
trait ModelHasPhone
{
    use SmsAuth;

    public static function findByPhone(int $number): ?Model
    {
        return static::wherePhone($number)->first();
    }
    
    public function hasVerifiedPhone()
    {
        return !is_null($this->phone_verified_at);
    }
    
}
