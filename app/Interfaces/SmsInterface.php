<?php

namespace App\Interfaces;

interface SmsInterface
{
    public function send($phone, $body, $type = null);
}
