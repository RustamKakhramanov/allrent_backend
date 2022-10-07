<?php


namespace App\Enums;


use Illuminate\Http\Request;
use App\Traits\Enumiration\FullEnum;


/**
 * @method static string AuthProviderEnum PASSWORD()
 * @method static string AuthProviderEnum SMS()
 */
enum AuthProviderEnum: string
{
    use FullEnum;


    case PASSWORD = "password";
    case  SMS = "sms";

    public function getRule(): array
    {

        return match ($this) {
            static::PASSWORD => [
                'email' => 'required|string|email',
                'password' => 'required|string',
            ],
            static::SMS =>  [
                'phone' => 'required|integer|digits:11',
                'code' => 'required|integer|digits:4',
            ],
        };
    }

    public function getMatchedFields()
    {
        return match ($this) {
            static::PASSWORD => [
                'username' => 'email',
                'password' => 'password',
            ],
            static::SMS => [
                'phone' => 'phone',
                'code' => 'code',
            ],
        };
    }

    public function getFields(Request $request): array
    {
        $data = [];

        foreach ($this->getMatchedFields() as $key => $item) {
            $data[$key] = $request->get($item);
        }

        return $data;
    }

    public function getFindScope(){
        return match ($this) {
            static::PASSWORD => [
                'email' => request('email'),
            ],
            static::SMS => [
                'phone' => request('phone'),
            ],
        };
    }
}
