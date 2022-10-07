<?php

namespace App\Services\Auth\Grants;

use App\Models\User as UserModel;
use DateInterval;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use League\OAuth2\Server\RequestEvent;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;
use Psr\Http\Message\ServerRequestInterface;

class SmsGrant extends AbstractGrant
{

    public function __construct(RefreshTokenRepositoryInterface $refreshTokenRepository) {
        $this->setRefreshTokenRepository($refreshTokenRepository);

        $this->refreshTokenTTL = new DateInterval('P1M');
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseTypeInterface $responseType
     * @param DateInterval $accessTokenTTL
     * @return ResponseTypeInterface|void
     * @throws OAuthServerException
     */
    public function respondToAccessTokenRequest(ServerRequestInterface $request, ResponseTypeInterface $responseType, DateInterval $accessTokenTTL)
    {

        $client = $this->validateClient($request);
        $scopes = $this->validateScopes($this->getRequestParameter('scope', $request, $this->defaultScope));
        $user = $this->validateUser($request, $client);

        $finalizedScopes = $this->scopeRepository->finalizeScopes($scopes, $this->getIdentifier(), $client, $user->getIdentifier());

        $accessToken = $this->issueAccessToken($accessTokenTTL, $client, $user->getIdentifier(), $finalizedScopes);
        $this->getEmitter()->emit(new RequestEvent(RequestEvent::ACCESS_TOKEN_ISSUED, $request));

        $responseType->setAccessToken($accessToken);
        $refreshToken = $this->issueRefreshToken($accessToken);

        if ($refreshToken !== null) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::REFRESH_TOKEN_ISSUED, $request));
            $responseType->setRefreshToken($refreshToken);
        }

        return $responseType;

    }

    /**
     * @param ServerRequestInterface $request
     * @param ClientEntityInterface $client
     * @return User
     * @throws OAuthServerException
     */
    protected function validateUser(ServerRequestInterface $request, ClientEntityInterface $client)
    {
        $phone = $this->getRequestParameter('phone', $request);

        if (is_null($phone)) {
            throw OAuthServerException::invalidRequest('phone');
        }

        $code = $this->getRequestParameter('code', $request);

        if (is_null($code)) {
            throw OAuthServerException::invalidRequest('code');
        }

        $user = UserModel::findByPhone($phone);

        if ($user instanceof UserModel === false) {
            $this->getEmitter()->emit(new RequestEvent(RequestEvent::USER_AUTHENTICATION_FAILED, $request));

            throw OAuthServerException::invalidClient($request);
        }

        if (!$user->verifyAuthCode($code)) {
            throw OAuthServerException::invalidClient($request);
        }

        $user->forgetAuthCode();

        return new User($user->getAuthIdentifier());
    }


    /**
     * @inheritDoc
     */
    public function getIdentifier()
    {
        return 'sms';
    }
}
