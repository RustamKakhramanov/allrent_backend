<?php

namespace App\Traits;

use Illuminate\Support\Enumerable;

trait Arrayable
{
    public function toArray($excludes = []): array
    {
        if ($excludes instanceof Enumerable) {
            $excludes = $excludes->all();
        } elseif (!is_array($excludes)) {
            $excludes = func_get_args();
        }

        return collect(get_object_vars($this))->except($excludes)->toArray();
    }
}
