<?php

namespace App\Transformers;

use App\Models\Specialist\SpecialistProfile;
use League\Fractal\TransformerAbstract;

class SpecialistTransformer extends TransformerAbstract
{

       /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */

    protected array $availableIncludes = [
        'user', 'company'
    ];

    public function tarnsform(SpecialistProfile $profile) {
        return [
            'user_id' => $profile->id, 
            'user_id' => $profile->user_id, 
            'company_id' => $profile->company_id, 
            'info' => $profile->info,
            'specialities' => $profile->specialites ? $profile->specialites->toArray() : [],
            'created_at' => $profile->created_at,
            'updated_at' => $profile->updated_at,
        ];
    }


    public function includeCompany(SpecialistProfile $profile)
    {
        return $profile->company ? $this->item($profile->company, new CompanyTransformer()) : $this->null();
    }

    public function includeUser(SpecialistProfile $profile)
    {
        return $profile->user ? $this->item($profile->user, new UserTransformer()) : $this->null();
    }
    
}
