<?php

namespace App\Transformers;

use Exception;
use App\Models\User;
use OpenApi\Annotations as OA;
use League\Fractal\TransformerAbstract;

/**
 * @OA\Schema(
 *  title="Profile model",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="email", type="string", format="email"),
 *  @OA\Property(property="email_verified_at", type="string", format="datetime"),
 *  @OA\Property(
 *     property="modules",
 *     type="array",
 *     @OA\Items(
 *         @OA\Property(property="name", type="string"),
 *         @OA\Property(property="url_prefix", type="string"),
 *         @OA\Property(property="token", type="string"),
 *         @OA\Property(property="is_public", type="boolean"),
 *     )
 *  ),
 *  @OA\Property(
 *     property="permissions",
 *     type="array",
 *     @OA\Items(type="string")
 *  ),
 *  @OA\Property(
 *     property="tags",
 *     type="array",
 *     @OA\Items(type="int")
 *  ),
 *  @OA\Property(property="created_at", type="string", format="datetime"),
 *  @OA\Property(property="updated_at", type="string", format="datetime"),
 * )
 *
 * Class ProfileTransformer
 * @package App\Transformers
 */
class ProfileTransformer extends TransformerAbstract
{

    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     * @throws Exception
     */
    public function transform ( User $user ) {
        return [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'email_verified_at' => $user->email_verified_at,
            'phone'             => $user->phone,
            'phone_verified_at' => $user->phone_verified_at,
            'permissions'       => $user->getAllPermissions()->pluck( 'name' ),
            'created_at'        => $user->created_at,
            'updated_at'        => $user->updated_at,
        ];
    }
}
