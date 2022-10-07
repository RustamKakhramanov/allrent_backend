<?php


namespace App\Http\Filters;

use App\Services\Http\BaseFilter;



class CityFilter extends BaseFilter
{
    protected $filters = ['country', 'name', 'is_published'];

    protected function name($name)
    {
        return $this->builder->where('name', 'ilike', "{$name}%");
    }

    protected function country($country_id)
    {
        return $this->builder->where('country_id', $country_id);
    }

    protected function is_published($bool)
    {
        return $this->builder->where('is_published', $bool === 'true');
    }

  
}
