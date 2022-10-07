<?php

namespace App\Transformers;

use App\Permission;
use League\Fractal\TransformerAbstract;
use OpenApi\Annotations as OA;
use Spatie\Permission\Models\Permission as ModelsPermission;

/**
 * Class PermissionTransformer
 * @package App\Transformers
 *
 * @OA\Schema(
 *  title="Permission model",
 *  @OA\Property(property="id", type="integer"),
 *  @OA\Property(property="name", type="string"),
 *  @OA\Property(property="description", type="string"),
 * )
 */
class PermissionTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Permission $role
     * @return array
     */
    public function transform(ModelsPermission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'description' => $permission->description,
        ];
    }

}
