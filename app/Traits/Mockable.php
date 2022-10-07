<?php

namespace App\Traits;

trait Mockable
{
    private $mockable = false;


    /**
     * Set the value of mockable
     *
     * @return  self
     */
    public function setMockable(bool $mockable = true)
    {
        $this->mockable = $mockable;

        return $this;
    }

    public function isMockable()
    {
        return $this->mockable;
    }
}
