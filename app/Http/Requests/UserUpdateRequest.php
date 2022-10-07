<?php

namespace App\Http\Requests;

use App\Rules\Lowercase;
use App\Rules\UniqueEmail;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserUpdateRequest extends FormRequest
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
        $user = $this->route('user');

        return [
            'name' => 'nullable|string|max:255',
            'email' => ['string', 'email', 'max:100',  new Lowercase, new UniqueEmail],
            'phone' => 'nullable|integer|digits:11|unique:users,phone,' . $user->id,
            'password' => 'nullable|string|min:8|max:255',
            'roles' => 'nullable|array',
            'roles.*' => 'required|string|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'required|string|exists:permissions,name',
            'tags' => 'nullable|array',
            'tags.*' => 'required|integer|exists:tags,id',
        ];
    }
}
