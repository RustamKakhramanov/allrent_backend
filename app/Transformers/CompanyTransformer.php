<?php

namespace App\Transformers;

use App\DTOs\ImageDTO;
use App\Models\Company\Company;
use App\Models\Location\Place;
use App\Models\Record\Rent;
use App\Models\Record\Schedule;
use League\Fractal\TransformerAbstract;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class CompanyTransformer extends TransformerAbstract
{


    protected array $availableIncludes = [
        'places', 'members', 'owner', 'images', 
    ];

    protected array $defaultIncludes = ['logo'];

    /**
     * A Fractal transformer.
     *
     * @param Company $country
     * @return array
     */
    public function transform(Company $company)
    {
        return [
            'id' => $company->id,
            'slug' => $company->slug,
            'name' => $company->name,
            'description' => $company->description,
            'info' => $company->info,
        ];
    }

    public function includeImages(Company $company)
    {
        return $this->collection(
            collect([
                url('/storage/images/first.jpg')
            ])->map(
                fn ($item) => ImageDTO::make($item)
            ),
            new ImageTransformer
        );
    }

    public function includeLogo(Company $company)
    {
        return $this->item(
            ImageDTO::make(['url' => url('/storage/images/first.jpg')]),
            new ImageTransformer
        );
    }

    public function includePlaces(Company $company)
    {


        return $company->places ? $this->collection($company->places, new PlaceTransformer()) : $this->primitive([]);
    }


    public function includeOwner(Company $company)
    {

        return $company->owner ? $this->item($company->owner, new UserTransformer()) : $this->null();
    }

    public function includeMembers(Company $company)
    {

        return $company->members ? $this->collection($company->members, new UserTransformer()) : $this->primitive([]);
    }
}
