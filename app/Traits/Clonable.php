<?php

namespace App\Traits;

trait Clonable
{
    public function __clone()
    {
        foreach ((array) get_object_vars($this) as $key => $value) {
            if (
                method_exists(static::class, 'disCloned') && in_array($key, $this->disCloned())
                ||
                !property_exists(static::class, $key)
            ) {
                $this->$key = null;
            } else {
                $this->$key =  $this->$key;
            }
        }
    }
}
