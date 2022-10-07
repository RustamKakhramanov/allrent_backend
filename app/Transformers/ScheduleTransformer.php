<?php

namespace App\Transformers;

use App\Models\Record\Schedule;
use League\Fractal\TransformerAbstract;

class ScheduleTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        'user', 'company'
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Schedule $schedule)
    {
        return [
            'id' => $schedule->id,
            'company_id' => $schedule->company_id,
            'schedule' => $schedule->schedule,
            'date' => $schedule->date
        ];
    }

    public function includeUser(Schedule $schedule)
    {
        return $schedule->user && user() && $this->isCanViewUser($schedule) ? $this->item($schedule->user, new UserTransformer()) : $this->null();
    }

    protected function includeCompany(Schedule $schedule)
    {
        return $schedule->company ? $this->item($schedule->company, new UserTransformer()) : $this->null();
    }  
    
    protected function isCanViewUser(Schedule $schedule)
    {
        return $schedule->company && user()->isMember($schedule->company) || user()->isScheduler($schedule);
    }
}
