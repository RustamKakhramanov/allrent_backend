<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait AdapterExcelable
{
    public static function createAndToExcel($adaptee)
    {
        $service = static::init($adaptee);
        $data = $service->adapting();

        return $service
            ->setAdapting($data)
            ->forExcel($data);
    }
}
