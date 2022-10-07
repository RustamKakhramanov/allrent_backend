<?php

namespace App\Http\Requests;

use App\NotificationReceiver;
use Illuminate\Foundation\Http\FormRequest;

class AddNotificationReceiverRequest extends FormRequest
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
            'tag_name' => 'nullable|string|exists:tags,name',
            'way' => 'required|string|in:' . implode(',', NotificationReceiver::AVAILABLE_WAYS),
            'key' => 'required|string|max:255',
            'is_enabled' => 'boolean',
        ];
    }
}
