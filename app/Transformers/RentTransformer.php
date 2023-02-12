<?php

namespace App\Transformers;

use App\Models\Record\Rent;
use App\Models\Location\Place;
use League\Fractal\TransformerAbstract;

class RentTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
       'user', 'company', ' specialist', 'rentable'
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Rent $rent)
    {
        return [
            'id' => $rent->id,
            'user_id' => $rent->user_id,
            'company_id' => $rent->company_id,
            'type' => $rent->type,
            'amount' => $rent->amount,
            'is_paid' => $rent->is_paid,
            'currency' => $rent->currency,
            'specialist_profile_id' => $rent->specialist_profile_id,
            'rentable_id' => $rent->rentable_id,
            'rentable_type' => $rent->rentable_type,
            'detail' => $rent->detail,
            'scheduled_at' => $rent->scheduled_at,
            'scheduled_end_at' => $rent->scheduled_end_at,
            'start_at' => $rent->start_at,
            'end_at' => $rent->end_at,
            'created_at' => $rent->created_at,
            'updated_at' => $rent->updated_at,
        ];
    }

    public function includeUser(Rent $rent)
    {

        return $rent->user ? $this->item($rent->user, new UserTransformer()) : $this->null();
    }


    public function includeCompany(Rent $rent)
    {

        return $rent->company ? $this->item($rent->company, new CompanyTransformer()) : $this->null();
    }


    public function includeSpecialist(Rent $rent)
    {

        return $rent->specialist ? $this->item($rent->owner, new SpecialistTransformer()) : $this->null();
    }


    public function includeRentable(Rent $rent)
    {

        switch($rent->rentable_type){
            case Place::class:
                return $this->item($rent->rentable, new PlaceTransformer()) ;
        }
    }
}
