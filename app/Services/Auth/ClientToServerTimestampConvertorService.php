<?php

namespace App\Services\Auth;

/**
 * Class ClientToServerTimestampConvertorService
 * @property integer $utcOffset
 * @property integer $clientTimestamp
 * @package App\Services
 */
class ClientToServerTimestampConvertorService
{
    protected int $utcOffset = 0;
    protected ?int $clientTimestamp = null;

    public function setUTCOffset(int $utcOffset) : self
    {
        $this->utcOffset = $utcOffset;

        return $this;
    }

    public function setClientTimestamp(?int $clientTimestamp) : self
    {
        $this->clientTimestamp = $clientTimestamp;

        return $this;
    }

    public function clientTimestampOnServer(bool $toMidnight = false) : int
    {
        $clientTimestampOnServer = $this->serverTimestamp()
            + $this->timestampDifference()
            + $this->utcOffset()
        ;

        return $toMidnight
            ? strtotime(date('Y-m-d', $clientTimestampOnServer))
            : $clientTimestampOnServer;
    }

    protected function clientTimestamp() : int
    {
        return $this->clientTimestamp ?? $this->serverTimestamp();
    }

    protected function serverTimestamp() : int
    {
        return now()->timestamp;
    }

    protected function utcOffset() : int
    {
        return $this->utcOffset;
    }

    protected function timestampDifference() : int
    {
        return $this->clientTimestamp() - $this->serverTimestamp();
    }
}
