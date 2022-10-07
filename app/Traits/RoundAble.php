<?php

namespace App\Traits;

trait RoundAble
{
    use Arrayable;

    private function roundHandle($prec = 0, $excludes, $handler = 'round')
    {
        foreach ($this->toArray() as $property => $value) {
            if (is_float($value) && !$this->isExclude($property, $excludes)) {
                $value = $handler($value, $prec);
            }

            if ($this->isExclude($property, $excludes)) {
                $property = $this->getExcludeValue($property, $excludes, $value, $handler);
            }

            $this->$property = $value;
        }

        return $this;
    }

    public function isExclude($property, $excludes)
    {
        return in_array($property, $excludes) || in_array($property, array_keys($excludes));
    }

    public function getExcludeValue($property, $excludes, $value, $handler)
    {

        return isset($excludes[$property]) && is_int($excludes[$property])
            ?
            $handler($value, $excludes[$property])
            :
            $value;
    }

    public function toCeil($prec = 0, $excludes = []){
        return $this->roundHandle($prec, $excludes, 'ceil');
    }

    public function toRound($prec = 0, $excludes = []){
        return $this->roundHandle($prec, $excludes);
    }
}
