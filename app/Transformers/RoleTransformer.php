<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use Spatie\Permission\Models\Role;
use OpenApi\Annotations as OA;


/**
 * Class RoleTransformer
 * @package App\Transformers
 *
 * @OA\Schema(
 *  title="Role model",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="description", type="string"),
 * )
 *
 */
class RoleTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Role $role
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'name' => $role->name,
            'description' => $role->description,
        ];
    }

}
