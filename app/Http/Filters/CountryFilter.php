<?php


namespace App\Http\Filters;

use App\Services\Http\BaseFilter;

class CountryFilter extends BaseFilter
{
    protected $filters = ['name'];

    protected function name($name)
    {
        return $this->builder->where('name', 'ilike', "{$name}%");
    }
}
