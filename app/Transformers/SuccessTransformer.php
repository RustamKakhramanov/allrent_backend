<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use OpenApi\Annotations as OA;

/**
 * Class SuccessTransformer
 * @package App\Transformers
 *
 * @OA\Schema(
 *  title="Success model",
 *  @OA\Property(property="status", type="boolean"),
 * )
 */
class SuccessTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param bool $status
     * @return array
     */
    public function transform(bool $status)
    {
        return [
            'status' => $status,
        ];
    }
}
