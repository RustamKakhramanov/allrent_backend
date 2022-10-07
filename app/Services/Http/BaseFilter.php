<?php


namespace App\Services\Http;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class BaseFilter
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * The Eloquent builder.
     *
     * @var Builder
     */
    protected $builder;
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new ThreadFilters instance.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply the filters.
     *
     * @param  Builder $builder
     * @return Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            $this->callMethods($filter, $value);
        }
        return $this->builder;
    }

    protected function callMethods($filter, $value)
    {
        if (!method_exists($this, $filter)) {
            return;
        }

        if (is_array($value) && isset($this->filters[$filter]) && is_array($this->filters[$filter])) {
            $this->$filter(...$value);
        } else {
            $this->$filter($value);
        }
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        $filters = [];

        foreach ($this->filters as $key => $filter) {
            if (is_array($filter)) {
                $value = $this->getArrayFromRequest($filter);
            } else {
                $key = $filter;
                $value = $this->request->get($filter);
            }

            if ($value) {
                $filters[$key] = $value;
            }
        }

        return $filters;
    }

    protected function getArrayFromRequest($keys): ?array
    {
        $values = $this->request->only($keys);
        foreach ($values as $value) {
            if (!is_bool($value) && !is_array($value) && trim((string)$value) === '') {
                return null;
            }
        }

        if (count($keys) !== count($values)) {
            return null;
        }

        return array_values($values);
    }
}
