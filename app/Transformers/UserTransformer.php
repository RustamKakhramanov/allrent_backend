<?php

namespace App\Transformers;

use App\Models\User;
use OpenApi\Annotations as OA;
use League\Fractal\TransformerAbstract;


/**
 * Class UserTransformer
 * @package App\Transformers
 *
 * @OA\Schema(
 *  schema="User",
 *  title="[CORE] User",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="email", type="string", format="email"),
 *  @OA\Property(property="email_verified_at", type="string", format="datetime"),
 *  @OA\Property(property="customer_id", type="integer"),
 *  @OA\Property(
 *     property="roles",
 *     type="array",
 *     @OA\Items(type="string")
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
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
