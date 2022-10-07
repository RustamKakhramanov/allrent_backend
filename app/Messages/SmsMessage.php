<?php

namespace App\Messages;

class SmsMessage
{
    /**
     * @var string|null
     */
    protected ?string $to = null;

    /**
     * @var string|null
     */
    protected ?string $text = null;


    public function setTo($to): SmsMessage {
        $this->to = $to;
        return $this;
    }


    public function setText(  $text): SmsMessage {
        $this->text = $text;
        return $this;
    }

    public function to(): ?string {
        return $this->to;
    }

    public function text() : ?string
    {
        return $this->text;
    }
}
