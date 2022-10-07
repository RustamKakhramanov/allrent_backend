<?php

namespace App\Channels;

use App\Services\Notify\SmsService;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    private SmsService $smsService;

    /**
     * @var SmsService
     */

    public function __construct( SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param Notification $notification
     * @return void
     */
    public function send($notifiable,  $notification)
    {
        $message = $notification->toSMS($notifiable);

        $this->smsService->send($message->to(), $message->text());
    }
}
