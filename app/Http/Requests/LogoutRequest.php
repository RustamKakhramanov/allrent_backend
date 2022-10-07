<?php

namespace App\Http\Requests;

use App\NotificationReceiver;
use Illuminate\Foundation\Http\FormRequest;

class LogoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'notification_way' => 'string',
            'tag_name' => 'string',
            'notification_key' => 'string',
        ];
    }
}
