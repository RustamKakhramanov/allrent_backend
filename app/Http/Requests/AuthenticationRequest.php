<?php

namespace App\Http\Requests;

use App\Enums\AuthProviderEnum;
use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
        $grant = AuthProviderEnum::tryFrom(request('provider', 'password'));

        return array_merge($grant->getRule(), [
            'provider' => 'nullable|in:' . implode(',', AuthProviderEnum::toArrayCases())
        ]);
    }
}
