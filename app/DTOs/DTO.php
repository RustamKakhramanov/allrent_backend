<?php

namespace App\DTOs;

use JsonSerializable;
use App\Traits\RoundAble;
use App\Traits\Arrayable as ArrayTrait;
use Illuminate\Contracts\Support\Arrayable;

class DTO implements JsonSerializable
{
    use ArrayTrait;
    use RoundAble;

    public function __get($name)
    {
        return isset($this->$name)
            ? $this->$name
            : null;
    }

    public function __construct($properties = [])
    {

        if (is_array($properties) && count($properties) == 1 && array_values($properties)[0] instanceof Arrayable) {
            $properties[0]->each(function ($value, $property) {
                if (property_exists(static::class, $property)) {
                    $this->$property = $value;
                }
            });
        } elseif (is_array($properties)) {
            foreach ($properties as $property => $value) {
                if (property_exists(static::class, $property)) {
                    $this->$property = $value;
                }
            }
        }

        if (method_exists(static::class, 'init')) {
            $this->init($properties);
        }
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public static function make($properties = []): static
    {
        if (!is_array($properties)) {
            $properties = func_get_args();
        }

        return new static($properties);
    }
}
