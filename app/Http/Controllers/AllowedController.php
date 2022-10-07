<?php

namespace App\Http\Controllers;

use App\Enums\TimeEnum;
use App\Enums\ScheduleTypeEnum;

class AllowedController extends Controller
{
    public function index()
    {
        return collect([
            'time' =>  $this->getTime(),
            'schedule' =>  $this->getSchedule()
        ]);
    }

    public function getTime(){
        return TimeEnum::getAllIntervals();
    } 
    
    public function getSchedule(){
        return ScheduleTypeEnum::values();
    }
}
