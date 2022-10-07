<?php

namespace App\Traits\Eloquent;

trait ExtendedBuilds
{
    protected $oderCaseColumn;

    public function prepareOrderByRaw($array, $colunn = null): string
    {
        $colunn = $colunn ?: $this->oderCaseColumn;
        $str = 'CASE ' . $colunn;

        foreach (array_values($array) as $index => $key) {
            $str .= " WHEN '" . $key . "' THEN " . $index + 1 . " ";
        }

        $str .= ' END ASC';

        return $str;
    }
}
