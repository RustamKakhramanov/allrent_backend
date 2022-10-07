<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ProfileUpdateRequest extends FormRequest
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
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'name' => 'string|max:255',
            'email' => auth()->user()->getEmailUpdateValidationRules($request),
            'phone' => "nullable|unique:users,phone,{$request->user()->id}|integer|digits:11",
            'password' => 'string|min:8|max:255',
        ];
    }
}
