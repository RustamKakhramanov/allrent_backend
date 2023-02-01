<?php

namespace App\Observers;

use App\Models\Record\Price;
use Illuminate\Support\Carbon;

class PriceObserver
{
    public function creating(Price $price){
        if(!$price->start_date){
            $price->start_date = Carbon::now(); 
        }
    }
}
