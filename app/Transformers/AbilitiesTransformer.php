<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class AbilitiesTransformer extends TransformerAbstract
{
    public function transform( $abilitiy)
    {
        return [
            'id' => $abilitiy->id,
            'icon' => $abilitiy->icon,
            'name' => $abilitiy->name,
            'value' => $abilitiy->value,
        ];
    }
}