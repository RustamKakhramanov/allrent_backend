<?php

namespace App\DTOs;

class SendSmsDTO extends DTO
{

    // 'from' => 'Record service'
    protected string $recipient;
    protected string $text;

    protected function  init($properties)
    {
        [$this->recipient, $this->text] = $properties;

        if (isset($properties[2])) {
            $this->from = $properties[2];
        }
    }
}
