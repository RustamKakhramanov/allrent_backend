<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Models\User;
use App\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;


class SmsNotification  extends Notification implements ShouldQueue
{
    use Queueable;

    private $text = '';

    public function via( $notifiable)
    {
        return [SmsChannel::class];
    }

    public function __construct( string $text = '')
    {
        $this->text = $text;
    }

    public function toSMS(User $notifiable)
    {
        $this->text = $this->text ?: 'Ваш код: ' .$notifiable->generateAuthCode();

           return (new SmsMessage())
        ->setTo($notifiable->phone)
        ->setText($this->text);
    }
}

