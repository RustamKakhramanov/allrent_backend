<?php


namespace App\Services\Auth;

use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use GuzzleHttp\Psr7\ServerRequest ;
use Laravel\Passport\Http\Controllers\AccessTokenController;




class AccessTokenAdapter
{
    const GRANT_TYPE_IMPERSONATE = 'impersonate';
    const GRANT_TYPE_PASSWORD = 'password';
    const GRANT_TYPE_REFRESH_TOKEN = 'refresh_token';
    const GRANT_TYPE_GUEST = 'guest';
    const GRANT_TYPE_SMS = 'sms';

    /**
     * @var AccessTokenController
     */
    protected $accessTokenController;
    /**
     * @var Request
     */
    protected $request;

    public function __construct(AccessTokenController $accessTokenController, Request $request)
    {
        $this->accessTokenController = $accessTokenController;
        $this->request = $request;
    }

    public function issueToken(string $grant_type, array $attributes): array
    {
        $grant_client = $this->getGrantClient();

        return $this->normalizeResponse(
            $this->accessTokenController->issueToken(
                $this->convertToPs7Request($grant_type, $grant_client, $attributes)
            )
        );
    }

    protected function normalizeResponse($response)
    {
        return json_decode($response->getContent(), true);
    }

    protected function getGrantClient()
    {
        return Passport::client()->where('name', 'Laravel Password Grant Client')->first();
    }

    protected function convertToPs7Request($grant_type, $client, $attributes)
    {

       return  (new ServerRequest(
            $this->request->method(),
            $this->request->getUri(),
            $this->request->headers->all(),
        ))->withParsedBody(collect([
            'grant_type' => $grant_type,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => '',
        ])->merge($attributes)->toArray());

    }
}
