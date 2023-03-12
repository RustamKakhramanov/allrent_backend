<?php

namespace App\Enums;

use App\Traits\Enumiration\FullEnum;

enum ContactEnum:string
{
    use FullEnum;
    
    case Phone = 'phone';
    case WhatsApp = 'whatsApp';
    case Mail = 'mail';
    case Instagram = 'instagram';
    case Telegram = 'telegram';
    
    public function getName(){
        return match($this){
            static::Phone => 'Телефон',
            static::WhatsApp => 'WhatsApp',
            static::Mail => 'Почта',
            static::Instagram => 'Instagram',
            static::Telegram => 'Telegram',
        };
    }
}
