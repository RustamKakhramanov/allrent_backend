<?php

namespace App\Services\Auth;

use App\DTOs\OAuthDataDTO as OAuthData;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class GetOAuthDataService
{
    private AccessTokenAdapter $adapter;

    public function __construct(AccessTokenAdapter $accessTokenAdapter)
    {
        $this->adapter = $accessTokenAdapter;
    }

    public function __invoke(string $email, string $password) : OAuthData
    {
        $oAuthData = $this->adapter->issueToken(
            AccessTokenAdapter::GRANT_TYPE_PASSWORD,
            ['username' => $email, 'password' => $password]
        );

        if (! isset(
            $oAuthData['token_type'],
            $oAuthData['expires_in'],
            $oAuthData['access_token'],
            $oAuthData['refresh_token']
        )) {
            throw new UnprocessableEntityHttpException(
                'Something went wrong while issuing OAuth token.'
            );
        }

        return new OAuthData($oAuthData);
    }
}
