<?php

namespace App\Repositories;

use App\DTOs\HomeDTO;
use App\Enums\HomePageEnum;
use App\Models\Company\Company;
use App\Repositories\Company\CompanyRepository;
use App\Repositories\Location\PlaceRepository;
use Illuminate\Support\Collection;

class HomeRepository
{
    private Collection $data;
    protected string $type;

    public static function parse(HomePageEnum $type, $additional = []): HomeDTO
    {
        return (new static($type))->getAllData($additional);
    }

    public function __construct(HomePageEnum $type)
    {
        $this->type = $type->value;
        $this->data = collect();
    }


    public function getAllData($additional)
    {
        switch ($this->type) {
            case HomePageEnum::Company():
                $this->setCompanyData($additional);
                break;
            case HomePageEnum::Place():
                $this->setPlaceData($additional);
                break;
        }

        return HomeDTO::make([...$this->data, 'type' => $this->type]);
    }

    protected function setCompanyData()
    {
        $this->data = $this->data->merge(['company' => CompanyRepository::first()]);
    }

    protected function setPlaceData($additional = [])
    {
        if (isset($additional['place_slug']) && isset($additional['company_slug'])) {
            $place  = PlaceRepository::whereSlug($additional['place_slug'])->whereHas('company', fn($q) => $q->where('slug', $additional['company_slug']))->first();
        }

        $this->data = $this->data->merge(['place' => $place ?? PlaceRepository::where('id', 1)->first()]);
    }
}
