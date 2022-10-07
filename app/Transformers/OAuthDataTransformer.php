<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\DTOs\OAuthDataDTO as OAuthData;

/**
 * @OA\Schema(
 *     schema="OAuthDataTransformer",
 *     title="[CORE] OAuth Data Object",
 *     @OA\Property(property="token_type", type="string"),
 *     @OA\Property(property="expires_in", type="string"),
 *     @OA\Property(property="access_token", type="string"),
 *     @OA\Property(property="refresh_token", type="string"),
 * )
 *
 * @package App\Transformers
 */
class OAuthDataTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [
        'user'
    ];

    public function transform(OAuthData $oAuthData): array
    {
        return $oAuthData->toArray();
    }

    public function includeUser(OAuthData $oAuthData)
    {
        return $oAuthData->user ? $this->item($oAuthData->user, new UserTransformer) : $this->primitive(null);
    }
}
