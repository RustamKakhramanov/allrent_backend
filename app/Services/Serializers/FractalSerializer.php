<?php

namespace App\Services\Serializers;

use League\Fractal\Serializer\ArraySerializer;

class FractalSerializer extends ArraySerializer
{


    public function collection(?string $resourceKey, array $data): array
    {

        return $resourceKey ? [$resourceKey => $data] :  $data;
    }

    public function item(?string $resourceKey, array $data): array
    {
        if ($resourceKey === false) {
            return $data;
        }
        return $data;
    }

    public function null(): ?array
    {

        return null;
    }

    public function serializeData($resourceKey, array $data)
    {
        return ['data' => $data];
    }
}
