<?php

namespace App\Traits;

use ReflectionClass;
use App\Services\ConstantAndValuesService;
use Illuminate\Contracts\Support\Arrayable;

trait MockDTO
{
    use GetConstants;

    private $dataClass;

    public function setDataClass( $class)
    {
        $this->dataClass = $class;

        return $this;
    }

    public function mock($value = true, $class = null)
    {
        return $this->mockHandle($this->getData($class ?: $this), $value);
    }


    public function mockFromKeys($value = true, $class = null)
    {
        return $this->mockHandle($this->getData($class ?: $this, 'fromKeys'), $value);
    }

    private function getData($class = null, $action = 'from')
    {
        return (new ConstantAndValuesService)->$action(
            $class ?? $this,
            $this->dataClass
        );
    }

    private function mockHandle($data, $value = true)
    {
        foreach ($data as $property => $data) {
            $this->$property = collect($data)->values()->mapWithKeys(fn ($i) => [$i => $value])->toArray();
        }

        return $this;
    }
}
