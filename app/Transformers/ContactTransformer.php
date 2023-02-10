<?php

namespace App\Transformers;

use App\Models\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{

    public function transform(Contact $contact){
        return [
            'type' => $contact->type,
            'value' => $contact->value,
            'owner' => $contact->owner,
            'description' => $contact->description,
        ];
    }

}
