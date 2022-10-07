<?php

namespace App\Services\Notify;

use GuzzleHttp\Client;
use Mobizon\MobizonApi;
use App\DTOs\SendSmsDTO;
use Illuminate\Support\Arr;
use App\Interfaces\SmsInterface;

class SmsService implements SmsInterface
{

    public function __construct(protected $sms_client)
    {
        // $sms_client->call(
        //     'message',
        //     'sendSMSMessage',
        //     [
        //         'recipient' => '+77713602692',
        //         'text' => 'Test sms message',
        //         // 'from' => 'Record service'
        //     ],
        // );
    }




    private function request(array $method_params, array $data)
    {
        [$type, $method] = $method_params;

        $this->sms_client->call(
            $type,
            $method,
            $data
        );

        // return json_decode( $response->getBody()->getContents(), true );
    }


    public function send(
        $phone,
        $body = '',
        $type = 'message'
    ) {
        switch (gettype($phone)) {
            case 'string':
                $method = 'oneSMS';
            default:
                $method = 'oneSMS';
        }

        return $this->$method($phone, $body);
    }


    private function oneSMS($phone, $body, $from = '')
    {
        return $this->request(
            [
                'message',
                'sendSMSMessage',
            ],
            ( SendSmsDTO::make($phone, $body, $from))->toArray()

        );
    }


    public function status(int $id)
    {
        // $messageId = $api->getData('messageId');
        // echo 'Message created with ID:' . $messageId . PHP_EOL;

        // if ($messageId) {
        //     echo 'Get message info...' . PHP_EOL;
        //     $messageStatuses = $api->call(
        //         'message',
        //         'getSMSStatus',
        //         array(
        //             'ids' => array($messageId, '13394', '11345', '4393')
        //         ),
        //         array(),
        //         true
        //     );

        //     if ($api->hasData()) {
        //         foreach ($api->getData() as $messageInfo) {
        //             echo 'Message # ' . $messageInfo->id . " status:\t" . $messageInfo->status . PHP_EOL;
        //         }
        //     }
        // }
    }
}
