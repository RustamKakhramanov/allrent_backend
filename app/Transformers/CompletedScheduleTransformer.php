<?php

namespace App\Transformers;

use App\DTOs\CompletedSheduleDTO;
use League\Fractal\TransformerAbstract;

class CompletedScheduleTransformer extends TransformerAbstract
{

    public function transform(CompletedSheduleDTO $completed): array
    {
        return [
            'time' => $completed->time,
            'active' => $completed->active,
        ];
    }
}
