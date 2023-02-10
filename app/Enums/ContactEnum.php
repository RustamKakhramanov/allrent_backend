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
}
