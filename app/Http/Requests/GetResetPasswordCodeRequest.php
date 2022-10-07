<?php

namespace App\Http\Requests;

use App\NotificationReceiver;
use Illuminate\Foundation\Http\FormRequest;

class GetResetPasswordCodeRequest extends FormRequest
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
            'tag_id' => 'nullable|integer|exists:tags,id',
            'notification_way' => 'required|string',
            'notification_key' => 'required|string',
        ];
    }
}
