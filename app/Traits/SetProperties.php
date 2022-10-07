<?php

namespace App\Traits;

trait SetProperties
{
    public function __construct($properties = [])
    {
        if ($properties instanceof Arrayable) {
            $properties = $properties->toArray();
        }

        foreach ($properties as $property => $value) {
            if (property_exists(static::class, $property)) {
                $this->$property = $value;
            }
        }
    }
}
