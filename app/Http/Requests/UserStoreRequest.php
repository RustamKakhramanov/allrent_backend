<?php

namespace App\Http\Requests;

use App\Rules\Lowercase;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'unique:users',
                'max:100'
            ],
            'phone' => 'nullable|integer|digits:11|unique:users,phone',
            'password' => 'required|min:8|max:255',
        ];
    }
}
